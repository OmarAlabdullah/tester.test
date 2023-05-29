<?php

function get_week($year = 0, $week_number = 0)
{
	$year = (int)$year;
	$week_number = (int)$week_number;
	
	$return = array(
		'succes' => false
	);
	
	if(!($year > 0))
		$year = (int)date('Y');
	$return['year'] = $year;
	
	if(!($week_number > 0))
		$week_number = (int)date('W');
	
	if($week_number > 52)
		$week_number = 1;
	
	$return['week_number'] = $week_number;
	
	
	$date = new DateTime('monday this week');
	
	if($year > 0 && $week_number > 0)
		$date->setISODate($year, $week_number);
	
	$return['monday'] = $date->format('d-m-Y'); 
	
	global $db;
	
	for($i = 1; $i <= 7; $i++)
	{
		$return['appointments'][$i] = array();
		$return['dates'][$i] = $date->format('d-m-Y');
		$return['highest_slot'][$i] = 0;
		
		$params = array(
			'clients' => array(
				'conditions' => array(
					'appointment' => $date->format('Y-m-d'),
					'archived' => '0000-00-00 00:00:00'
				),
				'order' => 'appointment_slot'
			)
		);
		$clients = $db->select($params);
		if($clients)
		{
			//$return['appointments'][$i] = $clients;
			foreach($clients as $client)
			{
				$params = array(
					'documents' => array(
						'conditions' => array(
							'archived' => '0000-00-00 00:00:00',
							'(`client_id` = ' . (int)$client['Client']['id'] . ')',
							'type' => 'nestor'
						)
					)
				);
				$nestors = $db->select($params);
				$params = array(
					'documents' => array(
						'conditions' => array(
							'archived' => '0000-00-00 00:00:00',
							'client_id' => (int)$client['Client']['id'],
							'type' => 'dgt'
						)
					)
				);
				$dgt_documents = $db->select($params);
				
				$return['appointments'][$i][] = array(
					'Client' => $client['Client'],
					'has_nestor' => ($nestors !== false && $nestors !== null && count($nestors) > 0),
					'has_dgt' => ($dgt_documents !== false && $dgt_documents !== null && count($dgt_documents) > 0),
				);
			}
			
			foreach($clients as $client)
			{
				if($client['Client']['appointment_slot'] > $return['highest_slot'][$i])
					$return['highest_slot'][$i] = (int)$client['Client']['appointment_slot'];
			}
		}
		
		$date->modify('+1 day');
	}
	
	$date->modify('-1 day');
	$return['sunday'] = $date->format('d-m-Y');
	
	$return['succes'] = true;
	
	//$date = new DateTime();
	//$date->setISODate($year, $week_number);
	
	$return['notifications'] = array();
	for($i = 1; $i <= 7; $i++)
	{
		$params = array(
			'notifications' => array(
				'conditions' => array(
					'date' => $date->format('Y-m-d')
				),
				'order' => 'slot'
			)
		);
		$notifications = $db->select($params);
		
		if($notifications)
			$return['notifications'][$date->format('d-m-Y')] = $notifications;
		
		$date->modify('+1 day');
	}
	
	print(json_encode($return, JSON_PRETTY_PRINT));
}
?>