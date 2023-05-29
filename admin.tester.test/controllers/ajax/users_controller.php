<?php
	function remove()
	{
		global $controller, $db;
		
		$return = array(
			'succes' => false,
			'error' => false
		);
		
		$user_ids = $controller['post']['user_ids'];
		
		foreach($user_ids as $user_id)
		{
			$user = $db->first('users', $user_id);
			if($user)
			{
				$return['succes'] = true;
				
				$user['User']['archived'] = date('Y-m-d H:i:s');
				$db->update($user);
			}
		}
		
		print(json_encode($return));
	}
	
	function change_password()
	{
		$return = array(
			'succes' => false
		);
		
		global $controller, $db, $userLoggedIn;
		
		//$return['post'] = $controller['post'];
		
		$user_id = (int)$controller['post']['user_id'];
		
		if($user_id > 0)
		{
			if(permissions('master') || $user_id == $userLoggedIn['User']['id'])
			{
				$user = $db->first('users', $user_id);
				if($user)
				{
					$user['User']['password'] = md5($controller['post']['new_password'] . SALT);;
					
					$db->update($user);
					
					if($user['User']['id'] == $userLoggedIn['User']['id'])
					{
						cookie('hash', md5($user['User']['password'] . SALT));
					}
					
					$return['user'] = $user;
					$return['succes'] = true;
				}
			}
		}
		
		print(json_encode($return));
	}
	
?>