<?php
	require 'libs/Slim/Slim.php';
	require 'libs/Paris/idiorm.php';
	require 'libs/Paris/paris.php';
	require 'models/Project.php';
	require 'models/Setting.php';

	ORM::configure('mysql:host=localhost;dbname=slimjim');
	ORM::configure('username', 'root');
	ORM::configure('password', '');

	$settings = array();

	foreach((Model::factory('Setting')->find_many() ?: array()) AS $obj) {
		$settings[$obj->key] = $obj->value;
	}

	$app = new Slim(array(
		'settings' => $settings
	));

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

    function create_request($repo_name, $repo_branch) {
		$project = Model::factory('Project')
			->where_equal('name', $repo_name)
			->where_equal('branch', $repo_branch)
			->find_one();

		if($project) {
			file_put_contents('requests/' . $payload->after . '.txt', serialize(array(
				'name' => $project->name,
				'clone_url' => $project->clone_url,
				'path' => $project->path,
				'branch' => $project->branch,
				'hook_path' => $project->path . $deploy_settings['hook_file']
			)), LOCK_EX);
		}
    };

    $app->get('/', function () use ($app) {
    	echo "Silence is golden";
    });

	$app->post('/gh_hook', function () use ($app) {
		$deploy_settings = $app->config('settings');

        $github_meta = json_decode(file_get_contents('https://api.github.com/meta'), true);
        $cidrs = $github_meta['hooks'];

		if(!cidr_match($app->request()->getIp(), $cidrs)) {
			$app->halt(401);
		}

		if(!($payload = $app->request()->params('payload'))) {
			$app->halt(403);
		}

		$payload = json_decode($payload);
		
		if(isset($payload->repository) && isset($payload->ref)) {
			$payload_branch = explode("/", $payload->ref);
			$payload_branch = $payload_branch[2];
			create_request($payload->repository->name, $payload_branch);
		} else {
			$app->halt(400);
		}
	});

	$app->post('/bb_hook', function () use ($app) {
		$deploy_settings = $app->config('settings');

        $bitbucket_ips = array('131.103.20.165','131.103.20.166');
        $request = $app->request();

        if(!in_array($request->getIp(), $bitbucket_ips)) {
        	$app->halt(401);
        }

		if(!($payload = $request->getBody())) {
			$app->halt(403);
		}

		$payload = json_decode($payload);

		if(isset($payload->repository)) {
			$payload_branch = $payload->commits[0]->branch;
			create_request($payload->repository->slug, $payload_branch);
		} else {
			$app->halt(400);
		}
	});

	$app->run();