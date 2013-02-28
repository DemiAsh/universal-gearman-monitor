<?php

error_reporting(65535);
ini_set("display_errors", 1);

require_once __DIR__ . '/lib/class_GearmanStatus.php';
require_once __DIR__ . '/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__ . '/themes/views');
$twig = new Twig_Environment($loader, array(
        'cache' => __DIR__ . '/themes/views/cache',
        'debug' => true
	));
$twig->addExtension(new Twig_Extension_Debug());

/**
* default gearmand-server listen 127.0.0.1:4730
* if u wanna another server:
* $gearmand = new GearmanStatus("IP or domain", port);
*/
$gearmand = new GearmanStatus();
$status = $gearmand->status();

if( $status )
{
    $twig->display('index.twig', array(
        'status' => (isset($status->status) ? $status->status : null),
		'workers' => (isset($status->workers) ? $status->workers : null),
	));
}
else
{
	$twig->display('error.twig');
}
