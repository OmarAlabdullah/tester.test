<h1>Tijdvakken</h1>
<h5>Tijd blokken voor de agenda beheren</h5>

<div class="page_actions">
	<a class="btn" href="/calendar/add_timeframe"><span class="fas fa-plus-square"></span>Nieuwe tijdvak toevoegen</a>
</div>

<div id="notification_output"></div>

<table id="timeframes_table">
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th>Tijdvak</th>
		<th>Email tekst</th>
		<th>Aangemaakt op</th>
		<th width="100">&nbsp;</th>
	</tr>
	<tbody>
    <?php
		foreach($timeframes as $timeframe)
		{
	?>
	<tr timeframe_id="<?=$timeframe['Timeframe']['id']?>">
		<td class="check"><input type="checkbox" id="<?=$timeframe['Timeframe']['id']?>" /></td>
		<td><a href="/calendar/edit_timeframe/<?=$timeframe['Timeframe']['id']?>"><?=$timeframe['Timeframe']['timeframe']?></a></td>
		<td><?=$timeframe['Timeframe']['email_text']?></td>
		<td><?=date('d-m-Y', strtotime($timeframe['Timeframe']['created']))?></td>
		<td>&nbsp;</td>
	</tr>
            <?php
		}
	?>
</tbody>
</table>

<div class="checkboxes_checked">
	<div class="number_of_checkboxes_checked"></div>
	<select class="checkbox_actions">
		<option value="0">Selecteer een actie</option>
		<option value="remove">Verwijderen</option>
	</select>
</div>


<script>
	$(document).ready(function()
	{
		$('#timeframes_table tbody').sortable(
		{
			update:function(event, ui)
			{
				var sorted_array = [];
				$(this).find('tr').each(function()
				{
					var timeframe_id = parseInt($(this).find('input[type="checkbox"]').first().attr('id'));
					sorted_array.push(timeframe_id);
				});
				$.ajax(
				{
					url: '/ajax/calendar/sort_timeframes',
					data: {'sorted_ids' : sorted_array},
					type: 'post',
					dataType: 'json',
					success: function(response)
					{
						console.log(response);
						
						if(response['succes'])
						{
							
						}else
						{
							var jObj = $('<div class="info_bar">Er is iets fout gegaan met opslaan</div>');
							jObj.hide();
							jObj.appendTo($('#notification_output'));
							jObj.slideDown();
							setTimeout(function()
							{
								jObj.slideUp(function()
								{
									$(this).remove();
								});
							}, 3000);
						}
					},
					error: function(response)
					{
						console.error(response);
					}
				});
			}
		});
	});
	
	function checkbox_action(action)
	{
		if(action == 'remove')
		{
			var timeframes_ids = (get_all_checked_ids());
			popup('<h3>VERWIJDEREN</h3>Wil je ' + timeframes_ids.length + ' rijen verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');
			
			$('.popup_no').click(function()
			{
				close_popup();
				reset_actions_selector();
				return false;
			});
			
			$('.popup_yes').click(function()
			{
				remove_timeframes(timeframes_ids, false);
				close_popup();
				reset_actions_selector();
				return false;
			});
			
		}
	}
	
	function remove_timeframes(timeframes_ids, force)
	{
		$.ajax(
		{
			url: '/ajax/calendar/remove_timeframes',
			type: 'post',
			data: {timeframes_ids:timeframes_ids, force:force},
			dataType: 'json',
			success: function(response)
			{
				console.log(response);
				if(response['succes'])
				{
					if(response['number_of_clients'] > 0 && !force)
					{
						popup('<h3>VERWIJDEREN</h3>Er zijn ' + response['number_of_clients'] + ' addressen gekoppeld aan deze tijdsvakken, wilt je deze echt verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');
						
						$('.popup_no').click(function()
						{
							close_popup();
							return false;
						});
						
						$('.popup_yes').click(function()
						{
							remove_timeframes(timeframes_ids, true);
							close_popup();
							return false;
						});
					}else
					{
						for(i in timeframes_ids)
						{
							$('tr[timeframe_id="' + timeframes_ids[i] + '"]').slideUp(function()
							{
								$(this).remove();
								uncheck_all();
								checkbox_changed();
							});
						}
					}
				}
			},
			error: function(response)
			{
				console.error(response);
			}
		});
	}
</script>