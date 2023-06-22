

<div class="app_center">
	<div class="big_icon"><span class="fas fa-image"></span></div>
	<div class="maintext"><?=$client['Client']['street']?> <?=$client['Client']['homenumber']?><?=$client['Client']['addition']?></div>
</div>

<br />

<div class="app_list">
	<div class="app_list_header">
		<?=tl('Kies een foto type')?>
	</div>

	<?php
		$required_photos = explode('|', $project_list['Project_list']['required_photos']);
		foreach($required_photos as $required_photo)
		{
			
	?>
	<a rel="<?=strtolower($required_photo)?>" class="app_list_item photo_type <?=($got_photos[strtolower($required_photo)] > 0 ? 'ghosted' : '')?>">
		<span class="app_list_item_prepend">
			<span class="far fa-dot-circle"></span>
		</span>
		<?=$required_photo?>
	</a>
	<?php
		}
	?>
	
	<a rel="" class="app_list_item photo_type">
		<span class="app_list_item_prepend">
			<span class="fas fa-random"></span>
		</span>
		<?=tl('Anders')?>
	</a>
	
	<a href="/clients/details/<?=$client['Client']['id']?>" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="fas fa-ban"></span>
		</span>
		<?=tl('Annuleren')?>
	</a>
	
</div>

<input type="file" id="file_upload" name="file_upload" style="display: none; " accept="image/*" <?=($userLoggedIn['Worker']['force_camera'] ? 'capture="environment"' : '')?> />

<script>
var chunk_size = (<?=(int)ini_get('upload_max_filesize')?> * 1024 * 1024);
var file;
var filename;
var temp_upload_filename;
var file_chunks_total = 0;
var file_chunks_sent = 0;
var photo_type = '';
	
	function str_replace(search, replace, subject) {
    return subject.split(search).join(replace);
}
	
$(document).ready(function()
{
	back_button('<?=tl('Woning')?>', '/clients/details/<?=$client['Client']['id']?>');
	
	$('#file_upload').change(function()
	{
		black_popup('<?=tl('Bezig met uploaden')?><br /><br /><div class="thin_loader"><div class="thin_loader_bar"></div></div>');
		
		var fd = new FormData();
    file = $(this)[0].files[0];
    filename = file.name;
    var ext = file.name.split('.').pop();
    temp_upload_filename = 'temp_upload_' + Date.now() + '.' + ext;
    file_chunks_total = Math.ceil(file.size / chunk_size);
		file_chunks_sent = 0;
		_sent_chunk();
	});
	
	$('.photo_type').click(function()
	{
		photo_type = $(this).attr('rel').toLowerCase();
		$('#file_upload').trigger('click');
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
		fd.append('filename', 'files/' + temp_upload_filename);
		
		__send_chunk(fd, function(response)
		{
			file_chunks_sent++;
			setTimeout(function()
			{
				_sent_chunk();
			}, 10);
		});
	}else
	{
		//done
		var fd = new FormData();
		fd.append('filename', 'files/' + temp_upload_filename);
		_process_photo(fd, function(response)
		{
			close_black_popup(function()
			{
				var new_photo_type = str_replace(' ', '@', photo_type);  // die heb ik toegevoegd 
				console.log('/ajax/clients/set_photo/<?=$client['Client']['id']?>/' + new_photo_type + '?temp_filename=' + response['filename']);
				
				$.getJSON('/ajax/clients/set_photo/<?=$client['Client']['id']?>/' + new_photo_type + '?temp_filename=' + response['filename'], function(response)
				{
					console.log(response);
					if(response['succes'])
						window.location.href = '/clients/photo/<?=$client['Client']['id']?>';
				});
			});
		});
	}
}
function __send_chunk(form_data, callbck)
{
	$.ajax(
	{
		url: '/ajax/clients/upload_photo/' + file_chunks_sent,
		type: 'post',
		dataType: 'json',
		data: form_data,
		contentType: false,
		processData: false,
		success: function(response)
		{
			console.log(response);
			
			if(response['succes'])
			{
				if(typeof callbck == 'function')
				{
					callbck.call(this, response);
				}
			}else
			{
				_upload_photo_error();
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
	  	//error
	  	_upload_photo_error();
	  }
	});
}
function upload_progress(perc)
{
	var perc_calc = file_chunks_sent * (100 / file_chunks_total);
	perc_calc += (perc / file_chunks_total);
	perc_calc = (perc_calc.toFixed(2));
	
	console.log(perc_calc+'%');
	
	$('.thin_loader_bar').css('width', perc_calc+'%');
}
function _process_photo(form_data, callbck)
{
	close_black_popup(function()
	{
		black_popup('<?=tl('Bezig met verwerken')?>');
		
		$.ajax(
		{
			url: '/ajax/clients/process_photo',
			type: 'post',
			dataType: 'json',
			data: form_data,
			contentType: false,
			processData: false,
			success: function(response)
			{
				console.log(response);
				
				if(response['succes'])
				{
					if(typeof callbck == 'function')
					{
						callbck.call(this, response);
					}
				}else
				{
					_upload_photo_error();
				}
			},
		  error: function()
		  {
		  	//error
		  	_upload_photo_error();
		  }
		});
	});
}
function _upload_photo_error()
{
	close_black_popup(function()
	{
		white_popup('<b><?=tl('Fout bij verwerken')?></b><br /><br /><?=tl('Er is iets fout gegaan met het verwerken van de foto')?>', '<?=tl('OK')?>', function()
		{
			location.reload(true);
		});
	});
}
</script>