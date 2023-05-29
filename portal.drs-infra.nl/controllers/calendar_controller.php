<?php

function overview()
{
	global $db, $controller;
	
	$year = (int)$controller['get']['year'];
	if($year > 0)
	{
		set('year', $year);
	}
	
	$week_number = (int)$controller['get']['week_number'];
	if($week_number > 0)
	{
		set('week_number', $week_number);
	}
	
	$client_id = (int)$controller['get']['client_id'];
	if($client_id > 0)
	{
		set('client_id', $client_id);
	}
}

?>