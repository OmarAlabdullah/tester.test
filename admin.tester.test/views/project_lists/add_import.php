
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

<h1>Nieuwe gegevens importeren - <?=$project_list['Project_list']['name']?></h1>
<h5>Upload een .xlsx bestand om peko, zadel, bijzonderheden en interne opmerkingen opnieuw te importeren.</h5>

<br />

<form method="post" action="/ajax/upload/" enctype="multipart/form-data" id="file_form">
	<input type="file" name="input_file" id="input_file" accept=".xlsx" />
</form>

<div class="page_actions">
	<a class="btn back_to_overview" href="/project_lists/details/<?=$project_list['Project_list']['id']?>"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
	<a class="btn" id="refresh_page" href="<?=SELF?>"><span class="fas fa-cloud-upload-alt"></span>Nieuw bestand uploaden</a>
</div>

<form method="post" action="<?=SELF?>">
	
	<div class="upload_files"><span class="fas fa-cloud-upload-alt"></span><br /><span class="text">Upload lijst</span></div>
	
	<div id="output"></div>
	
	<br />
	
	<div id="import_options">
		<label for="overwrite_peko">
			<input type="checkbox" checked id="overwrite_peko" /> Peko overschrijven
		</label>
		<br />
		<label for="overwrite_zadel">
			<input type="checkbox" checked id="overwrite_zadel" /> Zadel overschrijven
		</label>
		<br />
		<label for="overwrite_remarks">
			<input type="checkbox" id="overwrite_remarks" /> Bijzonderheden overschrijven
		</label>
		<br />
		<label for="overwrite_internal_remarks">
			<input type="checkbox" id="overwrite_internal_remarks" /> Interne Opmerkingen overschrijven
		</label>
	</div>
	
	<table id="results_table"></table>
	
	<div id="save_table_button">
		<br /><br />
		<a href="<?=SELF?>" class="btn">Opslaan</a>
	</div>
	
</form>

