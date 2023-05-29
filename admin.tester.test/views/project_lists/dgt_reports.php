
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

<h1>Documenten - DGT rapporten</h1>
<h5><?=$project_list['Project_list']['name']?></h5>

<form method="post" action="/ajax/upload/" enctype="multipart/form-data" id="file_form">
	<input type="file" name="input_file" id="input_file" accept=".pdf,.zip" />
</form>

<div class="page_actions">
	<a class="btn" href="/project_lists/documents/<?=$project_list['Project_list']['id']?>"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
	<a class="btn" id="refresh_page" href="<?=SELF?>"><span class="fas fa-cloud-upload-alt"></span>Nieuw bestand uploaden</a>
</div>

<form method="post" action="<?=SELF?>">
	
	<div class="upload_files"><span class="fas fa-cloud-upload-alt"></span><br /><span class="text">Upload bestanden</span></div>
	
	<div id="output"></div>
	
	<br />
	
	<div id="show_all_button">
		<a id="show_all">Laat alle resultaten zien</a><br /><br />
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
	$('#show_all').click(function()
	{
		_show_all();
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
		url: '/ajax/project_lists/parse_documents',
		type: 'post',
		dataType: 'json',
		data: {filename:filename, project_list_id:project_list_id},
		success: function(response)
		{
			reset_upload_button();
			console.log(response);
			
			if(response['error'])
				_show_error(response['error']);
			
			if(response['succes'])
			{
				if(response['is_zip'])
				{
					$('#output').html(response['zip']['number_of_files'] + ' bestanden geupload<br />Waarvan ' + response['pdfs'].length + ' pdf bestanden<br />' + (response['matched'] < response['pdfs'].length ? '<b>' + parseInt(response['pdfs'].length - response['matched']) + ' niet gematched</b>' : '<i>Allemaal gematched</i>') + '');
				}else
				{
					$('#output').html('1 bestanden geupload<br />Waarvan ' + response['pdfs'].length + ' pdf bestanden<br />' + (response['matched'] < response['pdfs'].length ? '<b>' + parseInt(response['pdfs'].length - response['matched']) + ' niet gematched</b>' : '<i>Allemaal gematched</i>') + '');
				}
				if(response['matched'] < response['pdfs'].length)
				{
					$('<div class="info_bar">Er zijn sommige raporten niet gematched</div>').insertAfter($('.page_actions'));
				}
				if(response['pdfs'].length > 0)
				{
					$('.upload_files').hide();
					_show_table(response['pdfs'], response['filename']);
				}else
				{
					popup('<h3>Geen pdf gevonden</h3>Er zijn geen pdf bestanden gevonden.<br /><br /><a class="btn btn-accept popup_okay" href="/"><span class="fas fa-check-circle"></span>Oke</a>');
					$('.popup_okay').click(function()
					{
						close_popup();
						reset_upload_button();
						return false;
					});
				}
				$('#refresh_page').show();
			}
		},
	  error: function()
	  {
	  	_file_upload_error();
	  }
	});
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
function _show_table(arr, filename)
{
	$('#results_table').empty();
	$('#results_table').append('<tr><th>Postcode</th><th>Huisnummer</th><th>Toevoeging</th><th>Type</th><th>Bestand</th><th>Match</th><th>&nbsp;</th></tr>');
	var matchted = 0;
	var unmatchted = 0;
	for(i in arr)
	{
		var this_matched = true;
		if(!arr[i]['matched'] || !arr[i]['type_matched'])
		{
			this_matched = false;
			unmatchted++;
		}else
			matchted++;
		$('#results_table').append('<tr rel="' + i + '" ' + (this_matched ? 'class="result_row matched" client_id="' + arr[i]['client_id'] + '"' : 'class="result_row unmatched"') + '><td>' + arr[i]['zipcode'] + '</td><td>' + arr[i]['homenumber'] + '</td><td>' + arr[i]['addition'].toUpperCase() + '</td><td>' + arr[i]['type'] + ' ' + (arr[i]['new'] ? 'nieuwe leiding' : '') + '</td><td><a href="/tests/read_file_from_zip?zip_filename=' + filename + '&filename=' + arr[i]['filename'] + '" target="_blank">' + arr[i]['filename'] + '</td><td>' + (arr[i]['matched'] ? 'ja' : 'nee') + ' </td><td><span id="link_' + i + '" class="fas fa-pencil-alt link_address"></span></td></tr>');
		$('#link_' + i).click(function()
		{
			var row_index = ($(this).parent().parent().attr('rel'));
			link_address(project_list_id, function(response)
			{
				if(response['action'] == 'update')
					linked_address(row_index, response);
				if(response['action'] == 'remove')
					remove_address(row_index);
			});
		});
	}
	if(unmatchted == 0 && false)
	{
		$('#results_table').hide();
		$('#save_table_button').hide();
	}else
	{
		$('#results_table').show();
		$('#save_table_button').show();
	}
	/*if(matchted > 0)
		$('#show_all_button').show();
	else
		$('#show_all_button').hide();*/
}
function _show_all()
{
	$('tr.matched').show();
	$('#show_all_button').hide();
}
function _save_table()
{
	console.log('_save_table');
	
	var dat = 
	{
		project_list_id: project_list_id,
		zip_filename: 'files/' + filename
	};
	var dgt_reports = {};
	
	var i = 0;
	$('#results_table').find('tr:not(:eq(0))').each(function()
	{
		var matched = $(this).hasClass('matched');
		var subtype = $(this).find('td:eq(3)').text();
		var filename = $(this).find('td:eq(4)').text();
		var client_id = parseInt($(this).attr('client_id'));
		
		dgt_reports[i] = {
			matched:matched,
			subtype:subtype,
			filename:filename,
			client_id:client_id
		};
		i++;
		
		console.log(matched, client_id, subtype, filename);
	});
	
	dat['dgt_reports'] = dgt_reports;
	
	console.log(dat);
	
	$.ajax(
	{
		url: '/ajax/project_lists/save_dgt_reports',
		type: 'post',
		dataType: 'json',
		data: dat,
		success: function(response)
		{
			console.log(response);
			if(response['succes'])
				window.location.href = '/project_lists/documents/' + project_list_id;
		}
	});
}

function linked_address(row_rel, response)
{
	console.log('linked_address', response);
	
	$('tr.result_row[rel="' + row_rel + '"]').attr('client_id', parseInt(response['client_id']));
	
	$('tr.result_row[rel="' + row_rel + '"]').find('td:eq(0)').text(response['zipcode']);
	$('tr.result_row[rel="' + row_rel + '"]').find('td:eq(1)').text(response['homenumber']);
	$('tr.result_row[rel="' + row_rel + '"]').find('td:eq(2)').text(response['addition']);
	
	$('tr.result_row[rel="' + row_rel + '"]').removeClass('unmatched');
	$('tr.result_row[rel="' + row_rel + '"]').find('td:eq(5)').text('Handmatig');
	
	close_popup();
}
function remove_address(row_rel)
{
	$('tr.result_row[rel="' + row_rel + '"]').remove();
	
	close_popup();
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
/*tr.matched
{
	display: none;
}*/
#show_all_button
{
	display: none;
}
#show_all
{
	color: #2C9AEB;
	text-decoration: underline;
	cursor: pointer;
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
</style>