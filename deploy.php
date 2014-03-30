#!/usr/bin/php
<?php
	require "config.php";

	chdir(CUSTOM_CONFIG::$ROOT_PATH);

	require "libs/spyc.php";

	@ list(, $request_file) = $_SERVER['argv'];

	if ($request_file && file_exists('requests/' . $request_file)) {
		if($request_data = file_get_contents('requests/' . $request_file)) {
			unlink('requests/' . $request_file);

			$request_data = unserialize($request_data);

			chdir($request_data['path']);

			foreach(array(
				'git clone ' . $request_data['clone_url'] . ' ./',
				'git reset --hard',
				'git checkout ' . $request_data['branch'],
				'git fetch origin',  
				'git rebase origin/'. $request_data['branch'] . ' ' . $request_data['branch'],
				'git status',
			) AS $command)
			{
				syslog(LOG_INFO, $command);
				syslog(LOG_INFO, "===== " . shell_exec($command . " 2>&1"));
			}

			sleep(1);

			$hooks = @ Spyc::YAMLLoad($request_data['hook_path']) ?: array();

			syslog(LOG_INFO, serialize($hooks));

			if(isset($hooks['writable'])) {
				foreach($hooks['writable'] AS $make_writeable) {
					$cmd = "chmod 777 -R " . $make_writeable . " 2>&1";
					syslog(LOG_INFO, $cmd);
					syslog(LOG_INFO, "===== " . shell_exec($cmd));
				}
			}

			if(isset($hooks['after_deploy'])) {
				foreach($hooks['after_deploy'] AS $after_deploy) {
					$cmd = $after_deploy . " 2>&1";
					syslog(LOG_INFO, $cmd);
					syslog(LOG_INFO, "===== " . shell_exec($cmd));
				}
			}
		}
	}