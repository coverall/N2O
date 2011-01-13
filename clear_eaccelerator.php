<?php
// $Id: clear_eaccelerator.php,v 1.2 2005/03/27 21:57:40 patrick Exp $

require_once('PEAR/Command.php');
require_once('Console/Getopt.php');

PEAR_Command::setFrontendType('CLI');
$all_commands = PEAR_Command::getCommands();

$argv = Console_Getopt::readPHPArgv();

if (sizeof($argv) < 2)
{
	echo 'Usage: ' . $argv[0] . ' <url to eaccelerator.php> [username] [password]' . "\n";
	exit();
}

$url = $argv[1];

if (sizeof($argv) == 4)
{
	$auth = $argv[2] . ':' . $argv[3];
}
else
{
	$auth = 'retrix:';
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_USERPWD, $auth);
curl_setopt($curl, CURLOPT_POSTFIELDS, 'clear=Clear');

$response = curl_exec($curl);

curl_close($curl);

?>
