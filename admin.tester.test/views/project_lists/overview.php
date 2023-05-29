<?php global $controller; ?>
<h1>Projecten</h1>

<div class="page_actions">
	<a class="btn" href="/project_lists/import"><span class="fas fa-plus-square"></span>Nieuw project toevoegen</a>

	<br /><br />

	<a class="btn <?=($controller['get']['filter'] == 'all' ? 'remove_filter btn-accept' : '')?>" href="/project_lists/overview?filter=all"><span class="fas fa-filter"></span>Alle projecten</a>
	<a class="btn <?=($controller['get']['filter'] == '' ? 'remove_filter btn-accept' : '')?>" href="/project_lists/overview"><span class="fas fa-filter"></span>Openstaand</a>
	<a class="btn <?=($controller['get']['filter'] == 'finished' ? 'remove_filter btn-accept' : '')?>" href="/project_lists/overview?filter=finished"><span class="fas fa-filter"></span>Afgerond</a>
	<a class="btn <?=($controller['get']['filter'] == 'special' ? 'remove_filter btn-accept' : '')?>" href="/project_lists/overview?filter=special"><span class="fas fa-filter"></span>Bijzonderheden</a>

</div>

<div class="clear"></div>

<br /><br />

<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th width="150">Projectnummer</th>
		<th>Naam</th>
		<th>Ploeg(en)</th>
		<th>Aantal addressen</th>
		<th>Contactgegevens</th>
		<th>Nog uit te voeren</th>
		<th>Aangemaakt op</th>
		<th width="100">&nbsp;</th>
	</tr>
	<?php
		foreach($project_lists as $project_list)
		{
	?>
	<tr>
		<td class="check"><input type="checkbox" id="<?=$project_list['Project_list']['id']?>" /></td>
		<td><?=(empty($project_list['Project_list']['project_number']) ? '&nbsp;' : $project_list['Project_list']['project_number'])?></td>
		<td><a href="/project_lists/details/<?=$project_list['Project_list']['id']?>"><?=$project_list['Project_list']['name']?></a></td>
		<td><?=$project_list['crews_string']?></td>
		<td><?=count($project_list['Clients'])?></td>
		<td><?=$project_list['has_contact_information']?> (<?=(count($project_list['Clients']) - $project_list['has_contact_information'])?>)</td>
		<td><?=$project_list['nog_uit_te_voeren']?></td>
		<td><?=date('d-m-Y', strtotime($project_list['Project_list']['created']))?></td>
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
		<option value="copy">Kopi&euml;ren</option>
		<option value="remove">Verwijderen</option>
	</select>
</div>

<script>
	function checkbox_action(action)
	{
		if(action == 'remove')
		{
			var project_list_ids = (get_all_checked_ids());
			popup('<h3>VERWIJDEREN</h3>Wil je ' + project_list_ids.length + ' lijsten verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');

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
					url: '/ajax/project_lists/remove',
					type: 'post',
					data: {project_list_ids:project_list_ids},
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
		if(action == 'copy')
		{
			var project_list_ids = (get_all_checked_ids());
			popup('<h3>Kopi&euml;ren</h3>Wil je ' + project_list_ids.length + ' lijsten Kopi&euml;ren?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-accept popup_yes" href="/"><span class="fas fa-check-circle"></span>Ja</a>');

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
					url: '/ajax/project_lists/duplicate',
					type: 'post',
					data: {project_list_ids:project_list_ids},
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
