<?php

error_reporting(65535);
ini_set("display_errors", 1);

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

use Symfony\Component\HttpFoundation\Response;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/themes/views',
	'twig.options' => array( 'cache' => __DIR__ . '/themes/views/cache' ),
));

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
	$twig->addGlobal('media', '/themes/media');
	return $twig;
}));


$app['debug'] = true;
$gearmand = GearmanStatus::factory("127.0.0.1", 4730);

if( $gearmand->up )
{
	$app->get('', function() use ($app, $gearmand) {
		$status = $gearmand->status();
		return $app['twig']->render('index.twig', array(
					'status' => $status->status,
					'workers' => $status->workers,
		));
	})->bind('home');

	$app->get('/quota', function() use ($app, $gearmand) {

		return $app['twig']->render('quota.twig', array());
	})->bind('quota');

	$app->get('/shutdown', function() use ($app, $gearmand) {

		return $app['twig']->render('shutdown.twig', array());
	})->bind('shutdown');
}
else
{
	$app->error(function() use ($app) {
		return new Response($app['twig']->render('error.twig'), 503);
	});
}
$app->run();