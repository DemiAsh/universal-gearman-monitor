<?php

require_once __DIR__ . '/lib/class_GearmanStatus.php';
require_once __DIR__ . '/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__ . '/themes/views');
$twig = new Twig_Environment($loader);


/**
 * default gearmand-server listen 127.0.0.1:4730
 * if u wanna another server:
 * $gearmand = new GearmanStatus("IP or domain", port);
 */
$gearmand = new GearmanStatus();

if( $gearmand->getStatus() )
{
	echo $twig->render('index.twig', array(
		'status' => (isset($gearmand->getStatus()->status) ? $gearmand->getStatus()->status : null),
		'workers' => (isset($gearmand->getStatus()->workers) ? $gearmand->getStatus()->workers : null),
	));
}
else
{
	echo $twig->render('error.twig');
}
