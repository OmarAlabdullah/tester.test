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
				
				set('photos', $photos);
				
				$got_photos = array();
				foreach($photos as $photo)
				{
					$got_photos[$photo['Photo']['type']] = $photo;
				}
				set('project_list', $project_list);
				set('got_photos', $got_photos);
				
				$params = array(
					'documents' => array(
						'conditions' => array(
							'archived' => '0000-00-00 00:00:00',
							'client_id' => (int)$client['Client']['id'],
							'type' => 'dgt'
						)
					)
				);
				$dgt_documents = $db->select($params);
				
				set('dgt_documents', $dgt_documents);
				
				$params = array(
					'documents' => array(
						'conditions' => array(
							'archived' => '0000-00-00 00:00:00',
							'(`client_id` = ' . (int)$client['Client']['id'] . ' OR (`client_id` = 0 AND `street` = "' . strtolower($client['Client']['street']) . '"))',
							'type' => 'sketch'
						)
					)
				);
				$sketches = $db->select($params);
				
				set('sketches', $sketches);
				
				$params = array(
					'documents' => array(
						'conditions' => array(
							'archived' => '0000-00-00 00:00:00',
							'(`client_id` = ' . (int)$client['Client']['id'] . ' OR (`client_id` = 0 AND `street` = "' . strtolower($client['Client']['street']) . '"))',
							'type' => 'nestor'
						)
					)
				);
				$nestors = $db->select($params);
				
				set('nestors', $nestors);
			}
		}
	}
}

function add($project_list_id = 0)
{
	$project_list_id = (int)$project_list_id;
	
	if($project_list_id > 0)
	{
		global $db, $controller;
		
		$project_list = $db->first('project_lists', $project_list_id);
		if($project_list)
		{
			set('project_list', $project_list);
			
			if(post())
			{
				$client['Client'] = $controller['post']['Client'];
				
				if(!empty($client['Client']['street']) && !empty($client['Client']['homenumber']) && !empty($client['Client']['zipcode']) && !empty($client['Client']['city']))
				{
					$client['Client']['zipcode'] = strtoupper(str_replace(' ', '', $client['Client']['zipcode']));
					$client['Client']['addition'] = strtolower(str_replace(' ', '', $client['Client']['addition']));
					
					$client['Client']['project_list_id'] = $project_list['Project_list']['id'];
					$client['Client']['created'] = date('Y-m-d H:i:s');
					
					$insert_id = $db->insert($client);
					
					if($insert_id > 0)
					{
						redirect('/clients/details/' . $insert_id);
					}
				}
			}
		}
	}
}

?>