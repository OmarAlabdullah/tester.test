<?php
	session_id();
	session_start();
	define('APPLICATION_FOLDER', 'lib/');
	
	if(!@include_once(APPLICATION_FOLDER . 'functions.php'))
		die('Error 1.1: default functions not found!');
	
	$stift_set_values = array();
	
	$controller = array();
	$controller['sublayout'] = null;
	$controller['config'] = array();
	$controller['params'] = array();
	if(isset($_GET['stift_query_string']))
		$controller['params'] = explode('/', $_GET['stift_query_string']);
	$last_param = end($controller['params']);
	if(empty($last_param))
		unset($controller['params'][count($controller['params'])-1]);
	unset($last_param);
	if(!empty($_GET['stift_get']))
	{
		$controller['get'] = array();
		$bla = explode('=', $_GET['stift_get']);
		if(!empty($bla[1]))
			$controller['get'][$bla[0]] = $bla[1];
	}
	unset($_GET['stift_get']);
	unset($_GET['stift_query_string']);
	foreach($_GET as $key => $value)
	{
		$controller['get'][$key] = $value;
		unset($_GET[$key]);
	}
	unset($bla);
	foreach($_POST as $key => $value)
	{
		$controller['post'][$key] = $value;
		unset($_POST[$key]);
	}
		
	foreach($_SESSION as $key => $value)
	{
		$controller['session'][$key] = $value;
	}
	
	foreach($_COOKIE as $key => $value)
	{
		$controller['cookie'][$key] = $value;
		unset($_COOKIE[$key]);
	}
	unset($key);
	unset($value);
	
	if(!@include_once(APPLICATION_FOLDER . 'controller.php'))
		error('Error 1.3: controller not found');
	
?>
