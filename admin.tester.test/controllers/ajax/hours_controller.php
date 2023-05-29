<?php

function save_hour_data()
{
	global $controller, $db, $userLoggedIn;
	
	$return = array(
		'succes' => false
	);
	
	$return['post'] = $controller['post'];
	
	foreach($return['post']['save_que'] as $que_item)
	{
		$worker_id = (int)$que_item['worker_id'];
		$date = $que_item['date'];
		$hours = str_replace(',', '.', $que_item['value']);
		$hours *= 4;
		$hours = round($hours);
		$hours /= 4;
		
		$type = $que_item['type'];
		
		if($worker_id > 0 && strlen($date) == 10)
		{
			if($type == 'bonus')
			{
				$query = "UPDATE `bonus_hours` SET `archived` = '" . date('Y-m-d H:i:s') . "' WHERE `worker_id` = " . $worker_id . " AND `date` = '" . $date . "'";
				$return['querys'][] = $query;
				
				$db->query($query);
				
				if($hours > 0)
				{
					$bonus_hour = array(
						'Bonus_hour' => array(
							'worker_id' => $worker_id,
							'date' => $date,
							'hours' => $hours,
							'user_id' => $userLoggedIn['User']['id']
						)
					);
					$db->insert($bonus_hour);
				}
				
				$return['updates'][] = array(
					'type' => 'bonus',
					'worker_id' => $worker_id,
					'date' => $date,
					'value' => $hours
				);
			}else
			{
				$query = "UPDATE `hours` SET `archived` = '" . date('Y-m-d H:i:s') . "' WHERE `worker_id` = " . $worker_id . " AND `date` = '" . $date . "'";
				$return['querys'][] = $query;
				
				$db->query($query);
				
				if($hours > 0)
				{
					$hour = array(
						'Hour' => array(
							'worker_id' => $worker_id,
							'date' => $date,
							'hours' => $hours,
							'user_id' => $userLoggedIn['User']['id']
						)
					);
					$db->insert($hour);
				}
				
				$return['updates'][] = array(
					'type' => 'normal',
					'worker_id' => $worker_id,
					'date' => $date,
					'value' => $hours
				);
			}
			
			$return['succes'] = true;
		}
	}
	
	print(json_encode($return, JSON_PRETTY_PRINT));
}

function add_bonus_transaction()
{
	global $controller, $db, $userLoggedIn;
	
	$return = array(
		'succes' => false
	);
	
	$worker_id = (int)$controller['post']['worker_id'];
	$date = substr($controller['post']['date'], 0, 10);
	$balance = (float)$controller['post']['balance'];
	
	if($worker_id > 0 && strlen($controller['post']['date']) == 10)
	{
		$bonus_transaction = array(
			'Bonus_transaction' => array(
				'worker_id' => $worker_id,
				'date' => $date,
				'balance' => $balance,
				'created' => date('Y-m-d H:i:s')
			)
		);
		$insert_id = $db->insert($bonus_transaction);
		if($insert_id > 0)
			$return['succes'] = true;
	}
	
	print(json_encode($return, JSON_PRETTY_PRINT));
}

function remove_bonus_transaction($bonus_transaction_id = 0)
{
	$return = array(
		'succes' => false
	);
	
	$bonus_transaction_id = (int)$bonus_transaction_id;
	if($bonus_transaction_id > 0)
	{
		global $db;
		
		$bonus_transaction = $db->first('bonus_transactions', $bonus_transaction_id);
		if($bonus_transaction)
		{
			$db->query("DELETE FROM `bonus_transactions` WHERE `id` = " . $bonus_transaction_id . " LIMIT 1");
			
			$bonus_transaction = $db->first('bonus_transactions', $bonus_transaction_id);
			
			$return['succes'] = ($bonus_transaction === null);
		}
	}
	
	print(json_encode($return, JSON_PRETTY_PRINT));
}

?>