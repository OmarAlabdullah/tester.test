<?php

function remove()
{
	global $controller, $db;
	
	$return = array(
		'succes' => false,
		'error' => false
	);
	
	$worker_ids = $controller['post']['worker_ids'];
	
	foreach($worker_ids as $worker_id)
	{
		$worker = $db->first('workers', $worker_id);
		if($worker)
		{
			$return['succes'] = true;
			
			$worker['Worker']['archived'] = date('Y-m-d H:i:s');
			$db->update($worker);
		}
	}
	
	print(json_encode($return));
}

function replicate()
{
	global $controller, $db;
	
	$return = array(
		'succes' => false,
		'error' => false
	);
	
	$worker_ids = $controller['post']['worker_ids'];
	
	foreach($worker_ids as $worker_id)
	{
		$worker = $db->first('workers', $worker_id);
		if($worker)
		{
			unset($worker['Worker']['id']);
			$worker['Worker']['name'] .= ' (kopie)';
			$worker['Worker']['created'] = date('Y-m-d H:i:s');
			$worker['Worker']['email'] = '';
			$worker['Worker']['password'] = '';
			$worker['Worker']['last_online'] = '0000-00-00 00:00:00';
			$worker['Worker']['app_install_date'] = '0000-00-00';
			
			$db->connect();
			$insert_id = $db->insert($worker);
			
			if($insert_id > 0)
				$return['succes'] = true;
		}
	}
	
	print(json_encode($return));
}

function remove_crews()
{
	global $controller, $db;
	
	$return = array(
		'succes' => false,
		'error' => false
	);
	
	$crew_ids = $controller['post']['crew_ids'];
	
	foreach($crew_ids as $crew_id)
	{
		$crew = $db->first('crews', $crew_id);
		if($crew)
		{
			$return['succes'] = true;
			
			$crew['Crew']['archived'] = date('Y-m-d H:i:s');
			$db->update($crew);
		}
	}
	
	print(json_encode($return));
}

function replicate_crews()
{
	global $controller, $db;
	
	$return = array(
		'succes' => false,
		'error' => false
	);
	
	$crew_ids = $controller['post']['crew_ids'];
	
	foreach($crew_ids as $crew_id)
	{
		$crew = $db->first('crews', $crew_id);
		if($crew)
		{
			unset($crew['Crew']['id']);
			$crew['Crew']['name'] .= ' (kopie)';
			$crew['Crew']['created'] = date('Y-m-d H:i:s');
			
			$db->connect();
			$insert_id = $db->insert($crew);
			
			if($insert_id > 0)
				$return['succes'] = true;
		}
	}
	
	print(json_encode($return));
}

?>