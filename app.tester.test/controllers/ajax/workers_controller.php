<?php

function setting($setting = '', $on = 0)
{
	global $db, $userLoggedIn;
	
	$return = array(
		'succes' => false
	);
	
	if($on != 1)
		$on = 0;
	
	$worker['Worker'] = $userLoggedIn['Worker'];
	$worker['Worker'][$setting] = $on;
	$db->update($worker);
	
	$return['Worker'] = $worker['Worker'];
	
	print(json_encode($return));
}
function setting_int($setting = '', $int = 0)
{
	global $db, $userLoggedIn;
	
	$return = array(
		'succes' => false
	);
	
	$int = (int)$int;
	
	$worker['Worker'] = $userLoggedIn['Worker'];
	$worker['Worker'][$setting] = $int;
	$db->update($worker);
	
	$return['Worker'] = $worker['Worker'];
	
	print(json_encode($return));
}

?>