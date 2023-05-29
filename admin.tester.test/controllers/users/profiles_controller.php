<?php
	function profile_overview()
	{
		global $db;
		
		$params = array(
			'users' => array(
				
			)
		);
		$users = $db->select($params);
		
		pr($users);
		
		$filename = 'assets/storage/user_profiles.json';
		if(file_exists($filename))
		{
			$data_json = file_get_contents($filename);
			$data_arr = json_decode($data_json, true);
			
			pr($data_arr);
			
			$data_arr = array(
				'user_profiles' => array(
					'worker',
					'admin'
				)
			);
			
			$newsave = fopen($filename, 'w');
			fwrite($newsave, json_encode($data_arr, JSON_PRETTY_PRINT));
			fclose($newsave);
		}
	}
	
?>