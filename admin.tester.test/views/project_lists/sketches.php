
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

<h1>Documenten - Schetsen</h1>
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
		console.log('done');
		parse_uploaded_file('files/' + filename);
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
}
function reset_upload_button()
{
	$('.upload_files').find('.text').html('Upload bestanden');
	$('.upload_files').removeClass('disabled');
	
	$('#input_file').val('');
	
	$('.page_actions').show();
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
function parse_uploaded_file(filename)
{
	console.log('parse_uploaded_file(\'' + filename + '\')');
	
	$.ajax(
	{
		url: '/ajax/project_lists/parse_sketches',
		type: 'post',
		dataType: 'json',
		data: {filename:filename, project_list_id:project_list_id},
		success: function(response)
		{
			reset_upload_button();
			console.log(response);
			
			if(response['succes'])
			{
				//window.location.href = '/project_lists/documents/' + project_list_id;
			}
		},
	  error: function()
	  {
	  	_file_upload_error();
	  }
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