<?php

function overview($monday = '')
{
	global $controller, $db, $userLoggedIn;
	
	if(empty($monday))
		$monday = date('Y-m-d', strtotime('monday this week'));
	
	$monday = date('Y-m-d', strtotime('this week', strtotime($monday)));
	set('monday', $monday);
	
	$week_number = date('W', strtotime($monday));
	set('week_number', $week_number);
	
	$params = array(
		'hours' => array(
			'conditions' => array(
				'worker_id' => $userLoggedIn['Worker']['id'],
				'(`date` >= "' . $monday . '" AND `date` <= "' . _pd($monday, 6) . '")',
				'archived' => '0000-00-00 00:00:00',
				'(`hours` != 0.00)'
			),
			'order' => 'date'
		)
	);
	$hours = $db->select($params);
	set('hours', $hours);
	
	$parsed_hours = array();
	foreach($hours as $hour)
	{
		$parsed_hours[$hour['Hour']['date']] = $hour['Hour']['hours'];
		$parsed_hours[$hour['Hour']['date']] = $parsed_hours[$hour['Hour']['date']] / 1;
	}
	set('parsed_hours', $parsed_hours);
	
	$params = array(
		'bonus_hours' => array(
			'conditions' => array(
				'worker_id' => $userLoggedIn['Worker']['id'],
				'(`date` >= "' . $monday . '" AND `date` <= "' . _pd($monday, 6) . '")',
				'archived' => '0000-00-00 00:00:00',
				'(`hours` != 0.00)'
			),
			'order' => 'date'
		)
	);
	$bonus_hours = $db->select($params);
	set('bonus_hours', $bonus_hours);
	
	$parsed_bonus_hours = array();
	foreach($bonus_hours as $bonus_hour)
	{
		$parsed_bonus_hours[$bonus_hour['Bonus_hour']['date']] = $bonus_hour['Bonus_hour']['hours'];
		$parsed_bonus_hours[$bonus_hour['Bonus_hour']['date']] = $parsed_bonus_hours[$bonus_hour['Bonus_hour']['date']] / 1;
	}
	set('parsed_bonus_hours', $parsed_bonus_hours);
	
	set('current_bonus_hours', get_bonus_hours());
	
	if(post())
	{
		$hours = $controller['post']['hours'];
		pr($hours);
	}
}

function bonus()
{
	global $db, $userLoggedIn;
	
	$params = array(
		'bonus_transactions' => array(
			'conditions' => array(
				'worker_id' => $userLoggedIn['Worker']['id'],
			),
			'order' => '`date` DESC',
			'select' => 'first'
		)
	);
	$bonus_transaction = $db->select($params);
	
	$params = array(
		'bonus_hours' => array(
			'conditions' => array(
				'archived' => '0000-00-00 00:00:00',
				'worker_id' => $userLoggedIn['Worker']['id']
			),
			'order' => '`date`'
		)
	);
	
	$starting_balance = 0.0;
	
	if($bonus_transaction)
	{
		$params['bonus_hours']['conditions'][] = '(`date` > "' . $bonus_transaction['Bonus_transaction']['date'] . '")';
		$starting_balance = (float)$bonus_transaction['Bonus_transaction']['balance'];
	}
	
	$bonus_hours = $db->select($params);
	
	set('starting_balance', $starting_balance);
	set('bonus_hours', $bonus_hours);
	set('bonus_transaction', $bonus_transaction);
	set('current_bonus_hours', get_bonus_hours());
}

function _pd($date = '', $plus = 0)
{
	return date('Y-m-d', (strtotime($date) + ($plus * 24 * 60 * 60) + 43200));
}
?>