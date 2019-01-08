<?php

use Symfony\Component\HttpFoundation\Request;

// Rendering the template
$app->get('/users', function() use ($app){
	$sql = $app['db']->createQueryBuilder();
	$sql
		->select('u.id, u.name, u.lastname, u.username, u.status, up.privilege')
		->from('user', 'u')
		->leftJoin('u', 'user_privileges', 'up', 'up.id = u.privileges')
		->orderBy('u.id', 'ASC');
	$stmt = $app['db']->prepare($sql);
	$stmt->execute();
	$users = $stmt->fetchAll();

	$session_data = $app['session']->get('session_data');
	
	return $app['twig']->render(
		'users.html.twig', 
		array('session_data'=>$session_data, 'users' => $users)
	);
});


// This controller create or modify a user
$app->post('/api/v1/create-modify-user', function(Request $request) use ($app){
	$data = json_decode($request->getContent(), true);
	$request->request->replace(is_array($data) ? $data : array());

	$session_data = $app['session']->get('session_data');
	$user_id = $request->request->get('user_id');
	$name = $request->request->get('name');
	$lastname = $request->request->get('lastname');
	$username = $request->request->get('username');
	$password = sha1($request->request->get('password'));
	$status = $request->request->get('status');
	$privileges = $request->request->get('privileges');
	$date = date('Y-m-d H:i:s');
	$logged_user = $session_data['logged_user_id'];

	if($user_id == ""){
		$sql_insert = "INSERT INTO user (name, lastname, username, password, status, privileges, creation_date, creation_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $app['db']->prepare($sql_insert);
		$stmt->bindValue(1, $name);
		$stmt->bindValue(2, $lastname);
		$stmt->bindValue(3, $username);
		$stmt->bindValue(4, $password);
		$stmt->bindValue(5, $status);
		$stmt->bindValue(6, $privileges);
		$stmt->bindValue(7, $date);
		$stmt->bindValue(8, $logged_user);
		$insert = $stmt->execute();

		if($insert == true){
			$response = array('message' => 'El usuario ha sido creado exitosamente.');
		}else{
			$response = array('message' => 'Ha ocurrido un error en la creación del usuario, intente nuevamente o comuníquese con el administrador del sistema.');
		}
	}else{
		$sql_update = "UPDATE user SET name = ?, lastname = ?, username = ?, password = ?, status = ?, privileges = ?, modification_date = ?, modification_user = ? WHERE id = ?";
		$stmt = $app['db']->prepare($sql_update);
		$stmt->bindValue(1, $name);
		$stmt->bindValue(2, $lastname);
		$stmt->bindValue(3, $username);
		$stmt->bindValue(4, $password);
		$stmt->bindValue(5, $status);
		$stmt->bindValue(6, $privileges);
		$stmt->bindValue(7, $date);
		$stmt->bindValue(8, $logged_user);
		$stmt->bindValue(9, $user_id);
		$update = $stmt->execute();

		if($update == true){
			$response = array('message' => 'El usuario ha sido editado exitosamente.');
		}else{
			$response = array('message' => 'Ha ocurrido un error en la edición del usuario, intente nuevamente o comuníquese con el administrador del sistema.');
		}
	}

	return $app->json($response);
});


// This controller gets the information of a specific user
$app->get('/api/v1/get-user/{user_id}', function($user_id) use ($app){
	$sql = $app['db']->createQueryBuilder();
	$sql
		->select('u.id, u.name, u.lastname, u.username, u.password, u.status, u.privileges')
		->from('user', 'u')
		->where('u.id = ?');
	$stmt = $app['db']->prepare($sql);
	$stmt->bindValue(1, $user_id);
	$stmt->execute();
	$user = $stmt->fetch();

	return $app->json($user);
});


// This controller deletes a specific user
$app->get('/api/v1/delete-user/{user_id}', function($user_id) use ($app){
	$sql = "DELETE FROM user WHERE id = ?";
	$stmt = $app['db']->prepare($sql);
	$stmt->bindValue(1, $user_id);
	$delete = $stmt->execute();

	if($delete == true){
		$response = array('message' => 'El usuario ha sido eliminado exitosamente.');
	}else{
		$response = array('message' => 'Ha ocurrido un error, intente nuevamente o comuníquese con el administrador del sistema.');
	}

	return $app->json($response);
});
