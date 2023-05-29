<?php
	function login()
	{
		global $controller, $db;
		
		$controller['layout'] = 'login';
		$controller['css'] = array();
		$controller['css'][] = 'login.css';
		$controller['js'] = array();
		
		if(post())
		{
			$username = $controller['post']['username'];
			$username = str_replace('\'', '', $username);
			$username = str_replace('"', '', $username);
			
			$password = $controller['post']['password'];
			$password = str_replace('\'', '', $password);
			$password = str_replace('"', '', $password);
			$password = md5($password . SALT);
			
			$params = array(
				'users' => array(
					'conditions' => array(
						'username' => $username
					),
					'select' => 'first'
				)
			);
			$user = $db->select($params);
			
			$user_log = array(
				'User_log' => array(
					'user_id' => 0,
					'timestamp' => date('Y-m-d H:i:s'),
					'action' => 'login-wrong-username',
					'ip' => $_SERVER['REMOTE_ADDR']
				)
			);
			
			if($user && !empty($password))
			{
				$user_log['User_log']['user_id'] = $user['User']['id'];
				
				if($password == $user['User']['password'])
				{
					cookie('user', $user['User']['id']);
					cookie('hash', md5($password . SALT));
					
					$user_log['User_log']['action'] = 'login-succes';
					$db->insert($user_log);
					
					if($controller['params'][0] == 'users' && $controller['params'][1] == 'login')
						redirect('/');
					else
						redirect(SELF);
				}else
					$db->insert($user_log);
			}else
				$db->insert($user_log);
		}
	}
	
	function logout()
	{
		global $db, $userLoggedIn;
		
		$user_log = array(
			'User_log' => array(
				'user_id' => $userLoggedIn['User']['id'],
				'timestamp' => date('Y-m-d H:i:s'),
				'action' => 'logout',
				'ip' => $_SERVER['REMOTE_ADDR']
			)
		);
		$db->insert($user_log);
		
		cookie('user', 0);
		cookie('hash', '');
		
		destroy_cookie('user');
		destroy_cookie('hash');
		
		redirect('/');
	}
	
	function overview()
	{
		global $db;
		
		$params = array(
			'users' => array(
				'conditions' => array(
					'archived' => '0000-00-00 00:00:00'
				)
			)
		);
		$users = $db->select($params);
		
		set('users', $users);
	}
	
	function details($user_id = 0)
	{
		$user_id = (int)$user_id;
		$user = false;
		
		if($user_id > 0 && permissions('master', 'admin'))
		{
			global $db, $controller;
			
			$user = $db->first('users', $user_id);
			
			set('user', $user);
			
			$params = array(
				'user_logs' => array(
					'conditions' => array(
						'user_id' => $user['User']['id']
					),
					'order' => 'timestamp DESC',
					'limit' => 20
				)
			);
			$user_logs = $db->select($params);
			
			set('user_logs', $user_logs);
			
			if(post())
			{
				$user['User']['preferences'] = array_to_string($controller['post']['preferences']);
				$db->update($user);
				redirect(SELF);
			}
		}
		if(!$user)
			redirect('/users/overview');
	}
	
	function add()
	{
		global $controller, $db;
		
		$controller['view'] = 'users/crud';
		
		$errors = array();
		
		if(post())
		{
			$user = $controller['post'];
			
			if(strlen($user['User']['password']) < 2)
				$errors['password'] = 'Vul een geldig wachtwoord in';
			
			$user['User']['password'] = md5($user['User']['password'] . SALT);
			$user['User']['created'] = date('Y-m-d H:i:s');
			
			if(strlen($user['User']['username']) < 2)
				$errors['username'] = 'Vul een geldige gebruikersnaam in';
			
			if(count($errors) == 0)
			{
				$params = array(
					'users' => array(
						'conditions' => array(
							'username' => $user['User']['username']
						),
						'select' => 'first'
					)
				);
				$db_user = $db->select($params);
				
				if($db_user)
					$errors['username'] = 'Deze gebruikersnaam bestaat al';
				
				if(count($errors) == 0)
				{
					$db->insert($user);
					redirect('/users/overview');
				}
			}
		}
		
		set('errors', $errors);
	}
	
?>