<?php

function remove_photo($photo_id = 0)
{
	$return = array(
		'succes' => false
	);
	
	$photo_id = (int)$photo_id;
	if($photo_id > 0)
	{
		global $db;
		
		$photo = $db->first('photos', $photo_id);
		if($photo)
		{
			$photo['Photo']['archived'] = date('Y-m-d H:i:s');
			$db->update($photo);
			
			if(mysqli_errno($db->handle) == 0)
			{
				$return['client_id'] = $photo['Photo']['client_id'];
				$return['client_finished'] = check_and_set_client_finished($photo['Photo']['client_id']);
				$return['succes'] = true;
			}
		}
	}
	
	print(json_encode($return, JSON_PRETTY_PRINT));
}

?>