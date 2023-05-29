<?php

function remove()
{
	global $controller, $db;
	
	$return = array(
		'succes' => false,
		'removed' => false
	);
	
	$photo_id = (int)$controller['post']['photo_id'];
	
	if($photo_id > 0)
	{
		$return['photo_id'] = $photo_id;
		$photo = $db->first('photos', $photo_id);
		if($photo)
		{
			$return['succes'] = true;
			$photo['Photo']['archived'] = date('Y-m-d H:i:s');
			$db->connect();
			$db->update($photo);
			$return['mysql_error'] = mysqli_error($db->handle);
			if(empty($return['mysql_error']))
			{
				$return['removed'] = true;
				$return['client_finished'] = check_and_set_client_finished($photo['Photo']['client_id']);
			}
		}
	}
	
	print(json_encode($return, JSON_PRETTY_PRINT));
}

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
function process_photo($client_id = 0, $type = '')
{
	global $controller, $db;
	
	$return = array(
		'succes' => false,
		'post' => $controller['post'],
		'filename' => '/var/www/vhosts/drs-infra.nl/app.drs-infra.nl/photos/' . time() . '_processed.jpg'
	);
	
	$client_id = (int)$client_id;
	
	$client = $db->first('clients', $client_id);
	
	$photo = array(
		'Photo' => array(
			'project_list_id' => $client['Client']['project_list_id'],
			'client_id' => $client['Client']['id'],
			'worker_id' => 0,
			'type' => strtolower(trim($type)),
			'ext' => 'jpg',
			'created' => date('Y-m-d H:i:s')
		)
	);
	$insert_id = $db->insert($photo);
	
	$return['insert_id'] = $insert_id;
	
	if($insert_id > 0)
	{
		if(!is_dir('/var/www/vhosts/drs-infra.nl/app.drs-infra.nl/photos/' . $client['Client']['project_list_id']))
		{
			$return['has_dir'] = false;
			$return['created_dir'] = mkdir('/var/www/vhosts/drs-infra.nl/app.drs-infra.nl/photos/' . $client['Client']['project_list_id'], 0777);
		}
		
		$return['filename'] = '/var/www/vhosts/drs-infra.nl/app.drs-infra.nl/photos/' . $client['Client']['project_list_id'] . '/' . $insert_id . '.jpg';
		$return['image_src'] = 'https://app.drs-infra.nl/photos/' . $client['Client']['project_list_id'] . '/' . $insert_id . '.jpg';
		$return['image_type'] = $photo['Photo']['type'];
	}
	
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
		
		unlink($controller['post']['filename']);
		
		$return['succes'] = true;
	}
	
	//unlink($controller['post']['filename']);
	
	print(json_encode($return));
}

function set_photo_type($photo_id = 0, $type = '')
{
	global $controller, $db;
	
	$return = array(
		'succes' => false
	);
	
	$photo_id = (int)$photo_id;
	
	if($photo_id > 0)
	{
		$return['photo_id'] = $photo_id;
		$photo = $db->first('photos', $photo_id);
		if($photo)
		{
			$return['succes'] = true;
			$photo['Photo']['type'] = $type;
			$db->connect();
			$db->update($photo);
			$return['mysql_error'] = mysqli_error($db->handle);
			if(empty($return['mysql_error']))
			{
				$return['type'] = $photo['Photo']['type'];
				$return['client_finished'] = check_and_set_client_finished($photo['Photo']['client_id']);
			}
		}
	}
	
	print(json_encode($return, JSON_PRETTY_PRINT));
}

?>