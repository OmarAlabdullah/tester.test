
<div class="tabs">
	<a class="selected" href="/project_lists/details/<?=$client['Client']['project_list_id']?>">
		<span class="far fa-address-card"></span>
		<br />
		Adressen
	</a>
	<a href="/project_lists/settings/<?=$client['Client']['project_list_id']?>">
		<span class="fas fa-cog"></span>
		<br />
		Instellingen
	</a>
	<a href="/project_lists/documents/<?=$client['Client']['project_list_id']?>">
		<span class="fas fa-file-alt"></span>
		<br />
		Documenten
	</a>
</div>


<h1>Adres details <?=$client['Client']['street']?> <?=$client['Client']['homenumber']?><?=$client['Client']['addition']?></h1>
<h5><?=$client['Client']['zipcode']?> <?=$client['Client']['city']?></h5>

<div class="page_actions">
	<?php
		if($client['Client']['appointment'] != '0000-00-00')
		{
	?>
	<a class="btn btn-accept" href="/calendar/overview?year=<?=date('Y', strtotime($client['Client']['appointment']))?>&week_number=<?=date('W', strtotime($client['Client']['appointment']))?>&client_id=<?=$client['Client']['id']?>"><span class="fas fa-play"></span>Wijzig afspraak</a>
	<a class="btn btn-alert" id="remove_appointment" href="<?=SELF?>"><span class="fas fa-trash"></span>Afspraak verwijderen</a>
	<a class="btn btn" href="/calendar/overview?year=<?=date('Y', strtotime($client['Client']['appointment']))?>&week_number=<?=date('W', strtotime($client['Client']['appointment']))?>"><span class="fas fa-calendar"></span>Naar agenda</a>
	<?php
		}else
		{
	?>
	<a class="btn btn-accept" href="/calendar/overview?client_id=<?=$client['Client']['id']?>"><span class="fas fa-play"></span>Maak afspraak</a>
	<?php
		}
		if($client['Client']['force_finished'] == 0)
		{
	?>
	<a class="btn" href="<?=SELF?>" id="set_force_finished"><span class="fas fa-wrench"></span>Gesaneerd</a>
	<?php
		}else
		{
	?>
	<a class="btn btn-accept" href="<?=SELF?>" id="remove_force_finished"><span class="fas fa-wrench"></span>Gesaneerd</a>
	<?php
		}
	?>
	<a class="btn btn-alert" id="remove_client" href="<?=SELF?>"><span class="fas fa-trash"></span>Adres verwijderen</a>
</div>

<?=$client['Client']['street']?> <?=$client['Client']['homenumber']?><?=$client['Client']['addition']?><br />
<?=$client['Client']['zipcode']?> <?=$client['Client']['city']?>

<br /><br />

