<?php global $controller; ?>
<div class="tabs">
	<a class="selected" href="/project_lists/details/<?=$project_list['Project_list']['id']?>">
		<span class="far fa-address-card"></span>
		<br />
		Adressen
	</a>
	<a href="/project_lists/settings/<?=$project_list['Project_list']['id']?>">
		<span class="fas fa-cog"></span>
		<br />
		Instellingen
	</a>
	<a href="/project_lists/documents/<?=$project_list['Project_list']['id']?>">
		<span class="fas fa-file-alt"></span>
		<br />
		Documenten
	</a>
</div>

<h1><?=$project_list['Project_list']['name']?></h1>
<h5>Aangemaakt op <?=date('d-m-Y', strtotime($project_list['Project_list']['created']))?></h5>

<div class="page_actions">
	<a class="btn" href="/project_lists/overview"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
	<a class="btn" href="/clients/add/<?=$project_list['Project_list']['id']?>"><span class="fas fa-plus-square"></span>Adres aanmaken</a>
	<a class="btn" href="/project_lists/add_import/<?=$project_list['Project_list']['id']?>"><span class="fas fa-cloud-upload-alt"></span>Nieuwe gegevens importeren</a>
</div>

<div class="page_actions">
	
	<input class="search" type="text" name="q" value="<?=$controller['get']['q']?>" placeholder="Zoek op naam, adres" autocomplete="off" />
	<?=(!empty($controller['get']['q']) ? '<a class="btn_clean close_search" href="' . SELF . '"><span class="fas fa-times"></span></a>' : '')?>
	
	<a class="btn add_filter <?=($controller['get']['filter'] == 'no_letter_sent' ? 'remove_filter' : '')?>" rel="no_letter_sent" href="<?=SELF?>"><span class="fas fa-filter"></span>Geen brief verstuurd</a>
	<a class="btn add_filter <?=($controller['get']['filter'] == 'no_contact_details' ? 'remove_filter' : '')?>" rel="no_contact_details" href="<?=SELF?>"><span class="fas fa-filter"></span>Zonder contact gegevens</a>
	<a class="btn add_filter <?=($controller['get']['filter'] == 'to_plan' ? 'remove_filter' : '')?>" rel="to_plan" href="<?=SELF?>"><span class="fas fa-filter"></span>Nog in te plannen</a>
	<a class="btn add_filter <?=($controller['get']['filter'] == 'unexecuted' ? 'remove_filter' : '')?>" rel="unexecuted" href="<?=SELF?>"><span class="fas fa-filter"></span>Nog niet uitgevoerd</a>
	
</div>

<?=(!empty($controller['get']['q']) ? '<h3>' . count($clients) . ' resultaten voor: `' . $controller['get']['q'] . '`</h3>' : '')?>

