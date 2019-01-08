<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

// Rendering the template
$app->get('/filemanager', function() use ($app){
	$sql = $app['db']->createQueryBuilder();
	$sql
		->select('f.id, f.version, f.description, f.path, f.creation_date')
		->from('files', 'f')
		->orderBy('f.creation_date', 'DESC');
	$stmt = $app['db']->prepare($sql);
	$stmt->execute();
	$files = $stmt->fetchAll();

	$session_data = $app['session']->get('session_data');
	
	return $app['twig']->render(
		'filemanager.html.twig', 
		array('session_data'=>$session_data, 'files' => $files)
	);
});


// This controller create or modify a file
$app->post('/filemanager/create-modify-file', function(Request $request) use ($app){
	//$data = json_decode($request->getContent(), true);
	//$request->request->replace(is_array($data) ? $data : array());

	$session_data = $app['session']->get('session_data');
	//$file_id = $request->request->get('file_id');
	$file_id = $request->get('txt_file_id');
	//$version = $request->request->get('version');
	$version = $request->get('txt_version');
	//$description = $request->request->get('description');
	$description = $request->get('txa_description');
	//$path = $request->request->get('path');
	$date = date('Y-m-d H:i:s');
	$logged_user = $session_data['logged_user_id'];

	$file = $request->files->get('file');
	
	$file_name = $file->getClientOriginalName();

	if($file !== null){
        $path = 'files/';
        $copy = $file->move($path, $file->getClientOriginalName());

        $response_file = array(
			"result_code" => 1,
			"message" => "Se ha subido el archivo exitosamente. Archivo: " . $file->getClientOriginalName(),
			"file_size" => "Size: " . filesize($path . '/' . $file->getClientOriginalName()) / 1024 . " kb"
		);
    }

	if($file_id == ""){
		$sql_insert = "INSERT INTO files (version, description, path, creation_date, creation_user) VALUES (?, ?, ?, ?, ?)";
		$stmt = $app['db']->prepare($sql_insert);
		$stmt->bindValue(1, $version);
		$stmt->bindValue(2, $description);
		$stmt->bindValue(3, $file_name);
		$stmt->bindValue(4, $date);
		$stmt->bindValue(5, $logged_user);
		$insert = $stmt->execute();

		if($insert == true){
			//$response = array('message' => 'El archivo ha sido cargado exitosamente.');
			$message = 'El archivo ha sido cargado exitosamente.';
		}else{
			//$response = array('message' => 'Ha ocurrido un error, intente nuevamente o comuníquese con el administrador del sistema.');
			$message = 'Ha ocurrido un error, intente nuevamente o comuníquese con el administrador del sistema.';
		}
	}else{
		$sql_update = "UPDATE files SET version = ?, description = ?, path = ?, modification_date = ?, modification_user = ? WHERE id = ?";
		$stmt = $app['db']->prepare($sql_update);
		$stmt->bindValue(1, $version);
		$stmt->bindValue(2, $description);
		$stmt->bindValue(3, $path);
		$stmt->bindValue(4, $date);
		$stmt->bindValue(5, $logged_user);
		$stmt->bindValue(6, $file_id);
		$update = $stmt->execute();

		if($update == true){
			$response = array('message' => 'El archivo ha sido editado exitosamente.');
		}else{
			$response = array('message' => 'Ha ocurrido un error, intente nuevamente o comuníquese con el administrador del sistema.');
		}
	}

	//return $app->json($response);
	return $app->redirect("/filemanager");
});


// This controller gets the information of a specific file
$app->get('/filemanager/get-file/{file_id}', function($file_id) use ($app){
	$sql = $app['db']->createQueryBuilder();
	$sql
		->select('f.id, f.version, f.description, f.path, f.creation_date')
		->from('files', 'f')
		->where('f.id = ?');
	$stmt = $app['db']->prepare($sql);
	$stmt->bindValue(1, $file_id);
	$stmt->execute();
	$file = $stmt->fetch();

	return $app->json($file);
});


// This controller deletes a specific file
$app->get('/filemanager/delete-file/{file_id}', function($file_id) use ($app){
	$sql = "DELETE FROM files WHERE id = ?";
	$stmt = $app['db']->prepare($sql);
	$stmt->bindValue(1, $file_id);
	$delete = $stmt->execute();

	if($delete == true){
		$response = array('message' => 'El archivo ha sido eliminado exitosamente.');
	}else{
		$response = array('message' => 'Ha ocurrido un error, intente nuevamente o comuníquese con el administrador del sistema.');
	}

	return $app->json($response);
});


$app->get('/files/{filename}', function($filename) use ($app){
	$path = "files/".$filename;

	/*return $app
    	->sendFile($path)
    	->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,
			$path
		);*/

	return new BinaryFileResponse($path);
});
