<h1>Notitie</h1>
<h5>Aanpassen</h5>

<div class="page_actions">
	<a class="btn" href="/notifications/overview"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
	<a class="btn" href="/calendar/overview?year=<?=(date('Y', strtotime($notification['Notification']['date'])))?>&week_number=<?=(date('W', strtotime($notification['Notification']['date'])))?>"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar agenda</a>
	<a class="btn btn-alert remove_notification" href="<?=SELF?>"><span class="fas fa-times-circle"></span>Notitie verwijderen</a>
</div>

<form method="post" action="<?=SELF?>">
	
	Datum: <?=date('D d-m-Y', strtotime($notification['Notification']['date']))?><br /><br />
	
	
	Titel:<br />
	<input type="text" style="width: 400px; " name="Notification[title]" placeholder="Titel" value="<?=$notification['Notification']['title']?>" autocomplete="off" id="dsyun4r28w7hf4ri47hgwf3489rf">
	
	<br /><br />
	
	
	Inhoud:<br />
	<textarea name="Notification[content]" style="width: 400px; height: 200px; resize: none; padding: 10px; box-sizing: border-box; font-family: sans-serif; "><?=$notification['Notification']['content']?></textarea>
	
	<br /><br /><br />
	
	<i>Onderstaand wordt ingevult in de app (hier eventueel aan te passen)</i>
	
	<br /><br />
	
	Status:<br />
	<select name="Notification[status]">
		<option value="pending" <?=($notification['Notification']['status'] == 'pending' ? 'selected' : '')?>>Open</option>
		<option value="finished" <?=($notification['Notification']['status'] == 'finished' ? 'selected' : '')?>>Afgerond</option>
	</select>
	
	<br /><br />
	
	
	Opmerkingen:<br />
	<textarea name="Notification[remarks]" style="width: 400px; height: 200px; resize: none; padding: 10px; box-sizing: border-box; font-family: sans-serif; "><?=$notification['Notification']['remarks']?></textarea>
	
	<br /><br />
	
	<input type="submit" value="Opslaan" />
	
</form>

<script>
$(document).ready(function()
{
	$('.remove_notification').click(function()
	{
		popup('<h3>Wil je deze notitie verwijderen?</h3><br /><br /><a href="<?=SELF?>" class="btn popup_no"><span class="fas fa-arrow-alt-circle-left"></span>Terug</a> &nbsp; <a href="<?=SELF?>" class="btn btn-alert popup_remove"><span class="fas fa-times-circle"></span>Verwijderen</a>');
		
		$('.popup_no').click(function()
		{
			close_popup();
			return false;
		});
		$('.popup_remove').click(function()
		{
			var notification_id = parseInt('<?=$notification['Notification']['id']?>');
			if(notification_id > 0)
			{
				$.getJSON('/ajax/calendar/remove_notification/' + notification_id, function(response)
				{
					if(response['succes'])
						window.location.href = '/notifications/overview';
					else
						close_popup();
				});
			}
			return false;
		});
		
		return false;
	});
});
</script>