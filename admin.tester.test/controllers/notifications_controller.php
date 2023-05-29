<?php

function overview()
{
	global $db;
	
	$params = array(
		'notifications' => array(
			'conditions' => array(
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => '`date` DESC'
		)
	);
	$notifications = $db->select($params);
	
	set('notifications', $notifications);
}

function edit_notification(int $notification_id)
{
	if($notification_id > 0)
	{
		global $db, $controller;
		
		$notification = $db->first('notifications', $notification_id);
		
		set('notification', $notification);
		
		if($notification)
		{
			if(post())
			{
				$post_notification['Notification'] = $controller['post']['Notification'];
				
				$notification['Notification']['title'] = $post_notification['Notification']['title'];
				$notification['Notification']['content'] = $post_notification['Notification']['content'];
				$notification['Notification']['status'] = $post_notification['Notification']['status'];
				$notification['Notification']['remarks'] = $post_notification['Notification']['remarks'];
				
				$db->update($notification);
				redirect('/calendar/overview?year=' . (date('Y', strtotime($notification['Notification']['date']))) . '&week_number=' . (date('W', strtotime($notification['Notification']['date']))));
			}
		}
	}
}

?>