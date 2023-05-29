<?php

function overview()
{
	global $db;
	
	$params = array(
		'workers' => array(
			'conditions' => array(
				'archived' => '0000-00-00 00:00:00'
			)
		)
	);
	$workers = $db->select($params);
	
	foreach($workers as $index => $worker)
	{
		$workers[$index]['Crew'] = $db->first('crews', $worker['Worker']['crew_id'])['Crew'];
	}
	
	set('workers', $workers);
}
function add()
{
	global $db, $controller;
	
	$controller['view'] = 'workers/crud';
	
	$params = array(
		'crews' => array(
			'conditions' => array(
				'archived' => '0000-00-00 00:00:00'
			)
		)
	);
	$crews = $db->select($params);
	
	set('crews', $crews);
	
	if(post())
	{
		$worker['Worker'] = $controller['post']['Worker'];
		
		if(!empty($worker['Worker']['name']))
		{
			$worker['Worker']['crew_id'] = (int)$worker['Worker']['crew_id'];
			$worker['Worker']['language_id'] = (int)$worker['Worker']['language_id'];
			$worker['Worker']['wage'] = (float)str_replace(',', '.', str_replace('.', '', $worker['Worker']['wage']));
			$worker['Worker']['created'] = date('Y-m-d H:i:s');
			
			if(!empty($worker['Worker']['password']))
				$worker['Worker']['password'] = md5($worker['Worker']['password'] . $controller['salt']);
			
			$db->connect();
			$db->insert($worker);
		}
		
		redirect('/workers/overview');
	}
}
function edit($worker_id = 0)
{
	global $db, $controller;
	
	$worker_id = (int)$worker_id;
	
	$controller['view'] = 'workers/crud';
	
	if($worker_id > 0)
	{
		$worker = $db->first('workers', $worker_id);
		set('worker', $worker);
		
		$params = array(
			'crews' => array(
				'conditions' => array(
					'archived' => '0000-00-00 00:00:00'
				)
			)
		);
		$crews = $db->select($params);
		
		set('crews', $crews);
		
		if(post())
		{
			$worker['Worker'] = $controller['post']['Worker'];
			
			if($worker['Worker']['id'] > 0 && !empty($worker['Worker']['name']))
			{
				$worker['Worker']['crew_id'] = (int)$worker['Worker']['crew_id'];
				$worker['Worker']['language_id'] = (int)$worker['Worker']['language_id'];
				$worker['Worker']['wage'] = (float)str_replace(',', '.', $worker['Worker']['wage']);
				
				if(empty($worker['Worker']['password']))
					unset($worker['Worker']['password']);
				else
					$worker['Worker']['password'] = md5($worker['Worker']['password'] . $controller['salt']);
				
				$db->connect();
				$db->update($worker);
			}
			
			redirect('/workers/overview');
		}
	}else
		redirect('/workers/overview');
}

function crews()
{
	global $db;
	
	$params = array(
		'crews' => array(
			'conditions' => array(
				'archived' => '0000-00-00 00:00:00'
			)
		)
	);
	$crews = $db->select($params);
	
	set('crews', $crews);
}
function add_crew()
{
	global $db, $controller;
	
	$controller['view'] = 'workers/crud_crew';
	
	if(post())
	{
		$crew['Crew'] = $controller['post']['Crew'];
		
		if(!empty($crew['Crew']['name']))
		{
			$crew['Crew']['created'] = date('Y-m-d H:i:s');
			$db->connect();
			$db->insert($crew);
		}
		
		redirect('/workers/crews');
	}
}
function edit_crew($crew_id = 0)
{
	global $db, $controller;
	
	$crew_id = (int)$crew_id;
	
	if($crew_id > 0)
	{
		$crew = $db->first('crews', $crew_id);
		$controller['view'] = 'workers/crud_crew';
		set('crew', $crew);
		
		if(post())
		{
			$crew['Crew'] = $controller['post']['Crew'];
			
			if($crew['Crew']['id'] > 0 && !empty($crew['Crew']['name']))
			{
				$db->connect();
				$db->update($crew);
			}
			
			redirect('/workers/crews');
		}
	}else
		redirect('/workers/crews');
}

?>