<?php

$worker['Worker'] = $userLoggedIn['Worker'];
$db->query("UPDATE `workers` SET `last_online` = '" . date('Y-m-d H:i:s') . "' WHERE `id` = " . $worker['Worker']['id'] . " LIMIT 1");

function __home()
{
	global $db, $controller;
	
	$day_of_the_week = strtolower(date('D'));
	$day_clients = array();
	
	$params = array(
		'clients' => array(
			'conditions' => array(
				'appointment = "' . date('Y-m-d') . '"',
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'appointment_slot'
		)
	);
	$clients = $db->select($params);
	if(count($clients) > 0)
	{
		foreach($clients as $client)
			$day_clients['vandaag'][(int)$client['Client']['appointment_slot']] = $client;
	}
	
	$params = array(
		'notifications' => array(
			'conditions' => array(
				'date = "' .  date('Y-m-d') . '"',
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'slot'
		)
	);
	$notifications = $db->select($params);
	if(count($notifications) > 0)
	{
		foreach($notifications as $notification)
		$day_clients['vandaag'][(int)$notification['Notification']['slot']] = $notification;
	}
	
	if(count($day_clients['vandaag']) > 0)
		ksort($day_clients['vandaag']);
	
	$params = array(
		'clients' => array(
			'conditions' => array(
				'appointment = "' . date('Y-m-d', strtotime('tomorrow')) . '"',
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'appointment_slot'
		)
	);
	$clients = $db->select($params);
	if(count($clients) > 0)
	{
		foreach($clients as $client)
			$day_clients['morgen'][(int)$client['Client']['appointment_slot']] = $client;
	}
	
	$params = array(
		'notifications' => array(
			'conditions' => array(
				'date = "' .  date('Y-m-d', strtotime('tomorrow')) . '"',
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'slot'
		)
	);
	$notifications = $db->select($params);
	if(count($notifications) > 0)
	{
		foreach($notifications as $notification)
			$day_clients['morgen'][(int)$notification['Notification']['slot']] = $notification;
	}
	
	if(count($day_clients['morgen']) > 0)
		ksort($day_clients['morgen']);
	
	$sunday = strtotime('sunday this week');
	
	if($day_of_the_week == 'sat' || $day_of_the_week == 'sun')
		$sunday += (7 * 24 * 60 * 60);
	
	$time = strtotime(date('Y-m-d', strtotime('tomorrow')));
	
	while($time < $sunday)
	{
		$time += (24 * 60 * 60);
		$array_date_index = day_of_week_eng_to_nl(date('D', $time));
		
		$params = array(
			'clients' => array(
				'conditions' => array(
					'appointment = "' . date('Y-m-d', $time) . '"',
					'archived' => '0000-00-00 00:00:00'
				),
				'order' => 'appointment_slot'
			)
		);
		$clients = $db->select($params);
		if(count($clients) > 0)
		{
			foreach($clients as $client)
				$day_clients[$array_date_index][(int)$client['Client']['appointment_slot']] = $client;
		}
		
		$params = array(
			'notifications' => array(
				'conditions' => array(
					'date = "' .  date('Y-m-d', $time) . '"',
					'archived' => '0000-00-00 00:00:00'
				),
				'order' => 'slot'
			)
		);
		$notifications = $db->select($params);
		if(count($notifications) > 0)
		{
			foreach($notifications as $notification)
				$day_clients[$array_date_index][(int)$notification['Notification']['slot']] = $notification;
		}
		
		if(count($day_clients[$array_date_index]) > 0)
			ksort($day_clients[$array_date_index]);
	}
	
	
	$home_date = session('home_date');
	set('home_date', $home_date);
	if(!is_null($home_date))
	{
		$day_clients = array();
		
		$sunday = strtotime('sunday this week', strtotime($home_date));
		$time = strtotime($home_date);
		
		while($time < $sunday)
		{
			$array_date_index = day_of_week_eng_to_nl(date('D', $time)) . ' ' . date('d-m-Y', $time);
			
			$params = array(
				'notifications' => array(
					'conditions' => array(
						'date = "' . date('Y-m-d', $time) . '"',
						'archived' => '0000-00-00 00:00:00'
					),
					'order' => 'slot'
				)
			);
			$notifications = $db->select($params);
			if(count($notifications) > 0)
			{
				foreach($notifications as $notification)
				$day_clients[$array_date_index][(int)$notification['Notification']['slot']] = $notification;
			}
			
			$params = array(
				'clients' => array(
					'conditions' => array(
						'appointment = "' . date('Y-m-d', $time) . '"',
						'archived' => '0000-00-00 00:00:00'
					),
					'order' => 'appointment_slot'
				)
			);
			$clients = $db->select($params);
			if(count($clients) > 0)
			{
				foreach($clients as $client)
				$day_clients[$array_date_index][$client['Client']['appointment_slot']] = $client;
			}
			
			if(count($day_clients[$array_date_index]) > 0)
				ksort($day_clients[$array_date_index]);
			
			$time += (24 * 60 * 60);
		}
	}
	
	if($controller['get']['admin'] == 1)
	{
		pr('admin');
		pr($day_clients);
	}
	
	
	$monday = $home_date;
	if(is_null($monday))
		$monday = date('Y-m-d', strtotime('monday this week'));
	$monday = date('Y-m-d', strtotime('this week', strtotime($monday)));
	set('monday', $monday);
	
	set('day_clients', $day_clients);
	set('day_notifications', $day_notifications);
}

function day_of_week_eng_to_nl($day_of_week_eng)
{
	$day_of_week_eng = strtolower($day_of_week_eng);
	$nl = array(
		'mon' => 'maandag',
		'tue' => 'dinsdag',
		'wed' => 'woensdag',
		'thu' => 'donderdag',
		'fri' => 'vrijdag',
		'sat' => 'zaterdag',
		'sun' => 'zondag'
	);
	
	return $nl[$day_of_week_eng];
}
function _pd($date = '', $plus = 0)
{
	return date('Y-m-d', (strtotime($date) + ($plus * 24 * 60 * 60) + 43200));
}

function reset_home_date()
{
	global $controller;
	$controller['view'] = 'fake';
	$controller['layout'] = null;
	
	pr(session('home_date'));
	destroy_session('home_date');
	pr(session('home_date'));
	//redirect('/');
}

?>