<?php
	if(count($clients) > 0)
	{
?>
<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th>Postcode</th>
		<th>Straat</th>
		<th>Huisnummer</th>
		<th>Bijzonderheden</th>
		<th>Brief verstuurd</th>
		<th width="100">&nbsp;</th>
	</tr>
    <?php
		foreach($clients as $i => $client)
		{
			$send_letter = false;
			if($client['Client']['send_letter_3'] != null && $client['Client']['send_letter_3'] != '0000-00-00 00:00:00')
				$send_letter = strtotime($client['Client']['send_letter_3']);
			elseif($client['Client']['send_letter_2'] != null && $client['Client']['send_letter_2'] != '0000-00-00 00:00:00')
				$send_letter = strtotime($client['Client']['send_letter_2']);
			elseif($client['Client']['send_letter_1'] != null && $client['Client']['send_letter_1'] != '0000-00-00 00:00:00')
				$send_letter = strtotime($client['Client']['send_letter_1']);
				
			$client_status = 0;
			
			if($send_letter)
				$client_status = 1;
			
			if(!(empty($client['Client']['email']) && empty($client['Client']['phone'])))
			{
				$client_status = 2;
			}
			if($client['Client']['appointment'] != '0000-00-00')
			{
				$client_status = 3;
			}
			if($client['Client']['finished'] == 1 || $client['Client']['force_finished'] == 1)
			{
				$client_status = 4;
			}
	?>
	<tr class="pagination_element client client_status_<?=$client_status?> <?=($client['Client']['not_remediated'] == 1 ? 'not_remediated' : '')?>" id="client_<?=$client['Client']['id']?>" rel="<?=ceil(($i+1) / $page_size)?>" <?=($i >= $page_size ? 'style="display: none; "' : '')?>>
		<td class="check"><input type="checkbox" id="<?=$client['Client']['id']?>" /></td>
		<td><?=$client['Client']['zipcode']?></td>
		<td><?=$client['Client']['street']?></td>
		<td><?=$client['Client']['homenumber']?> <?=strtoupper($client['Client']['addition'])?></td>
		<td><?=$client['Client']['remarks']?></td>
		<td>
            <?php
				if($send_letter)
				{
					$days = floor((time() - $send_letter) / (24 * 60 * 60));
					
					if($days == 1)
					{
			?>
			<?=$days?> dag geleden
                        <?php
					}elseif($days > 1)
					{
			?>
			<?=$days?> dagen geleden
                        <?php
					}else
					{
			?>
			vandaag
                        <?php
					}
				}
			?>
		</td>

        <?php
			switch($client_status)
			{
				case 1:
					$status_title = 'Nog geen contactgegevens';
				break;
				case 2:
					$status_title = 'Nog geen afspraak ingepland';
				break;
				case 3:
					$status_title = 'Nog niet uitgevoerd';
				break;
				case 4:
					$status_title = 'Afgerond';
				break;
				default:
					$status_title = 'Nog geen brief verstuurd';
				break;
			}
			if($client['Client']['not_remediated'] == 1)
				$status_title .= ' / Niet gesaneerd';
		?>
		
		<td title="<?=$status_title?>">
            <?php
				switch($client_status)
				{
					case 1:
			?>
				<span class="fas fa-square fas-green"></span>
				<span class="fas fa-square fas-grey"></span>
				<span class="fas fa-square fas-grey"></span>
				<span class="fas fa-square fas-grey"></span>
                        <?php
					break;
					case 2:
			?>
				<span class="fas fa-square fas-green"></span>
				<span class="fas fa-square fas-green"></span>
				<span class="fas fa-square fas-grey"></span>
				<span class="fas fa-square fas-grey"></span>
                        <?php
					break;
					case 3:
			?>
				<span class="fas fa-square fas-green"></span>
				<span class="fas fa-square fas-green"></span>
				<span class="fas fa-square fas-green"></span>
				<span class="fas fa-square fas-grey"></span>
                        <?php
					break;
					case 4:
			?>
				<span class="fas fa-square fas-green"></span>
				<span class="fas fa-square fas-green"></span>
				<span class="fas fa-square fas-green"></span>
				<span class="fas fa-square fas-green"></span>
                        <?php
					break;
					default:
			?>
				<span class="fas fa-square fas-grey"></span>
				<span class="fas fa-square fas-grey"></span>
				<span class="fas fa-square fas-grey"></span>
				<span class="fas fa-square fas-grey"></span>
                        <?php
					break;
				}
				if($client['Client']['not_remediated'] == 1)
				{
			?>
				<span class="fas fa-square fas-red"></span>
			<?php
				}
			?>
		</td>
	</tr>
            <?php
		}
	?>
</table>

<div class="checkboxes_checked">
	<div class="number_of_checkboxes_checked"></div>
	<select class="checkbox_actions">
		<option value="0">Selecteer een actie</option>
		<option value="export">Exporteer</option>
		<option value="send_letter">Brief verstuurd</option>
		<option value="replace">Verplaatsen</option>
		<option value="remove">Verwijderen</option>
	</select>
</div>

<br /><br />

<div class="pagination">
	<a href="<?=SELF?>" rel="prev"></a>
    <?php
		$number_of_pages = ceil(count($clients) / $page_size);
		$current_page = 1;
		
		$show_only = false;
		if($number_of_pages > 20)
			$show_only = 10;
		for($i = 1; $i <= $number_of_pages; $i++)
		{
	?>
	<a href="<?=SELF?>" rel="<?=$i?>" class="<?=($current_page == $i ? 'selected' : '')?>" style="<?=((!$show_only || ($i <= $show_only || $i > $number_of_pages - $show_only)) ? '' : 'display: none; ')?>"><?=$i?></a>
            <?php
			if($i == $show_only && $show_only !== false)
			{
	?>
	<a href="<?=SELF?>">...</a>
                <?php
			}
		}
	?>
	<a href="<?=SELF?>" rel="next"></a>
</div>

        <?php
}else
{
?>
<div class="info_bar">
	Geen addressen
</div>
    <?php
}
?>

