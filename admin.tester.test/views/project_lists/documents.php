
<div class="tabs">
	<a href="/project_lists/details/<?=$project_list['Project_list']['id']?>">
		<span class="far fa-address-card"></span>
		<br />
		Adressen
	</a>
	<a href="/project_lists/settings/<?=$project_list['Project_list']['id']?>">
		<span class="fas fa-cog"></span>
		<br />
		Instellingen
	</a>
	<a class="selected" href="/project_lists/documents/<?=$project_list['Project_list']['id']?>">
		<span class="fas fa-file-alt"></span>
		<br />
		Documenten
	</a>
</div>

<h1>Documenten</h1>
<h5><?=$project_list['Project_list']['name']?></h5>

<div class="page_actions">
	<a class="btn" href="/project_lists/dgt_reports/<?=$project_list['Project_list']['id']?>"><span class="fas fa-cloud-upload-alt"></span>DGT rapporten uploaden</a>
	<a class="btn" href="/project_lists/sketches/<?=$project_list['Project_list']['id']?>"><span class="fas fa-cloud-upload-alt"></span>Schetsen uploaden</a>
	<a class="btn" href="/project_lists/nestor_reports/<?=$project_list['Project_list']['id']?>"><span class="fas fa-cloud-upload-alt"></span>Nestor formulieren uploaden</a>
</div>

<br /><br />

<?php
	if(count($dgt_documents) > 0)
	{
?>
<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th width="350">DGT Rapporten</th>
		<th>Bestandsnaam</th>
		<th width="200">Matched</th>
		<th width="200">Geimporteerd op</th>
	</tr>
	<?php
		foreach($dgt_documents as $document)
		{
			if($document['Document']['client_id'] > 0)
				$client = $db->first('clients', $document['Document']['client_id']);
	?>
	<tr>
		<td class="check"><input type="checkbox" id="<?=$document['Document']['id']?>" /></td>
		<td><?=$document['Document']['subtype']?></td>
		<td><a href="/assets/documents/dgt/<?=$document['Document']['project_list_id']?>/<?=$document['Document']['filename']?>" target="_blank"><?=$document['Document']['filename']?></a></td>
		<td><?=($document['Document']['client_id'] > 0 ? '<a href="/clients/details/' . $client['Client']['id'] . '">' . $client['Client']['zipcode'] . ' ' . $client['Client']['homenumber'] . strtoupper($client['Client']['addition']) . '</a> &nbsp; <span class="fas fa-pen-square set_dgt_zipcode_on_pdf" rel="' . $document['Document']['id'] . '"></span>' : 'nee')?></td>
		<td><?=date('d-m-Y H:i', strtotime($document['Document']['created']))?></td>
	</tr>
	<?php
		}
	?>
</table>
<br /><br />
<?php
	}
	if(count($sketches) > 0)
	{
?>
<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th width="350">Schetsen</th>
		<th>Bestandsnaam</th>
		<th width="200">Matched</th>
		<th width="200">Geimporteerd op</th>
	</tr>
	<?php
		foreach($sketches as $document)
		{
			$client = false;
			
			if($document['Document']['client_id'] > 0)
				$client = $db->first('clients', $document['Document']['client_id']);
	?>
	<tr>
		<td class="check"><input type="checkbox" id="<?=$document['Document']['id']?>" /></td>
		<td>Schets</td>
		<td><a href="/assets/documents/sketches/<?=$document['Document']['project_list_id']?>/<?=$document['Document']['filename']?>" target="_blank"><?=$document['Document']['filename']?></a></td>
		<td><?=($client['Client']['id'] > 0 ? '<a href="/clients/details/' . $client['Client']['id'] . '">' . $client['Client']['zipcode'] . ' ' . $client['Client']['homenumber'] . strtoupper($client['Client']['addition']) . '</a>' : (!empty($document['Document']['street']) ? ucfirst($document['Document']['street']) : 'nee'))?></td>
		<td><?=date('d-m-Y H:i', strtotime($document['Document']['created']))?></td>
	</tr>
	<?php
		}
	?>
</table>
<br /><br />
<?php
	}
	if(count($nestor_reports) > 0)
	{
?>
<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th width="350">Nestor formulieren</th>
		<th>Bestandsnaam</th>
		<th width="200">Matched</th>
		<th width="200">Geimporteerd op</th>
	</tr>
	<?php
		foreach($nestor_reports as $document)
		{
			$client = false;
			
			if($document['Document']['client_id'] > 0)
				$client = $db->first('clients', $document['Document']['client_id']);
			
			$class = "";
			if(!file_exists('assets/documents/nestor_reports/' . $document['Document']['project_list_id'] . '/' . $document['Document']['filename']))
				$class .= " file_not_exists";
	?>
	<tr>
		<td class="check"><input type="checkbox" id="<?=$document['Document']['id']?>" /></td>
		<td>Nestor formulier</td>
		<td class="<?=$class?>"><a href="/assets/documents/nestor_reports/<?=$document['Document']['project_list_id']?>/<?=$document['Document']['filename']?>" target="_blank"><?=$document['Document']['filename']?></a></td>
		<td><?=($client['Client']['id'] > 0 ? '<a href="/clients/details/' . $client['Client']['id'] . '">' . $client['Client']['zipcode'] . ' ' . $client['Client']['homenumber'] . strtoupper($client['Client']['addition']) . '</a>' : (!empty($document['Document']['street']) ? ucfirst($document['Document']['street']) : 'nee'))?></td>
		<td><?=date('d-m-Y H:i', strtotime($document['Document']['created']))?></td>
	</tr>
	<?php
		}
	?>
</table>
<br /><br />
<?php
	}
?>

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
		$('.set_dgt_zipcode_on_pdf').click(function()
		{
			var document_id = parseInt($(this).attr('rel'));
			if(document_id > 0)
			{
				var new_zipcode_number = $(this).parent().find('a').first().text().replace(/ /g, '').toUpperCase();
				popup('<h3>Wil je de pdf overschrijven naar ' + new_zipcode_number + '</h3><br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Terug</a> &nbsp; <a class="btn btn-accept popup_overwrite" href="/"><span class="fas fa-check-circle"></span>Overschrijven</a>');
				$('.popup_no').click(function()
				{
					close_popup();
					return false;
				});
				$('.popup_overwrite').click(function()
				{
					$.getJSON('/ajax/documents/set_dgt_zipcode_on_pdf/' + document_id, function(response)
					{
						if(response['succes'])
						{
							close_popup();
							location.reload(true);
						}else
						{
							close_popup();
							popup('<h3>Er is iets fout gegaan</h3>' + response['error'] + '<br /><br /><a class="btn btn-accept popup_okay" href="/"><span class="fas fa-check-circle"></span>Oke</a>');
							$('.popup_okay').click(function()
							{
								location.reload(true);
								return false;
							});
						}
					});
					return false;
				});
			}
		});
	});
	
	function checkbox_action(action)
	{
		if(action == 'remove')
		{
			var document_ids = (get_all_checked_ids());
			popup('<h3>VERWIJDEREN</h3>Wil je ' + document_ids.length + ' documenten verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');
			
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
					url: '/ajax/documents/remove',
					type: 'post',
					data: {document_ids:document_ids},
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
<style>
.file_not_exists a
{
	color: #cc0000;
}
.set_dgt_zipcode_on_pdf
{
	cursor: pointer;
}
.set_dgt_zipcode_on_pdf:hover
{
	color: #fc6d41;
}
</style>