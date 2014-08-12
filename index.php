<?php
	require 'libs/Slim/Slim.php';
	require 'libs/Paris/idiorm.php';
	require 'libs/Paris/paris.php';
	require 'models/Project.php';
	require 'models/Setting.php';
	require 'config.php';

	ORM::configure('mysql:host='.CUSTOM_CONFIG::$DB_HOST . ';dbname='.CUSTOM_CONFIG::$DB_NAME);
	ORM::configure('username', CUSTOM_CONFIG::$DB_USER);
	ORM::configure('password', CUSTOM_CONFIG::$DB_PASS);

	$settings = array();

	foreach((Model::factory('Setting')->find_many() ?: array()) AS $obj) {
		$settings[$obj->key] = $obj->value;
	}

	$app = new Slim(array(
		'settings' => $settings
	));

	function get_github_meta() {
		$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL,'https://api.github.com/meta');
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'SlimJim');
		/* Curl SSL verification can be bypassed, e.g. for a server with self-signed certificates without ca-chain. */
		$curl_ssl_verification	= (isset(CUSTOM_CONFIG::$GITHUB_CURL_SSL_VERIFICATION))	?	(bool)CUSTOM_CONFIG::$GITHUB_CURL_SSL_VERIFICATION	:	true;
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, ($curl_ssl_verification ? 2 : false));
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, $curl_ssl_verification);
		$response = curl_exec($curl_handle);
		$github_meta = json_decode($response);
		curl_close($curl_handle);
		return $github_meta;
	};

    function cidr_match($ip, $cidrs) {
        $result = false;
        foreach($cidrs as $cidr) {
            list($subnet, $mask) = explode('/', $cidr);
            if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet)) {
                $result = true;
            }
        }
        return $result;
    };

    function create_request($repo_name, $repo_branch, $after_sha) {
    	global $app;
    	$deploy_settings = $app->config('settings');

		$arrProjects = Model::factory('Project')
			->where_equal('name', $repo_name)
			->where_equal('branch', $repo_branch)
			->find_many();

		foreach ($arrProjects as $project) {
			if($project) {
				file_put_contents('requests/' . $project->id . "_" . $after_sha . '.txt', serialize(array(
					'name' => $project->name,
					'clone_url' => $project->clone_url,
					'path' => $project->path,
					'branch' => $project->branch,
					'hook_path' => $project->path . $deploy_settings['hook_file']
				)), LOCK_EX);
			}
		}
    };

    $app->get('/', function() use ($app) {
    	echo "Silence is golden";
    });

	$app->post('/gh_hook', function() use ($app) {
		
		if (!isset(CUSTOM_CONFIG::$GITHUB_CIDR_VERIFICATION) || CUSTOM_CONFIG::$GITHUB_CIDR_VERIFICATION) {
			
			$github_meta = get_github_meta();
	        $cidrs = isset($github_meta->hooks)	?	$github_meta->hooks	:	array();
	
			if(!cidr_match($app->request()->getIp(), $cidrs)) {
				$app->halt(401);
			}
			
		}

		if(!($payload = $app->request()->params('payload'))) {
			$app->halt(403);
		}

		$payload = json_decode($payload);
		
		if(isset($payload->repository) && isset($payload->ref)) {
			$payload_branch = explode("/", $payload->ref);
			//Allow branches with slashes, such as implemented by git-flow (e.g. release/v1.1)
			$payload_branch = substr($payload->ref, strlen($payload_branch[0]."/".$payload_branch[1])+1);
			create_request($payload->repository->name, $payload_branch, $payload->after);
		} else {
			$app->halt(400);
		}
	});

	$app->post('/bb_hook', function() use ($app) {
        $bitbucket_ips = array('131.103.20.165','131.103.20.166');
        
        if(!in_array($app->request()->getIp(), $bitbucket_ips)) {
        	$app->halt(401);
        }

		if(!($payload = $app->request()->params('payload'))) {
			$app->halt(403);
		}

		$payload = json_decode($payload);

		if(isset($payload->repository)) {
			$payload_branch = $payload->commits[0]->branch;
			$after_sha = $payload->commits[0]->parents[0];
			create_request($payload->repository->slug, $payload_branch, $after_sha);
		} else {
			$app->halt(400);
		}
	});

	$app->run();