<script>
	function checkbox_action(action)
	{
		if(action == 'export')
		{
			export_selected();
			
			var client_ids = (get_all_checked_ids());
			
			popup('<h3>EXPORT</h3>Wil je deze ' + client_ids.length + ' rijen updaten naar brief verstuurd?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-accept popup_yes" href="/"><span class="fas fa-check-circle"></span>Ja</a>');
			
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
					url: '/ajax/clients/set_send_letter',
					type: 'post',
					data: {client_ids:client_ids},
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
		if(action == 'send_letter')
		{
			var client_ids = (get_all_checked_ids());
			
			popup('<h3>Brief verstuurd</h3>Wil je deze ' + client_ids.length + ' rijen updaten naar brief verstuurd?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-accept popup_yes" href="/"><span class="fas fa-check-circle"></span>Ja</a>');
			
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
					url: '/ajax/clients/set_send_letter',
					type: 'post',
					data: {client_ids:client_ids},
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
		if(action == 'remove')
		{
			var client_ids = (get_all_checked_ids());
			popup('<h3>VERWIJDEREN</h3>Wil je ' + client_ids.length + ' rijen verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');
			
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
					url: '/ajax/clients/remove',
					type: 'post',
					data: {client_ids:client_ids},
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
		if(action == 'replace')
		{
            <?php
				$this_project_list_id = $project_list['Project_list']['id'];
				$project_lists_string = '';
				foreach($project_lists as $_project_list)
				{
					if($_project_list['Project_list']['id'] != $this_project_list_id)
						$project_lists_string .= '<option value="' . $_project_list['Project_list']['id'] . '">' . str_replace('\'', '', str_replace('"', '', $_project_list['Project_list']['name'])) . '</option>';
				}
			?>
			var client_ids = (get_all_checked_ids());
			popup('<h3>Verplaatsen</h3>Wil je ' + client_ids.length + ' rijen verplaatsen?<br /><br /><select id="project_lists"><?=$project_lists_string?></select><br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Terug</a> <a class="btn btn-accept popup_yes" href="/"><span class="fas fa-check-circle"></span>Verplaats</a>');
			
			$('.popup_no').click(function()
			{
				close_popup();
				reset_actions_selector();
				return false;
			});
			
			$('.popup_yes').click(function()
			{
				var project_list_id = $('#project_lists').val();
				
				$.ajax(
				{
					url: '/ajax/clients/replace',
					type: 'post',
					data: {client_ids:client_ids, project_list_id:project_list_id},
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
	function export_selected()
	{
		reset_actions_selector();
		var client_ids = (get_all_checked_ids());
		if(client_ids.length > 0)
		{
			$.ajax(
			{
				url: '/ajax/clients/export',
				type: 'post',
				data: {client_ids:client_ids},
				dataType: 'json',
				success: function(response)
				{
					var csvContent = "data:text/csv;charset=utf-8,";
					for(r in response['rows'])
						csvContent += response['rows'][r] + '\r\n';
					var encodedUri = encodeURI(csvContent);
					
					var hiddenElement = document.createElement('a');
			    hiddenElement.href = encodedUri;
			    hiddenElement.target = '_blank';
			    hiddenElement.download = 'export-<?=str_replace('\'', '', str_replace('"', '', $project_list['Project_list']['name']))?>.csv';
			    hiddenElement.click();
				},
				error: function(response)
				{
					console.error(response);
				}
			});
		}
	}
	
</script>

<style>
tr.client td
{
	border-bottom: #cccccc solid 1px;
}
.client_status_1 td
{
	
}
.client_status_2 td
{
	background-color: rgba(5, 181, 239, 0.25) !important;
}
.client_status_3 td
{
	background-color: rgba(255, 137, 6, 0.25) !important;
}
.client_status_4 td
{
	background-color: rgba(190, 243, 141, 0.5) !important;
}
.not_remediated td
{
	background-color: rgba(255, 0, 0, 0.25) !important;
}
</style>