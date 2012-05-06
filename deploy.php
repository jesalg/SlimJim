#!/usr/bin/php
<?php
$argv = $_SERVER['argv'];

$commands = array(
    'cd ' . $argv[0],
    'git reset --hard',
    'git checkout ' . $argv[1],
    'git fetch origin',  
    'git rebase origin/'. $argv[1] . ' ' . $argv[1],
    'git status',
);

$output = '';
foreach($commands AS $command) {
    $tmp = shell_exec($command . " 2>&1");
    $output .= "{$command}\n";
    $output .= htmlentities(trim($tmp)) . "\n";
}

echo $output;
?>