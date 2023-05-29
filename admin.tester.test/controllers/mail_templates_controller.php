<?php
function overview()
{
	global $db;
	
	$params = array(
		'mail_templates' => array(
			'conditions' => array(
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'name ASC'
		)
	);
	$mail_templates = $db->select($params);
	
	set('mail_templates', $mail_templates);
}

function details($mail_template_id = 0)
{
	global $db, $controller;
	
	if(!($mail_template_id > 0))
		redirect('/mail_templates/overview');
	
	$params = array(
		'mail_templates' => array(
			'conditions' => array(
				'archived = "0000-00-00 00:00:00"',
				'id' => $mail_template_id
			),
			'select' => 'first'
		)
	);
	$mail_template = $db->select($params);
	
	if(!$mail_template)
		redirect('/mail_templates/overview');
	
	if(post())
	{
		$mail_template['Mail_template']['name'] = str_replace('\"', '', str_replace('\'', '', $controller['post']['name']));
		$mail_template['Mail_template']['subject'] = str_replace('\"', '', str_replace('\'', '', $controller['post']['subject']));
		$mail_template['Mail_template']['content'] = textarea_to_db($controller['post']['content']);
		$mail_template['Mail_template']['type'] = $controller['post']['type'];
		$mail_template['Mail_template']['default'] = ($controller['post']['default'] == 'on' ? 1 : 0);
		$db->update($mail_template);
		
		if($controller['post']['default'] == 'on')
			$db->query("UPDATE `mail_templates` SET `default` = 0 WHERE `type` = '" . $mail_template['Mail_template']['type'] . "' AND `id` != " . $mail_template['Mail_template']['id']);
		
		redirect('/mail_templates/overview');
	}
	
	set('mail_template', $mail_template);
}

function add()
{
	global $db, $controller, $userLoggedIn;
	
	$controller['view'] = 'mail_templates/details';
	
	if(post())
	{
		//pr($controller['post']);
		//exit();
		$mail_template = array(
			'Mail_template' => array()
		);
		$mail_template['Mail_template']['user_id'] = $userLoggedIn['User']['id'];
		$mail_template['Mail_template']['name'] = str_replace('\"', '', str_replace('\'', '', $controller['post']['name']));
		$mail_template['Mail_template']['subject'] = str_replace('\"', '', str_replace('\'', '', $controller['post']['subject']));
		$mail_template['Mail_template']['content'] = textarea_to_db($controller['post']['content']);
		$mail_template['Mail_template']['created'] = date('Y-m-d H:i:s');
		$mail_template['Mail_template']['type'] = $controller['post']['type'];
		$mail_template['Mail_template']['default'] = ($controller['post']['default'] == 'on' ? 1 : 0);
		
		$insert_id = $db->insert($mail_template);
		
		if($insert_id > 0)
		{
			if($controller['post']['default'] == 'on')
				$db->query("UPDATE `mail_templates` SET `default` = 0 WHERE `type` = '" . $mail_template['Mail_template']['type'] . "' AND `id` != " . $insert_id);
			redirect('/mail_templates/overview');
		}
	}
}

function textarea_to_db($txt)
{
	return $txt;
}
function db_to_textarea($txt)
{
	return $txt;
}

?>