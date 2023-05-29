<?php

function documents($project_list_id = 0)
{
	$project_list_id = (int)$project_list_id;
	
	if($project_list_id > 0)
	{
		global $db;
		
		$project_list = $db->first('project_lists', $project_list_id);
		
		if($project_list)
		{
			set('project_list', $project_list);
			
			$params = array(
				'documents' => array(
					'conditions' => array(
						'project_list_id' => $project_list['Project_list']['id'],
						'archived' => '0000-00-00 00:00:00'
					)
				)
			);
			$documents = $db->select($params);
			
			set('documents', $documents);
		}
	}
}

function overview()
{
	global $db;
	
	$params = array(
		'project_lists' => array(
			'conditions' => array(
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'created DESC'
		)
	);
	$project_lists = $db->select($params);
	
	if($project_lists)
	{
		foreach($project_lists as $index => $project_list)
		{
			$params = array(
				'documents' => array(
					'conditions' => array(
						'project_list_id' => $project_list['Project_list']['id'],
						'archived' => '0000-00-00 00:00:00'
					)
				)
			);
			$documents = $db->select($params);
			
			$project_lists[$index]['documents'] = $documents;
		}
		
		set('project_lists', $project_lists);
	}
}

?>