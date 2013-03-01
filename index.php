<?php

require_once __DIR__ . '/lib/class_GearmanStatus.php';
require_once __DIR__ . '/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__ . '/themes/views');
$twig = new Twig_Environment($loader, array(
		'cache' => __DIR__ . '/themes/views/cache'
	));

$status = null;
$status = GearmanStatus::factory("127.0.0.1", 4730)->status();

var_dump($status->workers);

if( $status )
{
	$twig->display('index.twig', array(
		'status' => $status->status,
		'workers' => $status->workers,
	));
}
else
{
	$twig->display('error.twig');
}
