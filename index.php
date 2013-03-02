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
	$app->match('', function() use ($app, $gearmand) {
		$status = $gearmand->status();
		return $app['twig']->render('index.twig', array(
			'status' => $status->status,
			'workers' => $status->workers,
		));
	})
	->bind('home')
	->method('GET');

	$app->match('/quota', function() use ($app, $gearmand) {
		if( ! isset($_POST['submit']))
		{
			return $app['twig']->render('quota.twig', array(
				'status' => $gearmand->getStatus(),
			));
		}
		else
		{
			if( ! is_numeric($_POST['size']))
			{
				$app->error(function() use ($app) {
					$msg = '"Function quota size" ' . ($_POST['size'] == "" ? "can't be empty" : 'can be only digits' );
					return new Response($app['twig']->render('error.twig', array(
						'message' => $msg
					)), 400);
				});
			}
			else
			{
				$gearmand->setMaxQueue($_POST['function'],$_POST['size']);
				return $app['twig']->render('quota.twig', array(
					'status' => $gearmand->getStatus(),
					'flashMessage' => true,
				));
			}
		}
	})
	->bind('quota')
	->method('GET|POST');

	$app->match('/shutdown/{var}', function($var) use ($app, $gearmand) {
		switch((int)$var)
		{
			case 0:
				$gearmand->shutdown();
				return $app->redirect('/');
				break;
			case 1:
				$gearmand->shutdown(TRUE);
				return $app->redirect('/');
				break;
			default:
				return $app->redirect('/');
				break;
		}
	})->bind('shutdown');
}
else
{
	$app->error(function() use ($app) {
		return new Response($app['twig']->render('error.twig', array(
			'message' => 'Can not connect to Gearmand or Gearmand not responding.'
		)), 503);
	});
}
$app->run();