<script>
var project_list_id = parseInt(<?=$project_list['Project_list']['id']?>);
var chunk_size = (<?=(int)ini_get('upload_max_filesize')?> * 1024 * 1024);
var file;
var filename;
var file_chunks_total = 0;
var file_chunks_sent = 0;
$(document).ready(function()
{
	$('.upload_files').click(function()
	{
		if(!$(this).hasClass('disabled'))
			$('#input_file').trigger('click');
		return false;
	});
	$('#input_file').change(function(e)
	{
		$('.page_actions').hide();
		$('.upload_files').addClass('disabled');
		$('.upload_files').find('.text').html('Bezig met uploaden... <span class="progress"></span>');
		
		var fd = new FormData();
    file = $(this)[0].files[0];
    //filename = Math.floor(Date.now() / 1000) + '_' + file.name.replace(/ /g, '_');
    filename = file.name;
    file_chunks_total = Math.ceil(file.size / chunk_size);
		file_chunks_sent = 0;
		
		_sent_chunk();
	});
	$('#save_table_button').click(function()
	{
		if(!$(this).hasClass('disabled'))
		{
			$(this).addClass('disabled');
				_save_table();
		}
		return false;
	});
	
	$('#overwrite_peko').change(function()
	{
		var chckd = $(this).prop('checked');
		_set_colum_active('peko', chckd);
	});
	$('#overwrite_zadel').change(function()
	{
		var chckd = $(this).prop('checked');
		_set_colum_active('zadel', chckd);
	});
	$('#overwrite_remarks').change(function()
	{
		var chckd = $(this).prop('checked');
		_set_colum_active('remarks', chckd);
	});
	$('#overwrite_internal_remarks').change(function()
	{
		var chckd = $(this).prop('checked');
		_set_colum_active('internal_remarks', chckd);
	});
});
function _sent_chunk()
{
	if(file_chunks_sent < file_chunks_total)
	{
		blob = file.slice((file_chunks_sent * chunk_size), ((file_chunks_sent+1) * chunk_size));
		var fd = new FormData();
		fd.append('file', blob);
		fd.append('filename', 'files/' + filename);
		
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
				_file_upload_error();
			}
		});
		
		
	}else
	{
		//done
		$('#input_file').val('');
		load_uploaded_documents('files/' + filename);
	}
}
function __send_chunk(form_data, callbck)
{
	$.ajax(
	{
		url: '/ajax/project_lists/upload_documents/' + file_chunks_sent,
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
	  	_file_upload_error();
	  }
	});
}
function upload_progress(perc)
{
	var jObj = $('.upload_files').find('.text').find('.progress');
	
	var perc_calc = file_chunks_sent * (100 / file_chunks_total);
	perc_calc += (perc / file_chunks_total);
	perc_calc = (perc_calc.toFixed(2));
	
	jObj.html(perc_calc+'%');
	
	if(!(perc < 100))
		console.log('Bestand is klaar met uploaden');
}
function reset_upload_button()
{
	$('.upload_files').find('.text').html('Upload bestanden');
	$('.upload_files').removeClass('disabled');
	
	$('#input_file').val('');
	
	$('.page_actions').show();
}
function load_uploaded_documents(filename)
{
	console.log('load_uploaded_documents(\'' + filename + '\')');
	
	$.ajax(
	{
		url: '/ajax/project_lists/parse_add_import',
		type: 'post',
		dataType: 'json',
		data: {filename:filename, project_list_id:project_list_id},
		success: function(response)
		{
			console.log(response);
			
			if(response['rows'].length > 0)
			{
				$('.upload_files').hide();
				$('#import_options').show();
				$('.page_actions').show();
				$('#refresh_page').show();
				_show_results_table(response['rows']);
				$('#save_table_button').show();
			}
		}
	});
}
function _show_results_table(rows)
{
	var results_table = $('#results_table');
	
	results_table.append('<tr><th>Straat</th><th>Huisnummer</th><th>Postcode</th><th>Peko</th><th>Zadel</th><th>Bijzonderheden</th><th>Interne Opmerkingen</th><th width="50">&nbsp;</th></tr>');
	
	for(i in rows)
	{
		var tr = $('<tr client_id="' + rows[i]['client_id'] + '"></tr>');
		tr.append('<td>' + rows[i]['street'] + '</td><td>' + rows[i]['homenumber'] + '</td><td>' + rows[i]['zipcode'] + '</td>');
		
		tr.append('<td class="row_peko"><span class="old_new_value old_value">' + rows[i]['old_peko'] + '</span><span class="old_new_value new_value">' + rows[i]['new_peko'] + '</span></td>');
		tr.append('<td class="row_zadel"><span class="old_new_value old_value">' + rows[i]['old_zadel'] + '</span><span class="old_new_value new_value">' + rows[i]['new_zadel'] + '</span></td>');
		tr.append('<td class="row_remarks"><span class="old_new_value old_value">' + rows[i]['old_remarks'] + '</span><span class="old_new_value new_value">' + rows[i]['new_remarks'] + '</span></td>');
		tr.append('<td class="row_internal_remarks"><span class="old_new_value old_value">' + rows[i]['old_internal_remarks'] + '</span><span class="old_new_value new_value">' + rows[i]['new_internal_remarks'] + '</span></td>');
		
		tr.append('<td class="row_peko"><a href="<?=SELF?>" class="disable_row" title="Negeer dit adres voor aanpassingen"><span class="fas fa-ban"></span></a></td>');
		
		results_table.append(tr);
	}
	
	$('.disable_row').click(function()
	{
		$(this).parent().parent().hide();
		
		return false;
	});
	
	results_table.show();
	
	_set_checkboxes();
}
function _save_table()
{
	var data = [];
	$('#results_table').find('tr:visible').each(function()
	{
		var client_id = parseInt($(this).attr('client_id'));
		if(client_id > 0)
		{
			var client_data = {
				client_id: client_id
			};
			
			if($('#overwrite_peko').prop('checked'))
				client_data['new_peko'] = $(this).find('.row_peko').find('.new_value').text();
			
			if($('#overwrite_zadel').prop('checked'))
				client_data['new_zadel'] = $(this).find('.row_zadel').find('.new_value').text();
			
			if($('#overwrite_remarks').prop('checked'))
				client_data['new_remarks'] = $(this).find('.row_remarks').find('.new_value').text();
			
			if($('#overwrite_internal_remarks').prop('checked'))
				client_data['new_internal_remarks'] = $(this).find('.row_internal_remarks').find('.new_value').text();
			
			data.push(client_data);
		}
	});
	
	console.log(data);
	if(data.length > 0)
	{
		console.log(data);
		$.ajax(
		{
			url: '/ajax/project_lists/save_add_import',
			type: 'post',
			dataType: 'json',
			data: {clients: data},
			success: function(response)
			{
				console.log(response);
				
				$('#save_table_button').removeClass('disabled');
				
				window.location.href = $('.back_to_overview').first().attr('href');
			}
		});
	}
	
}
function _file_upload_error()
{
	file;
	filename;
	file_chunks_total = 0;
	file_chunks_sent = 0;
	
	popup('<h3>Fout bij uploaden</h3>Er is iets fout gegaan met het uploaden van het bestand, probeer het opnieuw.<br /><br /><a class="btn btn-accept popup_okay" href="/"><span class="fas fa-check-circle"></span>Oke</a>');
	$('.popup_okay').click(function()
	{
		close_popup();
		reset_upload_button();
		return false;
	});
}
function _show_error(error_text)
{
	popup('<h3>' + error_text + '</h3>Probeer het opnieuw<br /><br /><a class="btn btn-accept popup_okay" href="/"><span class="fas fa-check-circle"></span>Oke</a>');
	$('.popup_okay').click(function()
	{
		close_popup();
		reset_upload_button();
		return false;
	});
}
function _set_colum_active(column, active)
{
	console.log('_set_colum_active', column, active);
	
	if(!active)
	{
		var jObj = $('.row_' + column);
		jObj.find('.old_new_value').addClass('deactive');
		jObj.find('.new_value').hide();
	}else
	{
		var jObj = $('.row_' + column);
		jObj.find('.old_new_value').removeClass('deactive');
		jObj.find('.new_value').show();
	}
}
function _set_checkboxes()
{
	$('#import_options').find('input[type="checkbox"]').each(function()
	{
		var tpe = $(this).attr('id').substr(10);
		_set_colum_active(tpe, $(this).prop('checked'));
	});
}
</script>
<style>
#file_form, #input_file
{
	display: none;
}
.upload_files
{
	display: inline-block;
	padding: 40px 100px;
	background-color: rgba(0, 0, 0, 0.05);
	cursor: pointer;
	line-height: 30px;
	text-align: center;
	border-radius: 2px;
}
.upload_files:hover
{
	background-color: rgba(0, 0, 0, 0.1);
}
.upload_files .fas
{
	font-size: 30px;
}
.upload_files.disabled
{
	opacity: 0.5;
	cursor: default;
}
.upload_files.disabled:hover
{
	background-color: rgba(0, 0, 0, 0.05);
}
#refresh_page
{
	display: none;
}
.unmatched td, .unmatched td a
{
	color: rgba(255, 0, 0, 0.7);
}
#save_table_button
{
	display: none;
	width: 100px;
}
.link_address
{
	cursor: pointer;
}
#results_table
{
	display: none;
}
.old_new_value
{
	padding: 2px 4px;
	display: inline;
	border-radius: 2px;
}
.old_value
{
	background-color: rgba(255, 0, 0, 0.1);
	margin-right: 10px;
}
.new_value
{
	background-color: rgba(0, 255, 0, 0.1);
	margin-right: 10px;
}
.old_new_value.deactive
{
	background-color: transparent;
}
#import_options
{
	display: none;
	margin-bottom: 20px;
}
</style>