
var openEditCreateUser = function(user_id, action){
	$('#userModal').modal('toggle');
	$('#sp_user_action').html(action);
	
	if(action == 'Crear'){
		$('#frm_user').trigger('reset');
	}
	
	if(user_id != ''){
		$.ajax({
		url: 'http://localhost/gp-filemanager/backend/api/v1/get-user/'+user_id,
		dataType: 'json',
		success: function(response){
			$('#txt_user_id').val(response.id);
			$('#txt_name').val(response.name);
			$('#txt_lastname').val(response.lastname);
			$('#txt_email').val(response.username);
			$('#txt_password').val(response.password);
			$('#slc_status').val(response.status);
			$('#slc_privileges').val(response.privileges);
		},
		error: function(error){
			console.log('Ha ocurrido un error: ' + error);
		}
	});
	}
}

var editCreateUser = function(){
	var user_id = $('#txt_user_id').val();
	var name = $('#txt_name').val();
	var lastname = $('#txt_lastname').val();
	var username = $('#txt_email').val();
	var password = $('#txt_password').val();
	var status = $('#slc_status').val();
	var privileges = $('#slc_privileges').val();
	var params = JSON.stringify({user_id: user_id, name: name, lastname: lastname, username: username, password: password, status: status, privileges: privileges});
	
	$.ajax({
		url: 'http://localhost/gp-filemanager/backend/api/v1/create-modify-user',
		method: 'POST',
		dataType: 'json',
		data: params,
		success: function(response){
			alert(response.message);
			$('#frm_user').trigger('reset');
			location.reload();
		},
		error: function(error){
			console.log('Ha ocurrido un error: ' + error);
		}
	});
}

var deleteUser = function(user_id){
	var confirm_delete = confirm('¿Está seguro de eliminar este usuario?');

	if(confirm_delete == true){
		$.ajax({
			url: 'http://localhost/gp-filemanager/backend/api/v1/delete-user/'+user_id,
			dataType: 'json',
			success: function(response){
				alert(response.message);
				location.reload();
			},
			error: function(error){
				console.log('Ha ocurrido un error: ' + error);
			}
		});	
	}
	
}