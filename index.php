<?php


require_once __DIR__ . '/lib/class_GearmanStatus.php';
require_once __DIR__ . '/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__ . '/themes/views');
$twig = new Twig_Environment($loader);

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