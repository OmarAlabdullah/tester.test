<?php

function overview()
{
	global $db, $controller;
	
	$year = (int)$controller['get']['year'];
	if($year > 0)
	{
		set('year', $year);
	}
	
	$week_number = (int)$controller['get']['week_number'];
	if($week_number > 0)
	{
		set('week_number', $week_number);
	}
	
	$client_id = (int)$controller['get']['client_id'];
	if($client_id > 0)
	{
		set('client_id', $client_id);
	}
}
function _get_select_box($selected_id = 0)
{
	global $db;
	
	$params = array(
		'timeframes' => array(
			'conditions' => array(
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'sort, created'
		)
	);
	$timeframes = $db->select($params);
	
	$return = '<select class="calendar_timeframe_select">';
	$return .= '<option value="0">&nbsp;</option>';
	foreach($timeframes as $timeframe)
	{
		$return .= '<option ' . ($selected_id == $timeframe['Timeframe']['id'] ? 'selected' : '') . ' value="' . $timeframe['Timeframe']['id'] . '">' . $timeframe['Timeframe']['timeframe'] . '</option>';
	}
	$return .= '<select>';
	
	return $return;
}

function overview_new()
{
	global $db, $controller;
	
	$year = (int)$controller['get']['year'];
	if($year > 0)
	{
		set('year', $year);
	}
	
	$week_number = (int)$controller['get']['week_number'];
	if($week_number > 0)
	{
		set('week_number', $week_number);
	}
	
	$client_id = (int)$controller['get']['client_id'];
	if($client_id > 0)
	{
		set('client_id', $client_id);
	}
}

function timeframes()
{
	global $db;
	
	$params = array(
		'timeframes' => array(
			'conditions' => array(
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'sort, created'
		)
	);
	$timeframes = $db->select($params);
	
	set('timeframes', $timeframes);
}
function add_timeframe()
{
	global $controller, $db;
	
	$controller['view'] = 'calendar/crud_timeframe';
	
	if(post())
	{
		$timeframe['Timeframe'] = $controller['post']['Timeframe'];
		
		if(!empty($timeframe['Timeframe']['timeframe']) && !empty($timeframe['Timeframe']['email_text']))
		{
			$timeframe['Timeframe']['created'] = date('Y-m-d H:i:s');
			
			$db->connect();
			$db->insert($timeframe);
			
			redirect('/calendar/timeframes');
		}
	}
}
function edit_timeframe($timeframe_id = 0)
{
	global $controller, $db;
	
	$timeframe_id = (int)$timeframe_id;
	
	if($timeframe_id > 0)
	{
		$controller['view'] = 'calendar/crud_timeframe';
		
		$timeframe = $db->first('timeframes', $timeframe_id);
		if($timeframe)
		{
			set('timeframe', $timeframe);
			if(post())
			{
				$timeframe['Timeframe'] = $controller['post']['Timeframe'];
				
				if(!empty($timeframe['Timeframe']['timeframe']) && !empty($timeframe['Timeframe']['email_text']))
				{
					$timeframe['Timeframe']['id'] = $timeframe_id;
					$timeframe['Timeframe']['created'] = date('Y-m-d H:i:s');
					
					$db->connect();
					$db->update($timeframe);
					
					redirect('/calendar/timeframes');
				}
			}
		}else
			redirect('/calendar/timeframes');
	}else
		redirect('/calendar/timeframes');
}

function export_week()
{
	global $db, $controller;
	
	$year = (int)$controller['get']['year'];
	if(!($year > 0))
		$year = date('Y');
	set('year', $year);
	
	$week_number = (int)$controller['get']['week_number'];
	if(!($week_number > 0))
		$week_number = date('W');
	set('week_number', $week_number);
	
	$dto = new DateTime();
	$dto->setISODate($year, $week_number);
	$week_start_date = ($dto->Format('Y-m-d'));
	$dto->modify('+6 days');
	$week_end_date = ($dto->Format('Y-m-d'));
	
	$params = array(
		'clients' => array(
			'conditions' => array(
				'(DATE(`appointment`) >= "' . $week_start_date . '" AND DATE(`appointment`) <= "' . $week_end_date . '")',
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'appointment, homenumber+0'
		)
	);
	$clients = $db->select($params);
	
	if(count($clients) > 0)
	{
		$project_list_ids = array();
		$project_list_ids_string = '';
		foreach($clients as $client)
		{
			$project_list_id = (int)$client['Client']['project_list_id'];
			if($project_list_id > 0)
			{
				if(!in_array($project_list_id, $project_list_ids))
				{
					$project_list_ids[] = $project_list_id;
					$project_list_ids_string .= $project_list_id . ',';
				}
			}
		}
		$project_list_ids_string = substr($project_list_ids_string, 0, -1);
		
		if(count($project_list_ids) > 0)
		{
			$params = array(
				'project_lists' => array(
					'conditions' => array(
						'(`id` IN (' . $project_list_ids_string . '))',
						'archived' => '0000-00-00 00:00:00'
					)
				)
			);
			$project_lists = $db->select($params);
			
			if(count($project_lists) > 0)
			{
				$_project_lists = $project_lists;
				$project_lists = array();
				foreach($_project_lists as $project_list)
				{
					$project_lists[$project_list['Project_list']['id']] = $project_list;
				}
				unset($_project_lists);
				
				foreach($clients as $key => $client)
				{
					$params = array(
						'photos' => array(
							'conditions' => array(
								'client_id' => $client['Client']['id'],
								'archived' => '0000-00-00 00:00:00'
							)
						)
					);
					$photos = $db->select($params);
					if(!count($photos) > 0)
						$photos = array();
					
					$clients[$key]['photos'] = $photos;
					
					$params = array(
						'documents' => array(
							'conditions' => array(
								'type' => 'dgt',
								'client_id' => $client['Client']['id'],
								'archived' => '0000-00-00 00:00:00'
							)
						)
					);
					$dgt_reports = $db->select($params);
					if(!count($dgt_reports) > 0)
						$dgt_reports = array();
					
					$clients[$key]['dgt_reports'] = $dgt_reports;
					
					$params = array(
						'documents' => array(
							'conditions' => array(
								'type' => 'nestor',
								'client_id' => $client['Client']['id'],
								'archived' => '0000-00-00 00:00:00'
							)
						)
					);
					$nestors = $db->select($params);
					if(!count($nestors) > 0)
						$nestors = array();
					
					$clients[$key]['nestors'] = $nestors;
					
					$project_lists[$client['Client']['project_list_id']]['clients'][] = $clients[$key];
				}
				
				foreach($project_lists as $key => $project_list)
				{
					$zip = new ZipArchive();
					$zip_filename = 'assets/exports/' . $year . '_' . $week_number . '_' . $project_list['Project_list']['name'] . '.zip';
					
					$renamed_zip_filename = '';
					if(file_exists($zip_filename))
					{
						$renamed_zip_filename = 'assets/exports/backup_' . time() . '_' . $year . '_' . $week_number . '_' . $project_list['Project_list']['name'] . '.zip';
						rename($zip_filename, $renamed_zip_filename);
					}
					
					if($zip->open($zip_filename, ZIPARCHIVE::CREATE) != true)
						$project_lists[$key]['errors'][] = 'Failed to create zip';
					else
					{
						foreach($project_list['clients'] as $client)
						{
							$photos_added = array();
							foreach($client['photos'] as $photo)
							{
								$photo_url = '/var/www/vhosts/drs-infra.nl/app.drs-infra.nl/photos/' . $photo['Photo']['project_list_id'] . '/' . $photo['Photo']['id'] . '.jpg';
								$type = $photo['Photo']['type'];
								if(empty($type))
									$type = 'extra';
								$zip_url = $client['Client']['street'] . '/' . $client['Client']['homenumber'] . strtoupper($client['Client']['addition']) . '/' . $type . '.jpg';
								$photo_index = 2;
								while(in_array($zip_url, $photos_added))
								{
									$zip_url = $client['Client']['street'] . '/' . $client['Client']['homenumber'] . strtoupper($client['Client']['addition']) . '/' . $type . ' (' . $photo_index . ').jpg';
									$photo_index++;
								}
								if(!$zip->addFile($photo_url, $zip_url))
									$project_lists[$key]['errors'][] = 'Failed to add photo [' . $photo['Photo']['project_list_id'] . '/' . $photo['Photo']['id'] . '.jpg' . ']';
								$photos_added[] = $zip_url;
							}
							
							foreach($client['dgt_reports'] as $document)
							{
								$document_url = 'assets/documents/dgt/' . $document['Document']['project_list_id'] . '/' . $document['Document']['filename'];
								if(file_exists($document_url))
								{
									$zip_url = $client['Client']['street'] . '/' . $client['Client']['homenumber'] . strtoupper($client['Client']['addition']) . '/' . $document['Document']['filename'];
									if(!$zip->addFile($document_url, $zip_url))
										$project_lists[$key]['errors'][] = 'Failed to add dgt document [' . $document['Document']['project_list_id'] . '/' . $document['Document']['filename'] . ']';
								}else
									$project_lists[$key]['errors'][] = 'Failed to add dgt document [' . $document['Document']['project_list_id'] . '/' . $document['Document']['filename'] . ']';
							}
							
							foreach($client['nestors'] as $document)
							{
								$document_url = 'assets/documents/nestor_reports/' . $document['Document']['project_list_id'] . '/' . $document['Document']['filename'];
								if(file_exists($document_url))
								{
									$zip_url = $client['Client']['street'] . '/' . $client['Client']['homenumber'] . strtoupper($client['Client']['addition']) . '/' . $document['Document']['filename'];
									if(!$zip->addFile($document_url, $zip_url))
										$project_lists[$key]['errors'][] = 'Failed to add nestor document [' . $document['Document']['project_list_id'] . '/' . $document['Document']['filename'] . ']';
								}else
									$project_lists[$key]['errors'][] = 'Failed to add nestor document [' . $document['Document']['project_list_id'] . '/' . $document['Document']['filename'] . ']';
							}
						}
						
						$project_lists[$key]['zip_filename'] = $zip_filename;
						if(!$zip->close())
							$project_lists[$key]['errors'][] = 'Failed to save zip';
						
						if(file_exists($zip_filename))
						{
							if(strlen($renamed_zip_filename) > 0)
								unlink($renamed_zip_filename);
						}
					}
				}
				
				set('project_lists', $project_lists);
			}
		}
	}
}

?>