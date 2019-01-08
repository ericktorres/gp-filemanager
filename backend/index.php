<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app['debug'] = true;

// Local Database connection
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	'db.options' => array(
	   'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'user' => 'gpfilemanager',
        'password' => 'GpF1l3M4n4ger.',
        'dbname' => 'gp_filemanager',
        'charset' => 'utf8'
	),
));

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/templates',
));

$app->before(function ($request) {
    $request->getSession()->start();
});

require_once("controllers/login.php");
require_once("controllers/console.php");
require_once("controllers/users.php");
require_once("controllers/filemanager.php");

$app->run();