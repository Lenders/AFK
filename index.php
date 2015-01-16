<?php
define('START_TIME', microtime(true));
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS);
define('EXT', '.php');
define('CONFIG_FILE', ROOT . 'app' . DS . 'config' . DS . 'config' . EXT);
define('DEBUG', false);

if(DEBUG){
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('html_errors', true);
}

require ROOT . 'system' . DS . 'Loader.php';

$loader = new \system\Loader();
$config = \system\Config::build(CONFIG_FILE);

$base = new \system\Base($loader, $config);
$base->run();