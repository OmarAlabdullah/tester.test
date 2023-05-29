<h1>Notities</h1>
<h5>Alle notities</h5>

<br /><br />

<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th>Titel</th>
		<th>Datum</th>
		<th>Inhoud</th>
		<th>Status</th>
		<th>Aangemaakt op</th>
		<th width="100">&nbsp;</th>
	</tr>
	<?php
		foreach($notifications as $notification)
		{
	?>
	<tr>
		<td class="check"><input type="checkbox" id="<?=$notification['Notification']['id']?>" /></td>
		<td><a href="/notifications/edit_notification/<?=$notification['Notification']['id']?>"><?=$notification['Notification']['title']?></a></td>
		<td><?=date('d-m-Y', strtotime($notification['Notification']['date']))?></td>
		<td><?=$notification['Notification']['content']?></td>
		<td>
			<?php
				switch($notification['Notification']['status'])
				{
					case 'pending':
						print('Open');
					break;
					case 'finished':
						print('Afgerond');
					break;
					default:
						print($notification['Notification']['status']);
					break;
				}
			?>
		</td>
		<td><?=date('d-m-Y', strtotime($notification['Notification']['created']))?></td>
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
			var notification_ids = (get_all_checked_ids());
			popup('<h3>VERWIJDEREN</h3>Wil je ' + notification_ids.length + ' rijen verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');

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
					url: '/ajax/calendar/remove_notifications',
					type: 'post',
					data: {notification_ids:notification_ids},
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
