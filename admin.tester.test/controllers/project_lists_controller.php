<?php

	function overview()
	{
		global $controller, $db;

		$filter = $controller['get']['filter'];

		$params = array(
			'project_lists' => array(
				'conditions' => array(
					'(archived="0000-00-00 00:00:00")'
				),
				'order' => 'created DESC'
			)
		);

		if(!empty($filter))
		{
			if($filter != 'all')
				$params['project_lists']['conditions']['status'] = $filter;
		}else
			$params['project_lists']['conditions']['status'] = 'open';

		$project_lists = $db->select($params);

		foreach($project_lists as $index => $project_list)
		{
			$params = array(
				'clients' => array(
					'conditions' => array(
						'project_list_id' => $project_list['Project_list']['id'],
						'(archived="0000-00-00 00:00:00")'
					)
				)
			);

			$project_lists[$index]['Clients'] = $db->select($params);

			foreach($project_lists[$index]['Clients'] as $client)
			{
				if($client['Client']['phone'] != '')
					$project_lists[$index]['has_contact_information']++;

				if($client['Client']['finished'] == 0 && $client['Client']['force_finished'] == 0 && $client['Client']['not_remediated'] == 0)
					$project_lists[$index]['nog_uit_te_voeren']++;
			}

			$params = array(
				'crews_projects' => array(
					'conditions' => array(
						'project_id' => $project_list['Project_list']['id']
					)
				)
			);
			$crews_projects = $db->select($params);
			foreach($crews_projects as $crews_project)
			{
				$crew = $db->first('crews', $crews_project['Crews_project']['crew_id']);
				$project_lists[$index]['crews_string'] .= $crew['Crew']['name'] . ', ';
			}
			$project_lists[$index]['crews_string'] = substr($project_lists[$index]['crews_string'], 0, -2);
		}
		set('project_lists', $project_lists);
	}

	function details($project_list_id = 0)
	{
		global $db, $controller, $userLoggedIn;

		if(!($project_list_id > 0))
			redirect('/project_lists/overview');

		$params = array(
			'project_lists' => array(
				'conditions' => array(
					'archived = "0000-00-00 00:00:00"',
					'id' => $project_list_id
				),
				'select' => 'first'
			)
		);
		$project_list = $db->select($params);

		if(!$project_list)
			redirect('/project_lists/overview');

		$params = array(
			'clients' => array(
				'conditions' => array(
					'project_list_id' => $project_list['Project_list']['id'],
					'(archived IS NULL OR archived="0000-00-00 00:00:00")'
				),
				'order' => 'street, (homenumber + 0)'
			)
		);

		if(!empty($controller['get']['q']))
		{
			$search_words = explode(' ', $controller['get']['q']);

			$search_string = '';
			foreach($search_words as $search_word)
			{
				if(is_numeric($search_word))
					$search_string .= ' AND (homenumber LIKE "' . $search_word . '")';
				else
					$search_string .= ' AND (street LIKE "%' . $search_word . '%" OR zipcode LIKE "' . $search_word . '" OR city LIKE "%' . $search_word . '%")';
			}
			$search_string = '(' . substr($search_string, 5) . ')';
			$params['clients']['conditions'][] = $search_string;
		}


		if(!empty($controller['get']['filter']))
		{
			switch($controller['get']['filter'])
			{
				case 'no_letter_sent':
					$params['clients']['conditions'][] = '(send_letter_1 = "" AND send_letter_2 = "" AND send_letter_3 = "")';
				break;
				case 'no_contact_details':

					$params['clients']['conditions'][] = '(email = "" AND phone = "")';
				break;
				case 'to_plan':
					$params['clients']['conditions'][] = '(appointment = "0000-00-00 00:00:00")';
					$params['clients']['conditions'][] = '(not_remediated = 0)';
				break;
				case 'unexecuted':
					$params['clients']['conditions'][] = '(finished = 0)';
				break;
			}
		}

		$clients = $db->select($params);

		set('project_list', $project_list);
		set('clients', $clients);
		set('page_size', ($userLoggedIn['preferences']['pagination'] > 0 ? $userLoggedIn['preferences']['pagination'] : 20));

		$params = array(
			'project_lists' => array(
				'conditions' => array(
					'archived' => '0000-00-00 00:00:00'
				)
			)
		);
		$project_lists = $db->select($params);

		set('project_lists', $project_lists);
	}

	function add_import($project_list_id = 0)
	{
		global $db, $controller, $userLoggedIn;

		if(!($project_list_id > 0))
			redirect('/project_lists/overview');

		$params = array(
			'project_lists' => array(
				'conditions' => array(
					'archived = "0000-00-00 00:00:00"',
					'id' => $project_list_id
				),
				'select' => 'first'
			)
		);
		$project_list = $db->select($params);

		if(!$project_list)
			redirect('/project_lists/overview');

		set('project_list', $project_list);
	}

	function uploads()
	{
		$raw_contents = (array_slice(scandir('files/', SCANDIR_SORT_ASCENDING), 1));

		$files = array();
		$i = 0;
		foreach($raw_contents as $file)
		{
			if($file != '.' && $file != '..')
			{
				if(is_dir('files/' . $file))
				{
					$files[$i]['filename'] = $file;
					$files[$i]['filetype'] = 'dir';
				}else
				{
					$pathinfo = pathinfo($file);

					$filetime = filemtime('files/' . $file);

					$files[$filetime . '_' . $i]['filename'] = $file;

					if($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls' || $pathinfo['extension'] == 'csv')
						$files[$filetime . '_' . $i]['filetype'] = 'excel';
					if($pathinfo['extension'] == 'txt')
						$files[$filetime . '_' . $i]['filetype'] = 'txt';
					if($pathinfo['extension'] == 'jpg' || $pathinfo['extension'] == 'jpeg' || $pathinfo['extension'] == 'gif' || $pathinfo['extension'] == 'png' || $pathinfo['extension'] == 'tif' || $pathinfo['extension'] == 'tiff')
						$files[$filetime . '_' . $i]['filetype'] = 'image';

					$files[$filetime . '_' . $i]['filetime'] = $filetime;
				}
				$i++;
			}
		}

		ksort($files);

		set('files', $files);
	}

	function remove_uploads()
	{
		global $controller;

		if($controller['get']['action'] == 'remove' && $controller['get']['filecount'] > 0)
		{
			$files = explode(',', $controller['get']['files']);

			if($controller['get']['filecount'] == count($files))
			{
				foreach($files as $file)
				{
					if(file_exists('files/' . $file))
					{
						@unlink('files/' . $file);
					}
				}
			}
			redirect('/project_lists/uploads');
		}
	}

	function fill_fake()
	{
		global $db;


		for($n = 1; $n <= 20; $n++)
		{
			$client = array(
				'Client' => array(
					'project_list_id' => 1,
					'street' => 'Benedenstraat',
					'homenumber' => $n,
					'addition' => (rand(0, 1) == 1 ? 'a': 'b'),
					'zipcode' => '2222BB',
					'city' => 'Amsterdam',
					'created' => date('Y-m-d H:i:s')
				)
			);
			$db->insert($client);
		}

	}

	function import()
	{
		global $db;

		$params = array(
			'project_lists' => array(
				'conditions' => array(
					'archived' => '0000-00-00 00:00:00'
				)
			)
		);
		$project_lists = $db->select($params);

		set('project_lists', $project_lists);
	}

	function add()
	{
		global $controller;

		$controller['view'] = 'projects_lists/crud';
	}

	function settings($project_list_id = 0)
	{
		$project_list_id = (int)$project_list_id;

		if($project_list_id > 0)
		{
			global $db, $controller;

			$project_list = $db->first('project_lists', $project_list_id);
			if($project_list)
			{
				$required_photos = explode('|', $project_list['Project_list']['required_photos']);
				foreach($required_photos as $index => $required_photo)
					if(empty($required_photo))
						unset($required_photos[$index]);

				$project_list['Project_list']['required_photos_array'] = $required_photos;

/////////////// die heb ik toegevoegd voor additional_data //////////////////////////
				$additional_data = explode('|', $project_list['Project_list']['additional_data']);
				foreach($additional_data as $index => $d)
					if(empty($d))
						unset($additional_data[$index]);

				$project_list['Project_list']['additional_data_array'] = $additional_data;
/////////////////////////////////////////////////////////////////////////////////////////

				$params = array(
					'crews' => array(
						'conditions' => array(
							'archived' => '0000-00-00 00:00:00'
						)
					)
				);
				$crews = $db->select($params);
				set('crews', $crews);

				$params = array(
					'crews_projects' => array(
						'conditions' => array(
							'project_id' => $project_list['Project_list']['id']
						)
					)
				);
				$crews_projects = $db->select($params);
				foreach($crews_projects as $crews_project)
				{
					$project_list['crews'][$crews_project['Crews_project']['crew_id']] = $crews_project['Crews_project']['crew_id'];
				}

				set('project_list', $project_list);

				if(post())
				{
					/////////////// die heb ik toegevoegd voor additional_data //////////////////////////
					$additional_data = $controller['post']['additional_data'];


					$additional_data_string = implode('|', array_filter(array_map('trim', $additional_data)));
					$additional_data_string = $db->real_escape_string($additional_data_string); // Escape the string to prevent SQL injection



						$query = ("UPDATE `project_lists` SET additional_data = '$additional_data_string' WHERE `project_id` = " . $project_list['Project_list']['id']);
						$db->connect();
					echo $additional_data_string;
						$db->query($query);


					///////////////////////////////////////////////////////////////////////////////////

					$required_photos = $controller['post']['required_photo'];
					foreach($required_photos as $required_photo)
					{
						$required_photo = trim($required_photo);
						if(!empty($required_photo))
						{
							$required_photos_string .= $required_photo . '|';
						}
					}
					$required_photos_string = substr($required_photos_string, 0, -1);

					$project_list['Project_list'] = $controller['post']['Project_list'];
					$project_list['Project_list']['required_photos'] = $required_photos_string;

					if($project_list['Project_list']['mbo'] != 1)
						$project_list['Project_list']['mbo'] = 0;
					if($project_list['Project_list']['mbn'] != 1)
						$project_list['Project_list']['mbn'] = 0;
					if($project_list['Project_list']['gbo'] != 1)
						$project_list['Project_list']['gbo'] = 0;
					if($project_list['Project_list']['gbn'] != 1)
						$project_list['Project_list']['gbn'] = 0;
					if($project_list['Project_list']['dgb'] != 1)
						$project_list['Project_list']['dgb'] = 0;

					$project_list['Project_list']['project_number'] = (int)$project_list['Project_list']['project_number'];
					$project_list['Project_list']['id'] = $project_list_id;

					$db->connect();
					$db->update($project_list);

					$db->query("DELETE FROM `crews_projects` WHERE `project_id` = " . $project_list['Project_list']['id']);


					$crews = $controller['post']['crews'];
					foreach($crews as $crew_id)
					{
						$crew_id = (int)$crew_id;
						if($crew_id > 0)
						{
							$crews_project = array(
								'Crews_project' => array(
									'crew_id' => $crew_id,
									'project_id' => $project_list['Project_list']['id'],
									'created' => date('Y-m-d H:i:s')
								)
							);
							$db->connect();
							$db->insert($crews_project);
						}
					}

					$params = array(
						'clients' => array(
							'conditions' => array(
								'project_list_id' => $project_list['Project_list']['id']
							)
						)
					);
					$clients = $db->select($params);
					foreach($clients as $client)
					{
						check_and_set_client_finished($client['Client']['id']);
					}

					redirect('/project_lists/details/' . $project_list['Project_list']['id']);
				}
			}
		}
	}

	function view_xlsx()
	{
		global $controller;
		set('data', _file_to_rows($controller['get']['filename']));
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

	function documents($project_list_id = 0)
	{
		$project_list_id = (int)$project_list_id;

		if($project_list_id > 0)
		{
			global $db, $controller;

			$project_list = $db->first('project_lists', $project_list_id);
			if($project_list)
			{
				set('project_list', $project_list);

				$params = array(
					'documents' => array(
						'conditions' => array(
							'archived' => '0000-00-00 00:00:00',
							'type' => 'dgt',
							'project_list_id' => $project_list['Project_list']['id']
						)
					)
				);
				$dgt_documents = $db->select($params);
				set('dgt_documents', $dgt_documents);

				$params = array(
					'documents' => array(
						'conditions' => array(
							'archived' => '0000-00-00 00:00:00',
							'type' => 'sketch',
							'project_list_id' => $project_list['Project_list']['id']
						)
					)
				);
				$sketches = $db->select($params);
				set('sketches', $sketches);

				$params = array(
					'documents' => array(
						'conditions' => array(
							'archived' => '0000-00-00 00:00:00',
							'type' => 'nestor',
							'project_list_id' => $project_list['Project_list']['id']
						)
					)
				);
				$nestor_reports = $db->select($params);
				set('nestor_reports', $nestor_reports);
			}
		}
	}

	function dgt_reports($project_list_id = 0)
	{
		$project_list_id = (int)$project_list_id;

		if($project_list_id > 0)
		{
			global $db, $controller;

			$controller['js'][] = 'link_address.js';

			$project_list = $db->first('project_lists', $project_list_id);
			if($project_list)
			{
				set('project_list', $project_list);
			}
		}
	}

	function sketches($project_list_id = 0)
	{
		$project_list_id = (int)$project_list_id;

		if($project_list_id > 0)
		{
			global $db;

			$project_list = $db->first('project_lists', $project_list_id);
			if($project_list)
			{
				set('project_list', $project_list);
			}
		}
	}

	function nestor_reports($project_list_id = 0)
	{
		$project_list_id = (int)$project_list_id;

		if($project_list_id > 0)
		{
			global $db;

			$project_list = $db->first('project_lists', $project_list_id);
			if($project_list)
			{
				set('project_list', $project_list);
			}
		}
	}

?>
