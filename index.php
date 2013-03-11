<?php
	require 'libs/Slim/Slim.php';
	require 'libs/Paris/idiorm.php';
	require 'libs/Paris/paris.php';
	require 'models/Project.php';
	require 'models/Setting.php';

	ORM::configure('mysql:host=localhost;dbname=slimjim');
	ORM::configure('username', 'username');
	ORM::configure('password', 'password');

	$settings = array();

	foreach((Model::factory('Setting')->find_many() ?: array()) AS $obj) {
		$settings[$obj->key] = $obj->value;
	}

	$app = new Slim(array(
		'settings' => $settings
	));

	$app->post('/deploy', function () use ($app) {
		$deploy_settings = $app->config('settings');

		if(! in_array($app->request()->getIp(), explode(',', $deploy_settings['allowed_from']))) {
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