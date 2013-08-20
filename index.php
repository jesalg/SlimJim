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

    function cidr_match($ip, $cidrs) {
        $result = false;
        foreach($cidrs as $cidr) {
            list($subnet, $mask) = explode('/', $cidr);
            if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet)) {
                $result = true;
            }
        }
        return $result;
    }

	$app = new Slim(array(
		'settings' => $settings
	));

	$app->post('/deploy', function () use ($app) {
		$deploy_settings = $app->config('settings');

        $github_meta = json_decode(file_get_contents('https://api.github.com/meta'), true);
        $cidrs = $github_meta['hooks'];

		if(!cidr_match($app->request()->getIp(), $cidrs)) {
			$app->halt(401);
		}

		if(! ($payload = $app->request()->params('payload'))) {
			$app->halt(403);
		}

		$payload = json_decode($payload);
		
		if(isset($payload->repository) && isset($payload->ref)) {
			$payload_branch = explode("/", $payload->ref);
			$payload_branch = $payload_branch[2];

			$project = Model::factory('Project')
				->where_equal('name', $payload->repository->name)
				->where_equal('branch', $payload_branch)
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
		} else {
			$app->halt(400);
		}
	});

	$app->run();