<table>
	<tr>
		<th width="220">Actie</th>
		<th>&nbsp;</th>
	</tr>
	<tr>
		<td><b>Aangemaakt / geimporteerd</b></td>
		<td><?=date('d-m-Y', strtotime($client['Client']['created']))?></td>
	</tr>
	<?php
		if($client['Client']['send_letter_1'] != '0000-00-00 00:00:00')
		{
	?>
	<tr>
		<td><b>Eerste brief verstuurd</b></td>
		<td><?=date('d-m-Y', strtotime($client['Client']['send_letter_1']))?></td>
	</tr>
	<?php
		}else
		{
	?>
	<tr>
		<td colspan="2"><i>Geen brief gestuurd</i></td>
	</tr>
	<?php
		}
		if($client['Client']['send_letter_2'] != '0000-00-00 00:00:00')
		{
	?>
	<tr>
		<td><b>Tweede brief verstuurd</b></td>
		<td><?=date('d-m-Y', strtotime($client['Client']['send_letter_2']))?></td>
	</tr>
	<?php
		}
		if($client['Client']['send_letter_3'] != '0000-00-00 00:00:00')
		{
	?>
	<tr>
		<td><b>Derde brief verstuurd</b></td>
		<td><?=date('d-m-Y', strtotime($client['Client']['send_letter_3']))?></td>
	</tr>
	<?php
		}
	?>
	<tr>
		<td><b>Email</b></td>
		<td><input id="email" class="ghost" value="<?=$client['Client']['email']?>" name="_email" autocomplete="new-password" /></td>
	</tr>
	<tr>
		<td><b>Telefoonnummer</b></td>
		<td><input id="phone" class="ghost" value="<?=$client['Client']['phone']?>" name="_phone" autocomplete="new-password" /></td>
	</tr>
	<tr>
		<td><b>Telefoonnummer 2</b></td>
		<td><input id="phone2" class="ghost" value="<?=$client['Client']['phone2']?>" name="_phone2" autocomplete="new-password" /></td>
	</tr>
	<?php
		if($client['Client']['appointment'] != '0000-00-00')
		{
			if($client['Client']['timeframe_id'] > 0)
			{
				$timeframe = $db->first('timeframes', $client['Client']['timeframe_id']);
			}
	?>
	<tr>
		<td><b>Afspraak</b></td>
		<td><?=date('d-m-Y', strtotime($client['Client']['appointment']))?> <?=($timeframe ? ' &nbsp; ' . $timeframe['Timeframe']['timeframe'] : '')?></td>
	</tr>
	<?php
			if($client['Client']['appointment_mail'] == '0000-00-00 00:00:00')
			{
	?>
	<tr>
		<td colspan="2"><i>Geen bevestigingsmail gestuurd</i></td>
	</tr>
	<?php
			}else
			{
	?>
	<tr>
		<td><b>Bevestigingsmail verstuurd</b></td>
		<td><?=date('d-m-Y H:i:s', strtotime($client['Client']['appointment_mail']))?></td>
	</tr>
	<?php
			}
		}else
		{
	?>
	<tr>
		<td colspan="2"><i>Niet ingepland</i></td>
	</tr>
	<?php
		}
	?>

	<tr>
		<td><b>Peko</b></td>
		<td><input id="peko" class="ghost" value="<?=$client['Client']['peko']?>" /></td>
	</tr>
	<tr>
		<td><b>Zadel</b></td>
		<td><input id="zadel" class="ghost" value="<?=$client['Client']['zadel']?>" /></td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td><b>Bijzonderheden</b></td>
		<td><input id="remarks" class="ghost" value="<?=$client['Client']['remarks']?>" /></td>
	</tr>
	<tr>
		<td><b>Interne Opmerkingen</b></td>
		<td><input id="internal_remarks" class="ghost" value="<?=$client['Client']['internal_remarks']?>" /></td>
	</tr>

	<?php
		if($client['Client']['force_finished'] == 1)
		{
	?>
	<tr>
		<td colspan="2"><b>Gesaneerd</b> (geforceerd)</td>
	</tr>
	<?php
	}else
	{
	?>
	<tr>
		<td colspan="2"><label for="not_remediated"><input type="checkbox" id="not_remediated" <?=($client['Client']['not_remediated'] == 1 ? 'checked' : '')?> /> Niet gesaneerd</label></td>
	</tr>
	<?php
	}
	?>
</table>

<br /><br />

<?php
	if($client['Client']['gas_stop'] == 1 || $client['Client']['vwi'] == 1 || $client['Client']['overlengte'] <> 0 || strlen($client['Client']['meerwerk']) > 0)
	{
?>
<table>
	<tr>
		<th colspan="999">Extra gegevens</th>
	</tr>
	<tr>
		<td width="30%">Gasstopper</td>
		<td><?=($client['Client']['gas_stop'] == 1 ? '<span style="color: #00cc00; " class="far fa-check-circle"></span>' : '-')?></td>
	</tr>
	<tr>
		<td>VWI</td>
		<td><?=($client['Client']['vwi'] == 1 ? '<span style="color: #00cc00; " class="far fa-check-circle"></span>' : '-')?></td>
	</tr>
	<tr>
		<td>Overlengte</td>
		<td><?=($client['Client']['overlengte'] <> 0 ? (float)$client['Client']['overlengte'] : '-')?></td>
	</tr>
	<tr>
		<td>Meerwerk</td>
		<td><?=(strlen($client['Client']['meerwerk']) > 0 ? nl2br($client['Client']['meerwerk']) : '-')?></td>
	</tr>
</table>
<br />
<?php
	}
?>

