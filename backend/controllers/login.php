<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/login', function() use ($app){
	return $app['twig']->render('login.html.twig');
});

// This controller authenticate a user
$app->post('/login/auth', function(Request $request) use ($app){
	$username = $_POST['username'];
	$password = sha1($_POST['password']);

	$sql = $app['db']->createQueryBuilder();
	$sql
		->select('COUNT(*) AS login')
		->from('user', 'u')
		->where('u.username = ?')
		->andWhere('u.password = ?')
		->andWhere('u.status = 1');
	$stmt = $app['db']->prepare($sql);
	$stmt->bindValue(1, $username);
	$stmt->bindValue(2, $password);
	$stmt->execute();
	$login = $stmt->fetch();

	if($login['login'] === "1"){
		// Getting logged user data
		$sql_user = $app['db']->createQueryBuilder();
		$sql_user
			->select('u.id, CONCAT(u.name,\' \',u.lastname) AS name, u.username, u.privileges')
			->from('user', 'u')
			->where('u.username = ?');
		$stmt = $app['db']->prepare($sql_user);
		$stmt->bindValue(1, $username);
		$stmt->execute();
		$user_data = $stmt->fetch();

		// Setting the session data for all the app
		$app['session']->set('session_data', array('logged_username' => $username, 'logged_user_id' => $user_data['id'], 'logged_user_privileges' => $user_data['privileges'], 'logged_user_name' => $user_data['name']));

		return $app->redirect("/console");
		//return "Logged";
	}else{
		return $app->redirect("/login");
		//return "Login failed";
		//return "Los datos de acceso son incorrectos";
	}
});