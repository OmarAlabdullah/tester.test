<?php

function upload_photo($part = -1)
{
	$part = (int)$part;
	
	global $controller;
	
	$return = array(
		'succes' => false
	);
	if(!empty($_FILES['file']['name']))
	{
		//if($part == 0)
		//	unset($controller['post']['filename']);
		
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
function process_photo()
{
	global $controller;
	
	$return = array(
		'succes' => false,
		'post' => $controller['post'],
		'filename' => 'files/temp_processed_' . time() . '.jpg'
	);
	
	$min_width = 1200;
	$min_height = 1200;
	
	$pathinfo = pathinfo($controller['post']['filename']);
	$ext = strtolower($pathinfo['extension']);
	$return['ext'] = $ext;
	
	$exif = exif_read_data($controller['post']['filename']);
	$return['exif'] = $exif;
	
	$gd = false;
	if($ext == 'png')
	{
		$gd = imagecreatefrompng($controller['post']['filename']);
	}if($ext == 'jpg' || $ext == 'jpeg')
	{
		$return['jpg'] = 'ja';
		$gd = imagecreatefromjpeg($controller['post']['filename']);
	}
	
	if($gd)
	{
		$imagesize = getimagesize($controller['post']['filename']);
		$return['imagesize'] = $imagesize;
		
		$o_width = $imagesize[0];
		$o_height = $imagesize[1];
		
		if(isset($exif['Orientation']))
		{
		  switch($exif['Orientation'])
		  {
		    case 3:
		      $gd = imagerotate($gd, 180, 0);
		      break;
		    case 6:
		      $gd = imagerotate($gd, 270, 0);
		      $o_width = $imagesize[1];
					$o_height = $imagesize[0];
		      break;
		    case 8:
		      $gd = imagerotate($gd, 90, 0);
		      $o_width = $imagesize[1];
					$o_height = $imagesize[0];
		      break;
		  }
		}
		
		
		
		if($o_width > $min_width && $o_height > $min_height)
		{
			$new_width = $min_width;
			$new_height = $o_height / ($o_width / $min_width);
			
			if($new_height < $min_height)
			{
				$new_height = $min_height;
				$new_width = $o_width / ($o_height / $min_height);
			}
			
			$return['n']['w'] = $new_width;
			$return['n']['h'] = $new_height;
			
			$gd_new = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($gd_new, $gd, 0, 0, 0, 0, $new_width, $new_height, $o_width, $o_height);
			
			//imagejpeg($gd, 'files/photo_origional.jpg'); //origional
			imagejpeg($gd_new, $return['filename']);
		}else
		{
			imagejpeg($gd, $return['filename']);
		}
		
		$return['succes'] = true;
	}
	
	unlink($controller['post']['filename']);
	
	print(json_encode($return));
}

function set_photo($client_id = 0, $type = '')
{
	$return = array(
		'succes' => false
	);
	
	global $controller;
	
	$temp_filename = $controller['get']['temp_filename'];
	
	$return['temp_filename'] = $temp_filename;
	
	$client_id = (int)$client_id;
	
	if($client_id > 0 && strlen($temp_filename) > 0)
	{
		global $db, $userLoggedIn;
		
		$return['mime'] = mime_content_type($temp_filename);
		$return['is_image'] = (strlen(stristr($return['mime'], 'image')) > 0);
		
		if($return['is_image'])
		{
			$client = $db->first('clients', $client_id);
			
			if($client)
			{
				
				if(!is_dir('photos/' . $client['Client']['project_list_id']))
				{
					$return['has_dir'] = false;
					$return['created_dir'] = mkdir('photos/' . $client['Client']['project_list_id'], 0777);
				}else
					$return['has_dir'] = true;
				$type = str_replace('@', ' ', $type); //die heb toegevoegd
				$photo = array(
					'Photo' => array(
						'project_list_id' => $client['Client']['project_list_id'],
						'client_id' => $client['Client']['id'],
						'worker_id' => $userLoggedIn['Worker']['id'],
						'type' => strtolower(trim($type)),
						'ext' => 'jpg',
						'created' => date('Y-m-d H:i:s')
					)
				);
				
				$insert_id = $db->insert($photo);
				
				$return['photo'] = $photo;
				$return['insert_id'] = $insert_id;
				
				if($insert_id > 0)
				{
					if(copy($temp_filename, 'photos/' . $client['Client']['project_list_id'] . '/' . $insert_id . '.jpg'))
					{
						$return['temp_filename'] = $temp_filename;
						unlink($temp_filename);
					}
					
					check_and_set_client_finished($client['Client']['id']);
					
					$return['succes'] = true;
				}
			}
		}
	}
	
	/*if($type == 'other')
		copy('files/photo.jpg', 'files/photo_' . time() . '.jpg');
	else
		copy('files/photo.jpg', 'files/photo_' . $type . '.jpg');*/
	
	print(json_encode($return));
}

function save_additional_information()
{
	$return = array(
		'succes' => false
	);
	
	global $controller, $db;
	
	$return['post'] = $controller['post'];
	
	$client_id = (int)$controller['post']['client_id'];
	
	if($client_id > 0)
	{
		$client = $db->first('clients', $client_id);
		if($client)
		{
			unset($client['Client']['city']);
			unset($client['Client']['remarks']);
			unset($client['Client']['internal_remarks']);
			$client['Client']['gas_stop'] = ($controller['post']['gas_stop'] === 'true' ? 1 : 0);
			$client['Client']['vwi'] = ($controller['post']['vwi'] === 'true' ? 1 : 0);
			$client['Client']['overlengte'] = (float)$controller['post']['ol'];
			$client['Client']['meerwerk'] = (string)$controller['post']['meerwerk'];
			$client['Client']['extra_information'] = 1;
			
			$db->update($client);
			
			$return['client'] = $client;
			$return['succes'] = true;
		}
	}
	
	print(json_encode($return));
}

function change_home_date($home_date = '')
{
	$return = array(
		'succes' => false
	);
	
	if(empty($home_date))
		$home_date = date('Y-m-d', strtotime('monday this week'));
	
	$home_date = date('Y-m-d', strtotime('this week', strtotime($home_date))); //set to monday
	
	session('home_date', $home_date);
	
	$return['home_date'] = $home_date;
	$return['succes'] = true;
	
	print(json_encode($return));
}

?>