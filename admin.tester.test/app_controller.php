<?php
	
	$controller['developer'] = 1;
	$controller['revision'] = 1374;
	$controller['layout'] = 'default';
	$controller['css'][] = 'default.css';
	$controller['css'][] = 'pagination.css';
	//$controller['css'][] = 'jquery-datepicker.scss';
	$controller['js'][] = 'jquery-3.4.1.min.js';
	$controller['js'][] = 'jquery-ui.min.js';
	$controller['js'][] = 'scripts.js';
	$controller['js'][] = 'pagination.js';
	$controller['js'][] = 'tables.js';
	$controller['js'][] = 'checkboxes.js';
	//$controller['js'][] = 'jquery-datepicker.js';
	
	$title_for_layout = 'DRS Infra';
	$keywords_for_layout = 'infra';
	$description_for_layout = 'DRS Infra beschrijving';
	
	define('SALT', 'FIA');
	
	$db = runClass('dbi');
	$db->name = 'drs';
	$db->user = 'drs';
	$db->password = 'u#4pgD72';
	
	$controller['routes'] = array(
		'/' => 'dashboard'
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
					if($user['User']['permissions'] == 'admin' || $user['User']['permissions'] == 'master')
					{
						if($hash == md5($user['User']['password'] . SALT))
						{
							/*$prefs = array();
							$preferences = explode(';', $user['User']['preferences']);
							foreach($preferences as $preference)
							{
								$expl = explode('=', $preference);
								if(!empty($expl[0]) && !empty($expl[1]))
									$prefs[$expl[0]] = $expl[1];
							}*/
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
	
	function check_and_set_client_finished($client_id = 0)
	{
		$client_id = (int)$client_id;
	
		if($client_id > 0)
		{
			global $db;
			
			$client = $db->first('clients', $client_id);
			
			if($client)
			{
				$project_list = $db->first('project_lists', $client['Client']['project_list_id']);
				
				if($project_list)
				{
					$params = array(
						'photos' => array(
							'conditions' => array(
								'client_id' => $client['Client']['id'],
								'archived' => '0000-00-00 00:00:00'
							),
							'order' => 'created ASC'
						)
					);
					$photos = $db->select($params);
					
					$got_photos = array();
					foreach($photos as $photo)
					{
						$got_photos[$photo['Photo']['type']]++;
					}
					
					$has_required_photos = 0;
					
					$required_photos = explode('|', $project_list['Project_list']['required_photos']);
					foreach($required_photos as $required_photo)
					{
						if($got_photos[strtolower($required_photo)] > 0)
							$has_required_photos++;
					}
					
					$client['Client']['finished'] = 0;
					if($has_required_photos >= count($required_photos))
					{
						//has all photos
						$client['Client']['finished'] = 1;
					}
					$db->update($client);
					return ($client['Client']['finished'] == 1);
				}
			}
		}
	}
?>
