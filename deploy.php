#!/usr/bin/php
<?php
$argv = $_SERVER['argv'];

if (file_exists($argv[0])) {

	//Get first line
	$request = fopen($argv[0]);
	$arguments = fgets($request);
	fclose($request);

	//Split string
	$params = explode("|", $arguments);

	//Run commands on repo
	$commands = array(
	    'cd ' . $params[0],
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