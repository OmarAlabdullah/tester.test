<h1>Mail Templates</h1>

<div class="page_actions">
	<a class="btn" href="/mail_templates/add"><span class="fas fa-plus-square"></span>Nieuw template toevoegen</a>
</div>

<?php
	if(count($mail_templates) > 0)
	{
?>

<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th>Naam</th>
		<th>Aangemaakt op</th>
		<th width="100">&nbsp;</th>
	</tr>
    <?php
		foreach($mail_templates as $mail_template)
		{
	?>
	<tr>
		<td class="check"><input type="checkbox" id="<?=$mail_template['Mail_template']['id']?>" /></td>
		<td><a href="/mail_templates/details/<?=$mail_template['Mail_template']['id']?>"><?=$mail_template['Mail_template']['name']?></a> <?=($mail_template['Mail_template']['default'] == 1 ? '(standaard)' : '')?></td>
		<td><?=date('d-m-Y', strtotime($mail_template['Mail_template']['created']))?></td>
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
			var mail_template_ids = (get_all_checked_ids());
			popup('<h3>VERWIJDEREN</h3>Wil je ' + mail_template_ids.length + ' rijen verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');
			
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
					url: '/ajax/mail_templates/remove',
					type: 'post',
					data: {mail_template_ids:mail_template_ids},
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
			var mail_template_ids = (get_all_checked_ids());
			popup('<h3>Kopi&euml;ren</h3>Wil je ' + mail_template_ids.length + ' rijen kopi&euml;ren?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-accept popup_yes" href="/"><span class="fas fa-check-circle"></span>Ja</a>');
			
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
					url: '/ajax/mail_templates/duplicate',
					type: 'post',
					data: {mail_template_ids:mail_template_ids},
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

        <?php
	}else
	{
?>
<div class="info_bar">
	Er zijn nog geen mail templates
</div>
        <?php
	}
?>