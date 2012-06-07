<?php

chdir(__DIR__);

$previousDir = '.';
while (!file_exists('config/application.config.php')) {
    $dir = dirname(getcwd());
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        break;
    }
    if($previousDir === $dir) {
        break;
    }
    $previousDir = $dir;
    chdir($dir);
}

if (!in_array('vendor/autoload.php',get_included_files())) {
    defined('ZF2_PATH') || define('ZF2_PATH', getenv('ZF2_PATH') ?: '');
    require_once ZF2_PATH . 'Zend/Loader/StandardAutoloader.php';
    $loader = new Zend\Loader\StandardAutoloader(array(
        'Zend' => ZF2_PATH . 'Zend'
    ));
    $loader->register();
}

require_once __DIR__ . '/../autoload_register.php';

