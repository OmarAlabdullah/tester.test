<?php
	
	$params = array(
		'user_logs' => array(
			'conditions' => array(
				'user_id' => 0
			),
			'order' => 'timestamp DESC',
			'limit' => 50
		)
	);
	$user_logs = $db->select($params);
	
?>


<h2>Logs</h2>

<table>
	<tr>
		<th>Tijdstip</th>
		<th>IP</th>
		<th>Actie</th>
	</tr>
    <?php
		foreach($user_logs as $user_log)
		{
	?>
	<tr>
		<td><?=date('d-m-Y H:i:s', strtotime($user_log['User_log']['timestamp']))?></td>
		<td><?=$user_log['User_log']['ip']?></td>
		<td><?=$user_log['User_log']['action']?></td>
	</tr>
            <?php
		}
	?>
</table>