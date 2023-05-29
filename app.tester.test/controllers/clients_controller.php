<?php

function details($client_id = 0)
{
	$client_id = (int)$client_id;
	
	if($client_id > 0)
	{
		global $db;
		
		$client = $db->first('clients', $client_id);
		
		if($client)
		{
			set('client', $client);
			
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
			set('photos', $photos);
		}
	}
}

function photo_old($client_id = 0) //2021-05-16: doet niet meer mee, vervangen voor andere volgorde
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
				set('client', $client);
				set('project_list', $project_list);
				
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
				set('photos', $photos);
				
				$got_photos = array();
				foreach($photos as $photo)
				{
					$got_photos[$photo['Photo']['type']]++;
				}
				set('got_photos', $got_photos);
			}
		}
	}
}
function photo($client_id = 0)
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
				set('client', $client);
				set('project_list', $project_list);
				
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
				set('photos', $photos);
				
				$got_photos = array();
				foreach($photos as $photo)
				{
					$got_photos[$photo['Photo']['type']]++;
				}
				set('got_photos', $got_photos);
			}
		}
	}
}

function costs($client_id = 0)
{
	$client_id = (int)$client_id;
	
	if($client_id > 0)
	{
		global $db;
		
		$client = $db->first('clients', $client_id);
		
		if($client)
		{
			set('client', $client);
		}
	}
}

function documents($client_id = 0)
{
	$client_id = (int)$client_id;
	
	if($client_id > 0)
	{
		global $db;
		
		$client = $db->first('clients', $client_id);
		
		if($client)
		{
			set('client', $client);
			
			$params = array(
				'documents' => array(
					'conditions' => array(
						'client_id' => $client['Client']['id'],
						'archived' => '0000-00-00 00:00:00'
					)
				)
			);
			$documents = $db->select($params);
			
			set('documents', $documents);
			
			
			$params = array(
				'documents' => array(
					'conditions' => array(
						'client_id' => 0,
						'project_list_id' => $client['Client']['project_list_id'],
						'street' => strtolower($client['Client']['street']),
						'archived' => '0000-00-00 00:00:00'
					)
				)
			);
			$street_documents = $db->select($params);
			
			set('street_documents', $street_documents);
			
			
			$params = array(
				'documents' => array(
					'conditions' => array(
						'client_id' => 0,
						'street' => '',
						'project_list_id' => $client['Client']['project_list_id'],
						'archived' => '0000-00-00 00:00:00'
					)
				)
			);
			$project_documents = $db->select($params);
			
			set('project_documents', $project_documents);
		}
	}
}

function notification($notification_id = 0)
{
	$notification_id = (int)$notification_id;
	if($notification_id > 0)
	{
		global $db;
		
		$notification = $db->first('notifications', $notification_id);
		if($notification)
		{
			set('notification', $notification);
		}
	}
}

?>