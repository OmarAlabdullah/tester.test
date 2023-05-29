<?php
	
	$controller['developer'] = 2;
	$controller['layout'] = 'default';
	$controller['css'][] = 'default.css';
	$controller['css'][] = 'pagination.css';
	$controller['js'][] = 'jquery-3.4.1.min.js';
	$controller['js'][] = 'jquery-ui.min.js';
	$controller['js'][] = 'scripts.js';
	$controller['js'][] = 'pagination.js';
	$controller['js'][] = 'tables.js';
	$controller['js'][] = 'checkboxes.js';

	$title_for_layout = 'DRS Infra';
	$keywords_for_layout = 'infra';
	$description_for_layout = 'DRS Infra beschrijving';
	
	define('SALT', 'FIA');
	
	$db = runClass('dbi');
	$db->name = 'drs';
	$db->user = 'drs';
	$db->password = 'u#4pgD72';
	
	$controller['routes'] = array(
		'/' => 'calendar/overview'
	);
	
	$controller['restricted'] = array(
		'/' => 'users/login'
	);
	
	$controller['salt'] = 'hdf_73jn';
	
	function userLoggedIn()
	{
		global $db;
		
		$user_id = cookie('user');
		$hash = cookie('hash');
		
		if($user_id > 0)
		{
			$user = $db->first('users', $user_id);
			
			if($user && !empty($hash))
			{
				if($user['User']['archived'] == '0000-00-00 00:00:00')
				{
					if($hash == md5($user['User']['password'] . SALT))
					{
						$prefs = string_to_array($user['User']['preferences']);
						
						$user['User']['last_online'] = date('Y-m-d H:i:s');
						$db->update($user);
						
						return array(
							'User' => $user['User'],
							'preferences' => $prefs
						);
					}
				}
			}
		}
		
		return false;
	}
	$userLoggedIn = userLoggedIn();
	
	function string_to_array($string)
	{
		$return = array();
		$arr = explode(';', $string);
		foreach($arr as $ar)
		{
			$expl = explode('=', $ar);
			if(!empty($expl[0]) && !empty($expl[1]))
				$return[$expl[0]] = $expl[1];
		}
		return $return;
	}
	function array_to_string($arr)
	{
		$return = '';
		foreach($arr as $ar => $value)
		{
			$return .= $ar . '=' . $value . ';';
		}
		$return = substr($return, 0, -1);
		
		return $return;
	}
?>
