<?php

function overview()
{
	global $controller, $db, $userLoggedIn;
	
	$monday = $controller['get']['date'];
	
	if(empty($monday))
		$monday = date('Y-m-d', strtotime('monday this week'));
	
	$monday = date('Y-m-d', strtotime('this week', strtotime($monday)));
	set('monday', $monday);
	
	$week_number = date('W', strtotime($monday));
	set('week_number', $week_number);
	
	$params = array(
		'hours' => array(
			'conditions' => array(
				'(`date` >= "' . $monday . '" AND `date` <= "' . _pd($monday, 6) . '")',
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => '`date`, worker_id'
		)
	);
	$hours = $db->select($params);
	set('hours', $hours);
	
	$parsed_hours = array();
	foreach($hours as $hour)
	{
		$parsed_hours[$hour['Hour']['worker_id']]['normal'][$hour['Hour']['date']] = $hour['Hour']['hours'];
	}
	
	$params = array(
		'bonus_hours' => array(
			'conditions' => array(
				'(`date` >= "' . $monday . '" AND `date` <= "' . _pd($monday, 6) . '")',
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => '`date`, worker_id'
		)
	);
	$bonus_hours = $db->select($params);
	set('bonus_hours', $bonus_hours);
	
	foreach($bonus_hours as $bonus_hour)
	{
		$parsed_hours[$bonus_hour['Bonus_hour']['worker_id']]['bonus'][$bonus_hour['Bonus_hour']['date']] = $bonus_hour['Bonus_hour']['hours'];
	}
	
	set('parsed_hours', $parsed_hours);
}

function details($worker_id = 0)
{
	$worker_id = (int)$worker_id;
	
	if($worker_id > 0)
	{
		global $db;
		
		$worker = $db->first('workers', $worker_id);
		if($worker)
		{
			$params = array(
				'bonus_transactions' => array(
					'conditions' => array(
						'worker_id' => $worker_id,
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
						'worker_id' => $worker_id
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
			
			set('worker', $worker);
			set('starting_balance', $starting_balance);
			set('bonus_hours', $bonus_hours);
			set('bonus_transaction', $bonus_transaction);
		}
	}
}

function _pd($date = '', $plus = 0)
{
	return date('Y-m-d', (strtotime($date) + ($plus * 24 * 60 * 60) + 43200));
}

function _pn($number)
{
	if($number == 0)
		return '&nbsp;';
	
	//if(number_format($number, 2, '.', '') != $number)
	//	return number_format($number, 2, '.', '');
	
	return $number / 1;
}

function get_month_by_int($int = 0)
{
	$int = (int)$int;
	if(!($int >= 1 && $int <= 12))
		$int = 1;
	
	$months = array(
		'Januari',
		'Februari',
		'Maart',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Augustus',
		'September',
		'Oktober',
		'November',
		'December'
	);
	return $months[$int-1];
}

function get_date_formatted($date = '')
{
	return date('d-m-Y', strtotime($date));
}

?>