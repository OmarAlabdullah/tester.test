<?php

function update_status()
{
	$return = array(
		'succes' => false
	);
	
	global $controller, $db, $userLoggedIn;
	
	$return['post'] = $controller['post'];
	
	$notification_id = (int)$controller['post']['notification_id'];
	
	if($notification_id > 0)
	{
		$notification = $db->first('notifications', $notification_id);
		if($notification)
		{
			$notification['Notification']['status'] = $controller['post']['status'];
			$notification['Notification']['remarks'] = $controller['post']['remarks'];
			$notification['Notification']['updated_by'] = (int)$userLoggedIn['Worker']['id'];
			$notification['Notification']['updated'] = date('Y-m-d H:i:s');
			
			$db->update($notification);
			
			$return['notification'] = $notification;
			$return['succes'] = true;
		}
	}
	
	print(json_encode($return));
}

?>