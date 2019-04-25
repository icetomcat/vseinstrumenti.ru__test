<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
setlocale(LC_ALL, "ru_RU.UTF-8");

define('START_TIME', microtime(true));
define('ROOT', __DIR__ . "/../private/");

if (version_compare(phpversion(), '5.6.0', '<') == true)
{
	die('PHP 5.6 Only');
}

$loader = include_once __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4("", ROOT);
