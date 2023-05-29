<?php
	
	function remove()
	{
		global $controller, $db;
		
		$return = array(
			'succes' => false,
			'error' => false
		);
		
		$mail_template_ids = $controller['post']['mail_template_ids'];
		
		foreach($mail_template_ids as $mail_template_id)
		{
			$mail_template = $db->first('mail_templates', $mail_template_id);
			if($mail_template)
			{
				$return['succes'] = true;
				
				$return['ids'][] = $mail_template['Mail_template']['id'];
				
				$mail_template['Mail_template']['archived'] = date('Y-m-d H:i:s');
				$db->connect();
				$db->update($mail_template);
				$return['debug'] = mysqli_error($db->handle);
			}
		}
		
		print(json_encode($return));
	}
	
	function duplicate()
	{
		global $controller, $db, $userLoggedIn;
		
		$return = array(
			'succes' => false,
			'error' => false
		);
		
		$mail_template_ids = $controller['post']['mail_template_ids'];
		
		foreach($mail_template_ids as $mail_template_id)
		{
			$mail_template = $db->first('mail_templates', $mail_template_id);
			if($mail_template)
			{
				$return['succes'] = true;
				
				unset($mail_template['Mail_template']['id']);
				$mail_template['Mail_template']['user_id'] = $userLoggedIn['User']['id'];
				$mail_template['Mail_template']['name'] .= ' (kopie)';
				$mail_template['Mail_template']['created'] = date('Y-m-d H:i:s');
				$mail_template['Mail_template']['default'] = 0;
				$mail_template['Mail_template']['watch_default'] = 0;
				
				$db->insert($mail_template);
			}
		}
		
		print(json_encode($return));
	}
	
?>