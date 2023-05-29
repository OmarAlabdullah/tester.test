<?php

function login()
{
	global $controller, $db;
	$controller['layout'] = 'login';
	$controller['css'] = array();
	$controller['css'][] = 'login.css';
	$controller['js'] = array();
	$controller['js'][] = 'jquery-3.4.1.min.js';
	
	if(post())
	{
		$username = strtolower($controller['post']['username']);
		$password = md5($controller['post']['password'] . $controller['salt']);
		
		cookie('username', $username);
		cookie('password', $password);
		
		redirect('/');
	}
}
function logout()
{
	cookie('username', '');
	cookie('password', '');
	
	destroy_cookie('username');
	destroy_cookie('password');
	
	redirect('/');
}

?>