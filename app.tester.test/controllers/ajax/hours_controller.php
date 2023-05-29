<?php

function save_hours()
{
	global $controller, $userLoggedIn, $db;
	
	$return = array(
		'succes' => false,
		'can_edit' => false
	);
	
	$monday = date('Y-m-d', strtotime('this week', strtotime(array_key_first($controller['post']['normal']))));
	$return['monday'] = $monday;
	$return['ago'] = ceil((time() - strtotime($monday)) / (24*60*60));
	
	$return['post'] = $controller['post'];
	
	$log = array(
		'Log' => array(
			'origin' => 'save_hours',
			'log' => '[' . $userLoggedIn['Worker']['id'] . '][' . $monday . '] ' . json_encode($controller['post']),
			'created' => date('Y-m-d H:i:s')
		)
	);
	$db->insert($log);
	
	if(($return['ago'] <= 30 && $return['ago'] >= -7) || true)
	{
		$return['can_edit'] = true;
		foreach($controller['post']['normal'] as $date => $hours)
		{
			$hours = (float)$hours;
			$hours = round($hours * 4) / 4;
			if($hours > 24)
				$hours = 24;
			$return[$date] = $hours;
			
			$db->connect();
			//$db->query("DELETE FROM `hours` WHERE `worker_id` = " . $userLoggedIn['Worker']['id'] . " AND `date` = '" . $date . "' LIMIT 1");
			$db->query("UPDATE `hours` SET `archived` = '" . date('Y-m-d H:i:s') . "' WHERE `worker_id` = " . $userLoggedIn['Worker']['id'] . " AND `date` = '" . $date . "' AND `archived` = '0000-00-00 00:00:00'");
			
			if($hours > 0)
			{
				$hour = array(
					'Hour' => array(
						'worker_id' => $userLoggedIn['Worker']['id'],
						'date' => $date,
						'hours' => $hours
					)
				);
				$db->connect();
				$db->insert($hour);
			}
		}
		
		foreach($controller['post']['bonus'] as $date => $hours)
		{
			$hours = (float)$hours;
			$hours = round($hours * 4) / 4;
			if($hours > 24)
				$hours = 24;
			//$return[$date] = $hours;
			
			$db->connect();
			$db->query("DELETE FROM `bonus_hours` WHERE `worker_id` = " . $userLoggedIn['Worker']['id'] . " AND `date` = '" . $date . "' LIMIT 1");
			
			if($hours > 0)
			{
				$hour = array(
					'Bonus_hour' => array(
						'worker_id' => $userLoggedIn['Worker']['id'],
						'date' => $date,
						'hours' => $hours
					)
				);
				$db->connect();
				$db->insert($hour);
			}
		}
	}
	
	$params = array(
		'hours' => array(
			'conditions' => array(
				'worker_id' => $userLoggedIn['Worker']['id'],
				'(`date` >= "' . $monday . '" AND `date` <= "' . _pd($monday, 6) . '")',
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'date'
		)
	);
	$hours = $db->select($params);
	
	$parsed_hours = array();
	foreach($hours as $hour)
	{
		$parsed_hours[$hour['Hour']['date']] = $hour['Hour']['hours'];
		$parsed_hours[$hour['Hour']['date']] = $parsed_hours[$hour['Hour']['date']] / 1;
	}
	
	$return['parsed_hours'] = $parsed_hours;
	
	$params = array(
		'bonus_hours' => array(
			'conditions' => array(
				'worker_id' => $userLoggedIn['Worker']['id'],
				'(`date` >= "' . $monday . '" AND `date` <= "' . _pd($monday, 6) . '")',
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'date'
		)
	);
	$bonus_hours = $db->select($params);
	
	$parsed_bonus_hours = array();
	foreach($bonus_hours as $bonus_hour)
	{
		$parsed_bonus_hours[$bonus_hour['Bonus_hour']['date']] = $bonus_hour['Bonus_hour']['hours'];
		$parsed_bonus_hours[$bonus_hour['Bonus_hour']['date']] = $parsed_bonus_hours[$bonus_hour['Bonus_hour']['date']] / 1;
	}
	
	$return['parsed_bonus_hours'] = $parsed_bonus_hours;
	
	$current_bonus_hours = get_bonus_hours();
	$return['current_bonus_eur'] = number_format($current_bonus_hours['balance_eur'], 2, ',', '.');
	
	$return['succes'] = true;
	
	print(json_encode($return, JSON_PRETTY_PRINT));
}

function _pd($date = '', $plus = 0)
{
	return date('Y-m-d', (strtotime($date) + ($plus * 24 * 60 * 60) + 43200));
}

?>