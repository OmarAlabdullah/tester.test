<h1>Gebruiker <?=$user['User']['username']?></h1>
<h5><?=$user['User']['name']?></h5>

<div class="page_actions">
	<a class="btn" href="/users/overview"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
	<?php
		if(permissions('master') || $user['User']['id'] == $userLoggedIn['User']['id'])
		{
	?>
	<a class="btn change_password" href="<?=SELF?>"><span class="fas fa-lock"></span>Wachtwoord wijzigen</a>
	<?php
		}
	?>
</div>

<?php
	if(permissions('master'))
	{
?>

<div class="info_bar">
	<?=$_SERVER['HTTP_USER_AGENT']?>
</div>

        <?php
	}
?>

<div class="block">
	<?=$user['User']['name']?>
	<br />
	<?=$user['User']['username']?>
	<br />
	<?=$user['User']['email']?>
	<br />
	<?=$user['User']['permissions']?>
	<br />
	<br />
	Aangemaakt: <?=date('d-m-Y H:i:s', strtotime($user['User']['created']))?>
	<br />
	<br />
	<form method="post" action="<?=SELF?>">
	<b>
        <?php
	$user['User']['preferences'] = string_to_array($user['User']['preferences']);
	foreach($user['User']['preferences'] as $preference_name => $preference_value)
	{
?>
	<?=$preference_name?>: <input type="text" name="preferences[<?=$preference_name?>]" value="<?=$preference_value?>" />
	<br />
        <?php
	}
?>
	</b>
        <?php
		if(count($user['User']['preferences']) > 0)
		{
	?>
	<br />
	<button type="submit">Opslaan</button>
            <?php
		}
	?>
	</form>
	<br />
    <?php
	if(permissions('master'))
	{
?>
	Laats actief: <?=date('d-m-Y H:i:s', strtotime($user['User']['last_online']))?>
	<br />
        <?php
	}
?>
</div>

<?php
	if(permissions('master'))
	{
?>
<h2>Laatste logs</h2>

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
        <?php
	}
?>

<script>
$(document).ready(function()
{
	$('.change_password').click(function()
	{
		popup('<h3>Wachtwoord wijzigen</h3>Nieuw wachtwoord<br /><input type="password" class="new_password" /><br /><br /><br /><a href="<?=SELF?>" class="btn popup_back"><span class="fas fa-arrow-alt-circle-left"></span>Terug</a> &nbsp; <a href="<?=SELF?>" class="btn btn-accept popup_save"><span class="fas fa-lock"></span>Wachtwoord wijzigen</a>');
		$('.popup_back').click(function()
		{
			close_popup();
			return false;
		});
		$('.popup_save').click(function()
		{
			var new_password = $('.new_password:visible').first().val();
			if(new_password.length > 0)
			{
				var fd = new FormData();
				fd.append('user_id', parseInt(<?=$user['User']['id']?>));
				fd.append('hash1', '(<?=md5('hash' . time() . '7fsdtye3')?>)');
				fd.append('hash2', '(<?=md5(round(time() / 120) . SALT)?>)');
				fd.append('hash3', '(<?=md5('__i' . time() . '7462')?>)');
				fd.append('hash4', '(<?=md5('1734' . round(time() / 60) . '87df')?>)');
				fd.append('new_password', new_password);
				
				
				$.ajax(
				{
					url: '/ajax/users/change_password',
					type: 'post',
					dataType: 'json',
					data: fd,
					contentType: false,
					processData: false,
					success: function(response)
					{
						console.log(response);
						close_popup();
					}
				});
			}
			return false;
		});
		
		return false;
	});
});
</script>