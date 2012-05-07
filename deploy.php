#!/usr/bin/php
<?php

chdir('/srv/www/slimjim.ruddl.com/public_html/');

$argv = $_SERVER['argv'];

if (file_exists("./requests/".$argv[1])) {

	//Get first line
	$request = fopen("./requests/".$argv[1], "r");
	$arguments = fgets($request);
	fclose($request);

	//Split string
	$params = explode("|", $arguments);

	//Change the working dir
	chdir($params[0]);

	//Run commands on repo
	$commands = array(
	    'unset GIT_DIR',
	    'git reset --hard',
	    'git checkout ' . $params[1],
	    'git fetch origin',  
	    'git rebase origin/'. $params[1] . ' ' . $params[1],
	    'git status',
	);

	$output = '';
	foreach($commands AS $command) {
	    $tmp = shell_exec($command . " 2>&1"); //> /dev/null 2>&1 &
	    $output .= "{$command}\n";
	    $output .= htmlentities(trim($tmp)) . "\n";
	}

	echo $output;

	//TODO: Delete request file
}

?>