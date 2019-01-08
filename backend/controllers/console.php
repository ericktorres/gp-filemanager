<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/console', function() use ($app){
	$session_data = $app['session']->get('session_data');

	return $app['twig']->render(
		'console.html.twig',
		array('session_data'=>$session_data)
	);
});