<?php
	if(count($dgt_documents) > 0)
	{
?>
<table>
	<tr>
		<th width="30%">DGT Rapporten</th>
		<th>Bestandsnaam</th>
		<th>Geimporteerd op</th>
		<th width="40"></th>
	</tr>
	<?php
		foreach($dgt_documents as $document)
		{
	?>
	<tr>
		<td><?=$document['Document']['subtype']?></td>
		<td><a href="/assets/documents/dgt/<?=$document['Document']['project_list_id']?>/<?=$document['Document']['filename']?>" target="_blank"><?=$document['Document']['filename']?></a></td>
		<td><?=date('d-m-Y H:i', strtotime($document['Document']['created']))?></td>
		<td><span class="fas fa-times-circle remove_hover remove_document" rel="<?=$document['Document']['id']?>"></span></td>
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
		<th width="30%">Schetsen</th>
		<th>Bestandsnaam</th>
		<th>Geimporteerd op</th>
		<th width="40"></th>
	</tr>
	<?php
		foreach($sketches as $document)
		{
	?>
	<tr>
		<td>Schets</td>
		<td><a href="/assets/documents/sketches/<?=$document['Document']['project_list_id']?>/<?=$document['Document']['filename']?>" target="_blank"><?=$document['Document']['filename']?></a></td>
		<td><?=date('d-m-Y H:i', strtotime($document['Document']['created']))?></td>
		<td><span class="fas fa-times-circle remove_hover remove_document" rel="<?=$document['Document']['id']?>"></span></td>
	</tr>
	<?php
		}
	?>
</table>
<br /><br />
<?php
	}


	if(count($nestors) > 0)
	{
?>
<table>
	<tr>
		<th width="30%">Nestor formulieren</th>
		<th>Bestandsnaam</th>
		<th>Geimporteerd op</th>
		<th width="40"></th>
	</tr>
	<?php
		foreach($nestors as $document)
		{
	?>
	<tr>
		<td>Nestor formulier</td>
		<td><a href="/assets/documents/nestor_reports/<?=$document['Document']['project_list_id']?>/<?=$document['Document']['filename']?>" target="_blank"><?=$document['Document']['filename']?></a></td>
		<td><?=date('d-m-Y H:i', strtotime($document['Document']['created']))?></td>
		<td><span class="fas fa-times-circle remove_hover remove_document" rel="<?=$document['Document']['id']?>"></span></td>
	</tr>
	<?php
		}
	?>
</table>
<br /><br />
<?php
	}
?>



<table>
	<tr>
		<th width="30%">Verplichte Foto's</th>
		<th>Gemaakt op</th>
		<th>Gemaakt door</th>
	</tr>
	<?php
		$required_photos = explode('|', $project_list['Project_list']['required_photos']);
		foreach($required_photos as $required_photo)
		{
	?>
	<tr>
		<td><?=$required_photo?></td>
		<?php
			if(isset($got_photos[strtolower($required_photo)]))
			{
				$worker = false;
				if($got_photos[strtolower($required_photo)]['Photo']['worker_id'] > 0)
					$worker = $db->first('workers', $got_photos[strtolower($required_photo)]['Photo']['worker_id']);
		?>
		<td><?=date('d-m-Y H:i', strtotime($got_photos[strtolower($required_photo)]['Photo']['created']))?></td>
		<td><?=($worker ? $worker['Worker']['name'] : '&nbsp;')?></td>
		<?php
			}else
			{
		?>
		<td><span style="color: #cc0000; " class="far fa-times-circle"></span></td>
		<td>&nbsp;</td>
		<?php
			}
		?>
	</tr>
	<?php
		}
	?>
	<tr>
		<td><b>Volledig uitgevoerd:</b></td>
		<td><?=($client['Client']['finished'] ? '<span style="color: #00cc00; " class="far fa-check-circle"></span>' : '<span style="color: #cc0000; " class="far fa-times-circle"></span>')?></td>
		<td>&nbsp;</td>
	</tr>
</table>

<div class="photo_holder">
	<?php
		foreach($photos as $photo)
		{
	?>
	<div class="photo">
		<img class="open_full_screen" src="https://app.drs-infra.nl/photos/<?=$photo['Photo']['project_list_id']?>/<?=$photo['Photo']['id']?>.<?=$photo['Photo']['ext']?>" />
		<div class="photo_type"><?=(!empty($photo['Photo']['type']) ? $photo['Photo']['type'] : 'extra')?></div>
		<div class="remove_photo" rel="<?=$photo['Photo']['id']?>"><span class="fas fa-times"></span></div>
	</div>
	<?php
		}
	?>
</div>

<br /><br />

<a class="btn" id="upload_photo" href="<?=SELF?>"><span class="fas fa-image"></span>Extra foto toevoegen</a>
<span id="upload_progress"></span>


<input type="file" name="input_file" id="input_file" accept="image/*" style="display: none; " />

<?php
//pr($client);
//phpinfo();
?>

<style>
.photo_holder
{
	width: 100%;
	min-height: 20px;
}
.photo_holder .photo
{
	position: relative;
	display: inline-block;
	width: 200px;
	height: 200px;
	margin-right: 20px;
	margin-top: 20px;
	cursor: pointer;
}
.photo_holder .photo img
{
	width: 200px;
	height: 200px;
	object-fit: cover;
}
.photo_holder .photo .photo_type
{
	position: absolute;
	right: 10px;
	top: 10px;
	display: inline-block;
	padding: 5px 10px;
	background-color: rgba(0, 0, 0, 0.8);
	color: #ffffff;
	font-weight: bold;
	border-radius: 3px;
	max-width: 180px;
	overflow: hidden;
	white-space: nowrap;
	box-sizing: border-box;
}
.photo_holder .photo .remove_photo
{
	position: absolute;
	left: 10px;
	bottom: 10px;
	display: inline-block;
	padding: 5px 10px;
	color: rgba(200, 0, 0, 0.8);
	font-weight: bold;
	border-radius: 3px;
	max-width: 180px;
	overflow: hidden;
	white-space: nowrap;
	box-sizing: border-box;
}
.photo_holder .photo .remove_photo:hover
{
	background-color: rgba(200, 0, 0, 0.3);
}

.photo_popup_overlay
{
	width: 100%;
	height: calc(100vh - 100px);
	background-color: rgba(0, 0, 0, 0.9);
	position: fixed;
	left: 0px;
	top: 100px;
	z-index: 150;
	display: none;
}
.photo_popup
{
	width: calc(100% - 40px);
	height: calc(100vh - 140px);
	position: fixed;
	left: 20px;
	top: 20px;
	z-index: 151;
}
.photo_popup img
{
	border-radius: 4px;
	width: 100%;
	height: calc(100vh - 140px);
	object-fit: contain;
	position: relative;
	top: 100px;
}
input.ghost
{
	background-color: transparent;
	border: transparent solid 1px;
	width: 300px;
	height: 24px;
}
input.ghost:hover
{
	border: #cccccc solid 1px;
}
</style>
<script>

var client_finished = <?=($client['Client']['finished'] ? 'true' : 'false')?>;
var client_id = parseInt(<?=$client['Client']['id']?>);
var chunk_size = (<?=(int)ini_get('upload_max_filesize')?> * 1024 * 1024);
var file;
var filename;
var file_chunks_total = 0;
var file_chunks_sent = 0;
var tmp_filename;
var tmout;

$(document).ready(function()
{
	photo_events();
	$('#upload_photo').click(function()
	{
		$('#input_file').trigger('click');
		return false;
	});
	$('#input_file').change(function(e)
	{
		$('#upload_progress').html('Uploaden');
		var fd = new FormData();
    file = $(this)[0].files[0];
    filename = file.name;
    file_chunks_total = Math.ceil(file.size / chunk_size);
		file_chunks_sent = 0;
		tmp_filename = 'files/' + Math.floor(Date.now() / 1000) + '_' + filename;

		_sent_chunk();
	});

	$('#zadel, #peko').on('change, keyup', function()
	{
		clearTimeout(tmout);
		tmout = setTimeout(function()
		{
			_update_peko_zadel();
		}, 500);
	});

	$('#remarks, #internal_remarks').on('change, keyup', function()
	{
		clearTimeout(tmout);
		tmout = setTimeout(function()
		{
			_update_remarks();
		}, 500);
	});

	$('#email, #phone, #phone2').on('change, keyup', function()
	{
		clearTimeout(tmout);
		tmout = setTimeout(function()
		{
			_update_email_phone();
		}, 500);
	});

	$('#not_remediated').on('change, click', function()
	{
		var not_remediated = $(this).prop('checked');
		//console.log(not_remediated);
		$.getJSON('/ajax/clients/set_not_remediated/' + client_id + '/' + (not_remediated ? '1' : '0'), function(response)
		{
			console.log(response);
		});
	});

	$('#remove_client').click(function()
	{
		var project_list_id = parseInt(<?=(int)$client['Client']['project_list_id']?>);
		if(client_id > 0 && project_list_id > 0)
		{
			popup('<h3>VERWIJDEREN</h3>Wil je dit adres definitief verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');

			$('.popup_no').click(function()
			{
				close_popup();
				return false;
			});
			$('.popup_yes').click(function()
			{
				$.getJSON('/ajax/clients/remove_client/' + client_id, function(response)
				{
					console.log(response);
					if(response['succes'])
					{
						window.location.href = '/project_lists/details/' + project_list_id;
					}
				});

				return false;
			});
		}
		return false;
	});
	$('#remove_appointment').click(function()
	{
		var project_list_id = parseInt(<?=(int)$client['Client']['project_list_id']?>);
		if(client_id > 0 && project_list_id > 0)
		{
			popup('<h3>Verwijderen</h3>Wil je dit adres uit de agenda halen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Uitplannen</a>');

			$('.popup_no').click(function()
			{
				close_popup();
				return false;
			});
			$('.popup_yes').click(function()
			{
				$.getJSON('/ajax/clients/remove_appointment/' + client_id, function(response)
				{
					console.log(response);
					if(response['succes'])
					{
						close_popup();
						popup('<h3>Stuur email</h3>Wil je een anuleringsbevestiging per email versturen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-accept popup_sent" href="/"><span class="fas fa-chevron-circle-right"></span>Verstuur email</a>');
						$('.popup_no').click(function()
						{
							close_popup();
							window.location.href = '/clients/details/' + client_id;
							return false;
						});
						$('.popup_sent').click(function()
						{
							console.log('sent');
							$.getJSON('/ajax/clients/sent_cancel_mail/' + client_id, function(response)
							{
								console.log(response);
								close_popup();
								window.location.href = '/clients/details/' + client_id;
							});
							return false;
						});
						//window.location.href = '/clients/details/' + client_id;
					}
				});

				return false;
			});
		}
		return false;
	});
	$('#remove_force_finished').click(function()
	{
		if(client_id > 0)
		{
			popup('<h3>Gesaneerd verwijderen</h3>Wil je de geforceerde Gesaneerd verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-accept popup_yes" href="/"><span class="fas fa-chevron-circle-right"></span>OK</a>');
			$('.popup_no').click(function()
			{
				close_popup();
				return false;
			});
			$('.popup_yes').click(function()
			{
				$.getJSON('/ajax/clients/set_force_finished/' + client_id + '/0', function(response)
				{
					if(response['succes'])
						window.location.href = '/clients/details/' + client_id;
				});
				close_popup();
				return false;
			});
		}

		return false;
	});
	$('#set_force_finished').click(function()
	{
		if(client_id > 0)
		{
			popup('<h3>Gesaneerd forceren</h3>Wil je de gesaneerd forceren?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-accept popup_yes" href="/"><span class="fas fa-chevron-circle-right"></span>OK</a>');
			$('.popup_no').click(function()
			{
				close_popup();
				return false;
			});
			$('.popup_yes').click(function()
			{
				$.getJSON('/ajax/clients/set_force_finished/' + client_id + '/1', function(response)
				{
					if(response['succes'])
						window.location.href = '/clients/details/' + client_id;
				});
				close_popup();
				return false;
			});
		}

		return false;
	});
	$('.remove_document').click(function()
	{
		var document_id = parseInt($(this).attr('rel'));
		if(document_id > 0 && client_id > 0)
		{
			console.log('remove document', document_id);

			popup('<h3>Document verwijderen</h3>Wil je dit document verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');
			$('.popup_no').click(function()
			{
				close_popup();
				return false;
			});
			$('.popup_yes').click(function()
			{
				var dat = {document_ids: [document_id]};
				$.post('/ajax/documents/remove/', dat, function(response)
				{
					if(response['succes'])
					{
						window.location.href = '/clients/details/' + client_id;
					}
				}, 'json');
				close_popup();
				return false;
			});
		}
	});
});
function _update_peko_zadel()
{
	var peko = $('#peko').val();
	var zadel = $('#zadel').val();

	var fd = new FormData();
	fd.append('client_id', client_id);
	fd.append('peko', peko);
	fd.append('zadel', zadel);

	$.ajax(
	{
		url: '/ajax/clients/update_peko_zadel',
		type: 'post',
		dataType: 'json',
		data: fd,
		contentType: false,
		processData: false,
		success: function(response)
		{
			console.log(response);
			if(response['succes'])
			{

			}
		}
	});
}
function _update_remarks()
{
	var remarks = $('#remarks').val();
	var internal_remarks = $('#internal_remarks').val();

	var fd = new FormData();
	fd.append('client_id', client_id);
	fd.append('remarks', remarks);
	fd.append('internal_remarks', internal_remarks);

	console.log(client_id, remarks, internal_remarks);

	$.ajax(
	{
		url: '/ajax/clients/update_remarks_internal_remarks',
		type: 'post',
		dataType: 'json',
		data: fd,
		contentType: false,
		processData: false,
		success: function(response)
		{

		},
		error: function(response)
		{
			console.error(response);
		}
	});
}
function _update_email_phone()
{
	var email = $('#email').val();
	var phone = $('#phone').val();
	var phone2 = $('#phone2').val();

	var fd = new FormData();
	fd.append('client_id', client_id);
	fd.append('email', email);
	fd.append('phone', phone);
	fd.append('phone2', phone2);

	$.ajax(
	{
		url: '/ajax/clients/update_email_phone',
		type: 'post',
		dataType: 'json',
		data: fd,
		contentType: false,
		processData: false,
		success: function(response)
		{
			console.log(response);
			if(response['succes'])
			{

			}
		}
	});
}
function _sent_chunk()
{
	if(file_chunks_sent < file_chunks_total)
	{
		blob = file.slice((file_chunks_sent * chunk_size), ((file_chunks_sent+1) * chunk_size));
		var fd = new FormData();
		fd.append('file', blob);
		fd.append('filename', tmp_filename);

		__send_chunk(fd, function(response)
		{
			console.log('response', response);

			if(response)
			{
				file_chunks_sent++;
				setTimeout(function()
				{
					_sent_chunk();
				}, 10);
			}else
			{
				//error
			}
		});


	}else
	{
		//done
		console.log('done');
		$('#input_file').val('');
		setTimeout(function()
		{
			$('#upload_progress').fadeOut(function()
			{
				$('#upload_progress').html('');
				$('#upload_progress').show();
			});
		}, 1000);

		var fd = new FormData();
		fd.append('filename', tmp_filename);

		$.ajax(
		{
			url: '/ajax/photos/process_photo/' + client_id + '/',
			type: 'post',
			dataType: 'json',
			data: fd,
			contentType: false,
			processData: false,
			success: function(response)
			{
				console.log(response);
				if(response['succes'])
				{
					jObj = $('<div class="photo"><img class="open_full_screen" src="' + response['image_src'] + '" /><div class="photo_type">' + (response['image_type'] == '' ? 'extra' : response['image_type']) + '</div><div class="remove_photo" rel="' + parseInt(response['insert_id']) + '"><span class="fas fa-times"></span></div></div>');
					jObj.hide();
					$('.photo_holder').append(jObj);
					jObj.fadeIn(function()
					{
						photo_events();
					});
				}
			}
		});
	}
}
function __send_chunk(form_data, callbck)
{
	$.ajax(
	{
		url: '/ajax/photos/upload_photo/' + file_chunks_sent,
		type: 'post',
		dataType: 'json',
		data: form_data,
		contentType: false,
		processData: false,
		success: function(response)
		{
			console.log(response);

			if(typeof callbck == 'function')
			{
				callbck.call(this, response['succes']);
			}
		},
		xhr: function()
		{
	    var xhr = new window.XMLHttpRequest();
	    xhr.upload.addEventListener("progress", function(evt)
	    {
	      if(evt.lengthComputable)
	      {
	        var perc = evt.loaded / evt.total;
	        perc = parseInt(perc * 100);
	        upload_progress(perc);
	      }
	    }, false);
	    return xhr;
	  },
	  error: function()
	  {

	  }
	});
}
function upload_progress(perc)
{
	//var jObj = $('.upload_files').find('.text').find('.progress');

	var perc_calc = file_chunks_sent * (100 / file_chunks_total);
	perc_calc += (perc / file_chunks_total);
	perc_calc = (perc_calc.toFixed(2));

	//jObj.html(perc_calc+'%');

	$('#upload_progress').html('Uploaden: ' + Math.round(perc_calc) + '%');

	console.log('Uploading ' + perc_calc);

	if(!(perc_calc < 100))
		console.log('Bestand is klaar met uploaden');
}
function _open_full_screen(src)
{
	var jObjo = $('<div class="photo_popup_overlay"></div>');
	jObjo.click(function()
	{
		_close_full_screen();
	});

	var jObj = $('<div class="photo_popup"></div>');
	//jObj.append('<div class="photo_popup_controls"><div class="photo_popup_controls_change"><span class="far fa-caret-square-up"></span></div><div></div><div class="photo_popup_controls_remove"><span class="far fa-trash-alt"></span></div></div>');
	var img = $('<img src="' + src + '" />');
	jObj.append(img);
	jObj.appendTo(jObjo);

	jObjo.appendTo($('body'));

	jObjo.fadeIn(200);
}
function _close_full_screen()
{
	$('.photo_popup_overlay').fadeOut(200, function()
	{
		$(this).remove();
	});
}
function photo_events()
{
	$('.open_full_screen').unbind('click');
	$('.open_full_screen').click(function()
	{
		var src = $(this).attr('src');
		if(src.length > 0)
		{
			_open_full_screen(src);
		}
	});

	$('.remove_photo').unbind('click');
	$('.remove_photo').click(function()
	{
		var jObj = $(this);
		var photo_id = parseInt($(this).attr('rel'));
		if(photo_id > 0)
		{
			popup('<h3>VERWIJDEREN</h3>Wil je deze foto verwijderen?<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Nee</a> <a class="btn btn-alert popup_yes" href="/"><span class="fas fa-times-circle"></span>Verwijderen</a>');

			$('.popup_no').click(function()
			{
				close_popup();
				return false;
			});

			$('.popup_yes').click(function()
			{
				$.ajax(
				{
					url: '/ajax/photos/remove',
					type: 'post',
					data: {photo_id:photo_id},
					dataType: 'json',
					success: function(response)
					{
						console.log(response);
						if(response['succes'])
						{
							if(response['removed'])
							{
								if(response['client_finished'] != client_finished)
									location.reload(true);
								else
								{
									jObj.parent().fadeOut(function()
									{
										$(this).remove();
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
				close_popup();
				return false;
			});
		}
	});

	$('.photo_type').unbind('click');
	$('.photo_type').click(function()
	{
		var jObj = $(this);

		var current_type = $(this).text();
		if(current_type == 'extra')
			current_type = '';
		var sel = '<select class="select_photo_type">';
		<?php
			foreach($required_photos as $required_photo)
			{
		?>
		sel += '<option value="<?=strtolower($required_photo)?>"><?=strtolower($required_photo)?></option>';
		<?php
			}
		?>
		sel += '<option value="">extra</option>';
		sel += '</select>';
		popup('<h3>Foto type selecteren</h3><br />' + sel + '<br /><br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Annuleren</a> <a class="btn btn-accept popup_yes" href="/"><span class="fas fa-save"></span>Oplsaan</a>');
		$('.select_photo_type').val(current_type);
		$('.popup_no').click(function()
		{
			close_popup();
			return false;
		});
		$('.popup_yes').click(function()
		{
			var new_type = $('.select_photo_type').val();
			var photo_id = parseInt(jObj.parent().find('.remove_photo').attr('rel'));
			console.log('photo_id', photo_id);
			if(photo_id > 0)
			{
				$.getJSON('/ajax/photos/set_photo_type/' + photo_id + '/' + new_type, function(response)
				{
					console.log('set_photo_type', response);

					if(response['client_finished'] != client_finished)
						location.reload(true);
					else
					{
						jObj.text(response['type']);
						if(response['type'] == '')
							jObj.text('extra');
						close_popup();
					}

				});
			}
			return false;
		});
	});
}
</script>
