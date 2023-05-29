<?php
	function upload_list()
	{
		$return = array(
			'succes' => false
		);
		if(!empty($_FILES['file']['name']))
		{
			if(move_uploaded_file($_FILES['file']['tmp_name'], 'files/' . $_FILES['file']['name']))
			{
				$return['succes'] = true;
				$return['filename'] = $_FILES['file']['name'];
			}
		}
		print(json_encode($return));
	}
	
	function upload_documents($part = 0)
	{
		$part = (int)$part;
		
		global $controller;
		
		$return = array(
			'succes' => false
		);
		if(!empty($_FILES['file']['name']))
		{
			$return['part'] = $part;
			if($part == 0)
			{
				$return['exists'] = file_exists($controller['post']['filename']);
				if($return['exists'])
					$return['unlink'] = unlink($controller['post']['filename']);
			}
			
			$out_fp = fopen($controller['post']['filename'], 'ab');
			$in_fp = fopen($_FILES['file']['tmp_name'], 'rb');
			
			$return['out'] = $controller['post']['filename'];
			
			while($buff = fread($in_fp, ((int)ini_get('upload_max_filesize') * 1024 * 1024)))
			{
				if(fwrite($out_fp, $buff) !== false)
					$return['succes'] = true;
			}
			fclose($out_fp);
			fclose($in_fp);
		}
		print(json_encode($return));
	}
	function parse_documents()
	{
		global $controller, $db;
		
		$return = array(
			'succes' => false,
			'error' => false,
			'is_zip' => false,
			'matched' => 0
		);
		
		$filename = $controller['post']['filename'];
		
		$return['filename'] = $filename;
		
		if(!empty($filename))
		{
			$return['succes'] = true;
			
			if(!file_exists($filename))
				$return['error'] = 'Bestand bestaat niet';
			else
			{
				$pathinfo = pathinfo($filename);
				$return['ext'] = strtolower($pathinfo['extension']);
				
				if($return['ext'] == 'pdf')
				{
					$return['files'][] = $pathinfo['basename'];
				}
				
				if($return['ext'] == 'zip')
				{
					$return['is_zip'] = true;
					$return['zip_available'] = class_exists('ZipArchive');
					if($return['zip_available'])
					{
						$zip = new ZipArchive();
						$filename = $filename;
						
						$z_open = $zip->open($filename);
						if($z_open === true)
						{
							//$return['zip']['resource'] = $zip;
							$return['zip']['number_of_files'] = $zip->numFiles;
							if($return['zip']['number_of_files'] > 0)
							{
								for($i = 0; $i < $return['zip']['number_of_files']; $i++)
								{
									$zip_filename = $zip->getNameIndex($i);
									$pathinfo = pathinfo($zip_filename);
									$ext = strtolower($pathinfo['extension']);
									if($ext == 'pdf')
									{
										$return['files'][] = $zip_filename;
									}
								}
							}
						}else
						{
							$return['error'] = 'Kan zip bestand niet openen [' . $z_open . ']';
						}
					}else
						$return['error'] = 'ZipArchive is niet ingeschakeld op de server';
				}
				
				if($return['error'] === false)
				{
					$pdfs = array();
					foreach($return['files'] as $filename)
					{
						$filename_start = reset(explode('-', str_replace(' ', '', $filename)));
						$filename_end = end(explode('-', str_replace(' ', '', $filename)));
						if(strlen($filename_start) > 6)
						{
							$zipcode = substr($filename_start, 0, 6);
							$homenumber_addition = substr($filename_start, 6);
							$homenumber = '';
							$addition = '';
							$letter_found = false;
							for($i = 0; $i < strlen($homenumber_addition); $i++)
							{
								$char = substr($homenumber_addition, $i, 1);
								if(!is_numeric($char))
									$letter_found = true;
								
								if($letter_found)
									$addition .= $char;
								else
									$homenumber .= $char;
							}
							
							$type = 'onbekend';
							$new = false;
							if(stristr($filename, 'dichtheid') !== false)
								$type = 'dichtheidsbeproeving';
							elseif(stristr($filename, 'sterkte') !== false)
								$type = 'sterktebeproeving';
							
							if(stristr($filename, 'nieuw') !== false)
								$new = true;
							
							$pdfs[strtoupper($zipcode) . (int)$homenumber . strtoupper($addition)][] = array(
								'zipcode' => strtoupper($zipcode),
								'homenumber' => (int)$homenumber,
								'addition' => strtolower($addition),
								'type' => $type,
								'new' => $new,
								'filename' => $filename
							);
						}
						
						
						
						
					}
					ksort($pdfs);
					foreach($pdfs as $_pdfs)
					{
						foreach($_pdfs as $pdf)
						{
							$params = array(
								'clients' => array(
									'conditions' => array(
										'zipcode' => $pdf['zipcode'],
										'homenumber' => $pdf['homenumber'],
										'addition' => $pdf['addition'],
										'project_list_id' => (int)$controller['post']['project_list_id']
									)
								)
							);
							$clients = $db->select($params);
							
							$pdf['matched'] = (count($clients) > 0);
							if($pdf['matched'])
								$pdf['client_id'] = $clients[0]['Client']['id'];
							
							$pdf['type_matched'] = ($pdf['type'] != 'onbekend');
							
							if($pdf['matched'] && $pdf['type_matched'])
								$return['matched']++;
							
							$return['pdfs'][] = $pdf;
						}
					}
				}
			}
		}
		
		print(json_encode($return));
	}
	function save_dgt_reports()
	{
		global $controller, $db;
		
		$return = array(
			'succes' => false
		);
		
		$return['post'] = $controller['post'];
		
		foreach($controller['post']['dgt_reports'] as $dgt_report)
		{
			$pathinfo = pathinfo($dgt_report['filename']);
			$ext = strtolower($pathinfo['extension']);
			
			$document = array(
				'Document' => array(
					'type' => 'dgt',
					'subtype' => $dgt_report['subtype'],
					'filename' => $dgt_report['filename'],
					'project_list_id' => (int)$controller['post']['project_list_id'],
					'client_id' => (int)$dgt_report['client_id'],
					'ext' => $ext,
					'created' => date('Y-m-d H:i:s')
				)
			);
			$db->connect();
			$insert_id = $db->insert($document);
			
			if($insert_id > 0)
			{
				$zip = new ZipArchive();
				if($zip->open($controller['post']['zip_filename']) === true)
				{
					$fp = $zip->getStream($dgt_report['filename']);
			    if(!$fp)
			    {
			    	$return['failed'][] = $dgt_report['filename'];
			    }else
			    {
						$contents = '';
						
						if(!is_dir('assets/documents/dgt/' . (int)$controller['post']['project_list_id']))
							mkdir('assets/documents/dgt/' . (int)$controller['post']['project_list_id'], 0777);
						
						$new_fp = fopen('assets/documents/dgt/' . (int)$controller['post']['project_list_id'] . '/' . $dgt_report['filename'], 'w');
						
						while(!feof($fp))
						{
							$part = fread($fp, 8192);
							$contents .= $part;
							fwrite($new_fp, $part);
						}
						
				    fclose($fp);
				    fclose($new_fp);
				    
				    $return['succeded'][] = $dgt_report['filename'];
				    $return['succes'] = true;
				  }
				}
			}
		}
		
		print(json_encode($return));
	}
	
	function _ext_type($filename)
	{
		$pathinfo = pathinfo($filename);
		
		if($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls')
			return 'excel';
		
		if($pathinfo['extension'] == 'txt' || $pathinfo['extension'] == 'csv')
			return 'txt';
		
		return false;
	}
	
	function load_preview()
	{
		global $controller;
		
		$return = array(
			'succes' => false,
			'error' => false
		);
		
		$filename = $controller['post']['filename'];
		if(empty($filename) && !empty($controller['get']['filename']))
			$filename = $controller['get']['filename'];
		
		if(!empty($filename))
		{
			$return['succes'] = true;
			
			if(!file_exists('files/' . $filename))
				$return['error'] = 'Bestand bestaat niet';
			else
			{
				$data = _file_to_rows($filename);
				if(count($data['data']) >= 1)
				{
					for($i = 0; $i < 10; $i++)
						if(isset($data['data'][$i]))
							$return['data'][$i] = $data['data'][$i];
					//$return['data'] = $data['data'];
					
					
					$return['num_rows'] = $data['num_rows'];
				}else
				{
					$return['error'] = 'Bestand kan niet gelezen worden';
				}
			}
		}
		
		print(json_encode($return));
	}
	
	function _file_to_rows($filename)
	{
		$return = array(
			'data' => array(),
			'num_rows' => 0
		);
		
		$ext_type = _ext_type($filename);
		
		if($ext_type == 'excel')
		{
			if(include('lib/classes/SimpleXLSX.php'))
			{
				if($xlsx = SimpleXLSX::parse('files/' . $filename))
				{
					$i = 0;
					foreach($xlsx->rows() as $row)
					{
						//if($i <= 9)
							$return['data'][] = $row;
						$i++;
					}
				}
			}
		}elseif($ext_type == 'txt')
		{
			$csv = array_map('str_getcsv', file('files/' . $filename));
			
			if(count($csv) >= 2 && count($csv[0]) >= 2)
			{
				$i = 0;
				foreach($csv as $row)
				{
					//if($i <= 9)
						$return['data'][] = $row;
					$i++;
				}
			}else
			{
				$csv = str_getcsv(file_get_contents('files/' . $filename), "\n");
				if(count($csv) >= 2)
				{
					$i = 0;
					foreach($csv as $row)
					{
						$csv_row = str_getcsv($row, ";");
						if(count($csv_row) >= 2)
						{
							//if($i <= 9)
								$return['data'][] = $csv_row;
							$i++;
						}
					}
				}
			}
		}
		$return['num_rows'] = $i;
		
		return $return;
	}
	
	function test()
	{
		global $controller;
		pr($controller['get']['filename']);
		pr(_file_to_rows($controller['get']['filename']));
	}
	
	function import_list()
	{
		global $controller, $userLoggedIn, $db;
		
		$return = array(
			'succes' => false,
			'error' => false
		);
		
		//$return['post'] = $controller['post'];
		
		$filename = $controller['post']['filename'];
		
		if(!file_exists('files/' . $filename))
			$return['error'] = 'Bestand bestaat niet';
		else
		{
			if($controller['post']['add_to_exsisting_list'] != 'true')
			{
				$project_number = $controller['post']['project_number'];
				
				$project_list_name = $controller['post']['list_name'];
				if(empty($project_list_name))
					$project_list_name = 'Lijst ' . date('d-m-Y H:i:s');
				
				$project_list = array(
					'project_list' => array(
						'user_id' => $userLoggedIn['User']['id'],
						'project_number' => $project_number,
						'name' => $project_list_name,
						'created' => date('Y-m-d H:i:s')
					)
				);
				
				$insert_id = $db->insert($project_list);
			}else
				$insert_id = $controller['post']['exsisting_list'];
			
			if($insert_id > 0)
			{
				$return['list_id'] = $insert_id;
				
				$data = _file_to_rows($filename);
				if(count($data['data']) >= 1)
				{
					foreach($data['data'] as $i => $row)
					{
						$row[2] = strtolower($row[2]);
						$row[3] = strtoupper(str_replace(' ', '', $row[3]));
						
						$client = array(
							'Client' => array(
								'project_list_id' => $return['list_id'],
								'street' => $row[0],
								'homenumber' => $row[1],
								'addition' => $row[2],
								'zipcode' => $row[3],
								'city' => $row[4],
								'phone' => $row[5],
								'peko' => $row[6],
								'zadel' => $row[7],
								'internal_remarks' => $row[8],
								'remarks' => $row[9],
								'created' => date('Y-m-d H:i:s')
							)
						);
						
						if($controller['post']['skip_first_row'] != 'true' || $i > 0)
						{
							if(!empty($client['Client']['zipcode']))
								if(!$db->insert($client))
									$return['error'] = 'Rij kan niet worden toegevoegd';
						}
					}
				}
				
				$return['succes'] = true;
			}else
				$return['error'] = 'Lijst kan niet worden aangemaakt';
		}
		
		print(json_encode($return));
	}
	
	function create_empty_list()
	{
		global $controller, $userLoggedIn, $db;
		
		$return = array(
			'succes' => false,
			'error' => false
		);
		
		$project_list_name = $controller['post']['list_name'];
		if(empty($project_list_name))
			$project_list_name = 'Lijst ' . date('d-m-Y H:i:s');
		
		$project_number = $controller['post']['project_number'];
		
		$project_list = array(
			'project_list' => array(
				'user_id' => $userLoggedIn['User']['id'],
				'project_number' => $project_number,
				'name' => $project_list_name,
				'created' => date('Y-m-d H:i:s')
			)
		);
		
		$insert_id = $db->insert($project_list);
		
		if($insert_id > 0)
		{
			$return['succes'] = true;
			$return['project_list_id'] = $insert_id;
		}
		
		print(json_encode($return));
	}
	
	function remove()
	{
		global $controller, $db;
		
		$return = array(
			'succes' => false,
			'error' => false
		);
		
		$project_list_ids = $controller['post']['project_list_ids'];
		
		foreach($project_list_ids as $project_list_id)
		{
			$project_list = $db->first('project_lists', $project_list_id);
			if($project_list)
			{
				$return['succes'] = true;
				
				$project_list['Project_list']['archived'] = date('Y-m-d H:i:s');
				$db->update($project_list);
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
		
		$project_list_ids = $controller['post']['project_list_ids'];
		
		foreach($project_list_ids as $project_list_id)
		{
			$project_list = $db->first('project_lists', $project_list_id);
			if($project_list)
			{
				$origional_project_list_id = $project_list['Project_list']['id'];
				
				unset($project_list['Project_list']['id']);
				$project_list['Project_list']['user_id'] = $userLoggedIn['User']['id'];
				$project_list['Project_list']['name'] .= ' (kopie)';
				$project_list['Project_list']['created'] = date('Y-m-d H:i:s');
				
				$insert_id = $db->insert($project_list);
				
				if($insert_id > 0)
				{
					$return['succes'] = true;
					
					$params = array(
						'clients' => array(
							'conditions' => array(
								'project_list_id' => $origional_project_list_id
							)
						)
					);
					$clients = $db->select($params);
					foreach($clients as $client)
					{
						unset($client['Client']['id']);
						$client['Client']['project_list_id'] = $insert_id;
						$client['Client']['created'] = date('Y-m-d H:i:s');
						
						$db->insert($client);
					}
				}
			}
		}
		
		print(json_encode($return));
	}
	
	function get_addresses($project_list_id = 0)
	{
		$project_list_id = (int)$project_list_id;
		
		$return = array(
			'succes' => false
		);
		
		if($project_list_id > 0)
		{
			global $controller, $db;
			
			$params = array(
				'clients' => array(
					'conditions' => array(
						'project_list_id' => $project_list_id
					)
				)
			);
			$clients = $db->select($params);
			
			//$return['clients'] = $clients;
			
			$return['parsed'] = array();
			
			foreach($clients as $client)
			{
				$zipcode = strtoupper(str_replace(' ', '', $client['Client']['zipcode']));
				$homenumber = (int)(str_replace(' ', '', $client['Client']['homenumber']));
				$addition = strtoupper(str_replace(' ', '', $client['Client']['addition']));
				$return['parsed'][$zipcode][$homenumber . $addition] = array(
					'client_id' => (int)$client['Client']['id'],
					'homenumber' => $homenumber,
					'addition' => $addition
				);
			}
			
			$return['succes'] = true;
		}
		
		print(json_encode($return));
	}
	
	function parse_sketches()
	{
		global $controller, $db;
		
		$return = array(
			'succes' => false,
			'error' => false,
			'is_zip' => false,
			'matched' => 0
		);
		
		$filename = $controller['post']['filename'];
		$project_list_id = (int)$controller['post']['project_list_id'];
		
		$return['filename'] = $filename;
		
		if($project_list_id > 0 && !empty($filename))
		{
			$return['succes'] = true;
			
			if(!file_exists($filename))
				$return['error'] = 'Bestand bestaat niet';
			else
			{
				$pathinfo = pathinfo($filename);
				$return['ext'] = strtolower($pathinfo['extension']);
				
				if($return['ext'] == 'pdf')
				{
					$return['files'][] = $pathinfo['basename'];
				}
				
				if($return['ext'] == 'zip')
				{
					$return['is_zip'] = true;
					$return['zip_available'] = class_exists('ZipArchive');
					if($return['zip_available'])
					{
						$zip = new ZipArchive();
						
						if($zip->open($filename) === true)
						{
							$return['zip']['number_of_files'] = $zip->numFiles;
							if($return['zip']['number_of_files'] > 0)
							{
								for($i = 0; $i < $return['zip']['number_of_files']; $i++)
								{
									$zip_filename = $zip->getNameIndex($i);
									$pathinfo = pathinfo($zip_filename);
									if(isset($pathinfo['extension']))
									{
										$ext = strtolower($pathinfo['extension']);
										if($ext == 'pdf')
										{
											if(substr($pathinfo['dirname'], 0, 1) != '_' && substr($pathinfo['basename'], 0, 1) != '_' && substr($pathinfo['basename'], 0, 1) != '.')
												$return['files'][] = $zip_filename;
										}
									}
								}
							}
						}else
						{
							$return['error'] = 'Kan zip bestand niet openen';
						}
					}
				}
			}
			
			
			if($return['error'] === false)
			{
				if(!is_dir('assets/documents/sketches/' . $project_list_id))
					mkdir('assets/documents/sketches/' . $project_list_id, 0777);
				
				foreach($return['files'] as $filename)
				{
					$pathinfo = pathinfo($filename);
					$match = _get_match_by_streetname($project_list_id, $pathinfo['filename']);
					$return['matches'][] = $match;
					
					$save_filename = $pathinfo['basename'];
					$zip_filename = $pathinfo['basename'];
					
					$file_number = 2;
					while(file_exists('assets/documents/sketches/' . $project_list_id . '/' . $save_filename))
					{
						$save_filename = $pathinfo['filename'] . ' (' . $file_number . ').' . strtolower($pathinfo['extension']);
						$file_number++;
						
						if($file_number > 100)
							break;
					}
					
					$document = array(
						'Document' => array(
							'type' => 'sketch',
							'filename' => $save_filename,
							'project_list_id' => $project_list_id,
							'client_id' => $match['client_id'],
							'ext' => strtolower($pathinfo['extension']),
							'created' => date('Y-m-d H:i:s')
						)
					);
					
					if($match['match'])
					{
						if($match['client_id'] == 0 && $match['matched_street'])
						{
							$document['Document']['street'] = $match['street'];
						}
					}
					
					$insert_id = (int)$db->insert($document);
					if($insert_id > 0)
					{
						if($return['is_zip'])
						{
							$fp = $zip->getStream($filename);
					    if(!$fp)
					    {
					    	$return['failed'][] = $zip_filename;
					    }else
					    {
								$contents = '';
								
								$new_fp = fopen('assets/documents/sketches/' . $project_list_id . '/' . $save_filename, 'w');
								
								while(!feof($fp))
								{
									$part = fread($fp, 8192);
									$contents .= $part;
									fwrite($new_fp, $part);
								}
								
						    fclose($fp);
						    fclose($new_fp);
						    
						    $return['succeded'][] = $zip_filename;
						    $return['succes'] = true;
						  }
						}else
						{
							rename($return['filename'], 'assets/documents/sketches/' . $project_list_id . '/' . $save_filename);
						}
					}
				}
			}
		}
		
		print(json_encode($return));
	}
	
	function _get_match_by_streetname($project_list_id = 0, $filename = '')
	{
		global $db;
		
		$filename = (string)trim($filename);
		
		if(strtolower(substr($filename, 0, 6)) == 'nestor')
			$filename = substr($filename, 6);
		
		$filename = trim($filename);
		
		$return = array(
			'match' => false,
			'matched_client' => false,
			'matched_street' => false,
			'filename' => $filename, 
			'client_id' => 0
		);
		
		if($project_list_id > 0 && !empty($filename))
		{
			$street = '';
			for($c = 0; $c < strlen($filename); $c++)
			{
				$char = substr($filename, $c, 1);
				if(!is_numeric($char))
					$street .= strtolower($char);
				else
					break;
			}
			$return['street'] = trim($street);
			
			$homenumber = '';
			for($c = $c; $c < strlen($filename); $c++)
			{
				$char = substr($filename, $c, 1);
				if(is_numeric($char))
					$homenumber .= ($char);
				else
					break;
			}
			$return['homenumber'] = trim($homenumber);
			
			$addition = '';
			for($c = $c; $c < strlen($filename); $c++)
			{
				$char = substr($filename, $c, 1);
				$addition .= strtoupper($char);
			}
			$return['addition'] = trim($addition);
			
			
			$params = array(
				'clients' => array(
					'conditions' => array(
						'project_list_id' => $project_list_id,
						'street' => $return['street'],
						'homenumber' => $return['homenumber'],
						'addition' => $return['addition'],
						'archived' => '0000-00-00 00:00:00'
					),
					'select' => 'first'
				)
			);
			$client = $db->select($params);
			
			if($client)
			{
				$return['match'] = true;
				$return['matched_client'] = true;
				$return['client_id'] = $client['Client']['id'];
			}
			
			if(!($return['client_id'] > 0))
			{
				$params = array(
					'clients' => array(
						'conditions' => array(
							'project_list_id' => $project_list_id,
							'street' => $return['street'],
							'archived' => '0000-00-00 00:00:00'
						)
					)
				);
				$clients = $db->select($params);
				if($clients)
				{
					if(count($clients) > 0)
					{
						$return['match'] = true;
						$return['matched_street'] = true;
					}
				}
			}
		}
		
		return $return;
	}
	
	function parse_nestor_reports()
	{
		global $controller, $db;
		
		$return = array(
			'succes' => false,
			'error' => false,
			'is_zip' => false,
			'matched' => 0
		);
		
		$filename = $controller['post']['filename'];
		$project_list_id = (int)$controller['post']['project_list_id'];
		
		$return['filename'] = $filename;
		
		if($project_list_id > 0 && !empty($filename))
		{
			$return['succes'] = true;
			
			if(!file_exists($filename))
				$return['error'] = 'Bestand bestaat niet';
			else
			{
				$pathinfo = pathinfo($filename);
				$return['ext'] = strtolower($pathinfo['extension']);
				
				if($return['ext'] == 'pdf')
				{
					$return['files'][] = $pathinfo['basename'];
				}
				
				if($return['ext'] == 'zip')
				{
					$return['is_zip'] = true;
					$return['zip_available'] = class_exists('ZipArchive');
					if($return['zip_available'])
					{
						$zip = new ZipArchive();
						
						if($zip->open($filename) === true)
						{
							$return['zip']['number_of_files'] = $zip->numFiles;
							if($return['zip']['number_of_files'] > 0)
							{
								for($i = 0; $i < $return['zip']['number_of_files']; $i++)
								{
									$zip_filename = $zip->getNameIndex($i);
									$pathinfo = pathinfo($zip_filename);
									if(isset($pathinfo['extension']))
									{
										$ext = strtolower($pathinfo['extension']);
										if($ext == 'pdf')
										{
											if(substr($pathinfo['dirname'], 0, 1) != '_' && substr($pathinfo['basename'], 0, 1) != '_' && substr($pathinfo['basename'], 0, 1) != '.')
												$return['files'][] = $zip_filename;
										}
									}
								}
							}
						}else
						{
							$return['error'] = 'Kan zip bestand niet openen';
						}
					}
				}
			}
			
			
			if($return['error'] === false)
			{
				if(!is_dir('assets/documents/nestor_reports/' . $project_list_id))
					mkdir('assets/documents/nestor_reports/' . $project_list_id, 0777);
				
				foreach($return['files'] as $filename)
				{
					$pathinfo = pathinfo($filename);
					$match = _get_match_by_streetname($project_list_id, $pathinfo['filename']);
					$return['matches'][] = $match;
					
					$save_filename = $pathinfo['basename'];
					$zip_filename = $pathinfo['basename'];
					
					$file_number = 2;
					while(file_exists('assets/documents/nestor_reports/' . $project_list_id . '/' . $save_filename))
					{
						$save_filename = $pathinfo['filename'] . ' (' . $file_number . ').' . strtolower($pathinfo['extension']);
						$file_number++;
						
						if($file_number > 100)
							break;
					}
					
					$document = array(
						'Document' => array(
							'type' => 'nestor',
							'filename' => $save_filename,
							'project_list_id' => $project_list_id,
							'client_id' => $match['client_id'],
							'ext' => strtolower($pathinfo['extension']),
							'created' => date('Y-m-d H:i:s')
						)
					);
					
					if($match['match'])
					{
						if($match['client_id'] == 0 && $match['matched_street'])
						{
							$document['Document']['street'] = $match['street'];
						}
					}
					
					$return['document'] = $document;
					
					$insert_id = (int)$db->insert($document);
					if($insert_id > 0)
					{
						if($return['is_zip'])
						{
							$fp = $zip->getStream($filename);
					    if(!$fp)
					    {
					    	$return['failed'][] = $zip_filename;
					    }else
					    {
								$contents = '';
								
								$new_fp = fopen('assets/documents/nestor_reports/' . $project_list_id . '/' . $save_filename, 'w');
								
								while(!feof($fp))
								{
									$part = fread($fp, 8192);
									$contents .= $part;
									fwrite($new_fp, $part);
								}
								
						    fclose($fp);
						    fclose($new_fp);
						    
						    $return['succeded'][] = $zip_filename;
						    $return['succes'] = true;
						  }
						}else
						{
							rename($return['filename'], 'assets/documents/nestor_reports/' . $project_list_id . '/' . $save_filename);
						}
					}
					
				}
			}
		}
		
		print(json_encode($return));
	}

function parse_add_import()
{
	global $controller, $db;
	
	$return = array(
		'succes' => false
	);
	
	$filename = $controller['post']['filename'];
	$project_list_id = (int)$controller['post']['project_list_id'];
	
	if($project_list_id > 0)
	{
		$data = _file_to_rows(substr($filename, 6));
		
		//unset first row
		unset($data['data'][0]);
		
		foreach($data['data'] as $index => $_data)
		{
			if(empty($_data[1]))
				unset($data['data'][$index]);
		}
		
		$return['data'] = $data['data'];
		
		foreach($return['data'] as $client_row)
		{
			$params = array(
				'clients' => array(
					'conditions' => array(
						'project_list_id' => $project_list_id,
						'zipcode' => strtoupper(str_replace(' ', '', $client_row[3])),
						'homenumber' => (int)$client_row[1],
						'addition' => strtolower($client_row[2])
					),
					'select' => 'first'
				)
			);
			$client = $db->select($params);
			
			if($client)
			{
				//$return['clients'][] = $client;
				
				$return['rows'][] = array(
					'client_id' => $client['Client']['id'],
					'street' => $client['Client']['street'],
					'homenumber' => $client['Client']['homenumber'] . strtoupper($client['Client']['addition']),
					'zipcode' => $client['Client']['zipcode'],
					'old_peko' => $client['Client']['peko'],
					'new_peko' => $client_row[6],
					'old_zadel' => $client['Client']['zadel'],
					'new_zadel' => $client_row[7],
					'old_remarks' => $client['Client']['remarks'],
					'new_remarks' => $client_row[8],
					'old_internal_remarks' => $client['Client']['internal_remarks'],
					'new_internal_remarks' => $client_row[9]
				);
				
			}
		}
		
	}
	
	print(json_encode($return));
}
function save_add_import()
{
	global $controller, $db;
	
	$return = array(
		'succes' => false
	);
	
	$return['clients'] = $controller['post']['clients'];
	
	foreach($return['clients'] as $_client)
	{
		$client = $db->first('clients', (int)$_client['client_id']);
		if($client)
		{
			if(isset($_client['new_peko']))
				$client['Client']['peko'] = $_client['new_peko'];
			
			if(isset($_client['new_zadel']))
				$client['Client']['zadel'] = $_client['new_zadel'];
			
			if(isset($_client['new_remarks']))
				$client['Client']['remarks'] = $_client['new_remarks'];
			
			if(isset($_client['new_internal_remarks']))
				$client['Client']['internal_remarks'] = $_client['new_internal_remarks'];
			
			$db->update($client);
			$return['succes'] = true;
		}
	}
	
	print(json_encode($return));
}
?>