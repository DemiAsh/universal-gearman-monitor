<?php

error_reporting(65535);
ini_set("display_errors", 1);

require_once __DIR__ . '/lib/class_GearmanStatus.php';
require_once __DIR__ . '/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__ . '/themes/views');
$twig = new Twig_Environment($loader, array(
		'debug' => true,
	));
$twig->addExtension(new Twig_Extension_Debug());

//$twig->addGlobal('base', 'http://'.$_SERVER['HTTP_HOST'].'/themes/media');

$gearmand = new GearmanStatus();

if( $gearmand->getStatus() )
{
	echo $twig->render('index.twig', array(
		'status' => $gearmand->getStatus()->status,
		'workers' => $gearmand->getStatus()->workers,
	));
}
else
{
	echo $twig->render('error.twig');
}