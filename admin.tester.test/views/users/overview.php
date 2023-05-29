<h1>Administratieve Gebruikers</h1>
<h5>Gebruikers van het Back-end beheren</h5>

<div class="page_actions">
	<a class="btn" href="/users/add"><span class="fas fa-plus-square"></span>Nieuwe Gebruiker toevoegen</a>
    <?php
		if(permissions('master'))
		{
	?>
	<a class="btn" href="/users/profiles/profile_overview"><span class="fas fa-user"></span>Gebruikersprofielen</a>
            <?php
		}
	?>
</div>

<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th>Gebruikersnaam</th>
		<th>Naam</th>
		<th>Rechten</th>
		<th>Aangemaakt op</th>
		<th width="100">&nbsp;</th>
	</tr>
    <?php
		foreach($users as $user)
		{
	?>
	<tr>
		<td class="check"><input type="checkbox" id="<?=$user['User']['id']?>" /></td>
		<td><a href="/users/details/<?=$user['User']['id']?>"><?=$user['User']['username']?></a></td>
		<td><?=$user['User']['name']?></td>
		<td><?=$user['User']['permissions']?></td>
		<!--<td><?=(strtotime($user['User']['last_online']) > 0 ? date('d-m-Y H:i:s', strtotime($user['User']['last_online'])) : '-')?></td>-->
		<td><?=date('d-m-Y', strtotime($user['User']['created']))?></td>
		<td>&nbsp;</td>
	</tr>
            <?php
		}
	?>
</table>

<div class="checkboxes_checked">
	<div class="number_of_checkboxes_checked"></div>
	<select class="checkbox_actions">
		<option value="0">Selecteer een actie</option>
		<option value="remove">Verwijderen</option>
	</select>
</div>

<script>
	function checkbox_action(action)
	{
		if(action == 'remove')
		{
			var user_ids = (get_all_checked_ids());
			popup('<h3>VERWIJDEREN</h3>Wil je ' + user_ids.length + ' rijen verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');
			
			$('.popup_no').click(function()
			{
				close_popup();
				reset_actions_selector();
				return false;
			});
			
			$('.popup_yes').click(function()
			{
				$.ajax(
				{
					url: '/ajax/users/remove',
					type: 'post',
					data: {user_ids:user_ids},
					dataType: 'json',
					success: function(response)
					{
						console.log(response);
						if(response['succes'])
							window.location.reload(true);
					},
					error: function(response)
					{
						console.error(response);
					}
				});
				close_popup();
				reset_actions_selector();
				return false;
			});
			
		}
	}
</script>