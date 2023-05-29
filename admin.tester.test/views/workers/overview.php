<h1>Werkers</h1>
<h5>Werklui met toegang tot de app</h5>

<div class="page_actions">
	<a class="btn" href="/workers/add"><span class="fas fa-plus-square"></span>Nieuwe werker toevoegen</a>
	<a class="btn" href="/workers/crews"><span class="fas fa-users"></span>Ploegen</a>
</div>

<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th>Naam</th>
		<th>Ploeg</th>
		<th>Aangemaakt op</th>
		<th width="100">&nbsp;</th>
	</tr>
    <?php
		foreach($workers as $worker)
		{
	?>
	<tr>
		<td class="check"><input type="checkbox" id="<?=$worker['Worker']['id']?>" /></td>
		<td><a href="/workers/edit/<?=$worker['Worker']['id']?>"><?=$worker['Worker']['name']?></a></td>
		<td><?=$worker['Crew']['name']?></td>
		<td><?=date('d-m-Y', strtotime($worker['Worker']['created']))?></td>
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
		<option value="replicate">Kopi&euml;ren</option>
		<option value="remove">Verwijderen</option>
	</select>
</div>

<script>
	function checkbox_action(action)
	{
		if(action == 'remove')
		{
			var worker_ids = (get_all_checked_ids());
			popup('<h3>VERWIJDEREN</h3>Wil je ' + worker_ids.length + ' rijen verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');
			
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
					url: '/ajax/workers/remove',
					type: 'post',
					data: {worker_ids:worker_ids},
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
		if(action == 'replicate')
		{
			var worker_ids = (get_all_checked_ids());
			popup('<h3>Kopi&euml;ren</h3>Wil je ' + worker_ids.length + ' rijen kopi&euml;ren?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-accept popup_yes" href="/"><span class="fas fa-check-circle"></span>Kopi&euml;ren</a>');
			
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
					url: '/ajax/workers/replicate',
					type: 'post',
					data: {worker_ids:worker_ids},
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