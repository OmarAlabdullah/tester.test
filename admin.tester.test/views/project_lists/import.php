<h1>Nieuw project toevoegen</h1>
<h5>Kies een bestand om te importeren of maak een leeg project aan</h5>

<div class="page_actions">
	<a class="btn" href="/project_lists/overview"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
	<a id="create_empty_list" class="btn" href="/"><span class="fas fa-expand"></span>Leeg project aanmaken</a>
	<a id="upload_file" class="btn" href="/"><span class="fas fa-file-upload"></span>Een bestand importeren</a>
</div>

<div class="upload_progress">
	<div class="bar"></div>
	<div class="perc">0%</div>
</div>

<div class="uploading_info"></div>

<div class="output_window"></div>

<form method="post" action="/ajax/upload/" enctype="multipart/form-data" id="file_form">
	<input type="file" name="input_file" id="input_file" />
</form>

<style>
	#file_form, .uploading_info, .upload_progress
	{
		display: none;
	}
	.upload_progress
	{
		position: relative;
		width: 100%;
		border: #eeeeee solid 1px;
		height: 30px;
		line-height: 30px;
		box-sizing: border-box;
		text-align: center;
	}
	.upload_progress .bar
	{
		position: absolute;
		width: 0%;
		height: 30px;
		line-height: 30px;
		background-color: #53C804;
	}
	.upload_progress .perc
	{
		position: absolute;
		width: 100%;
		text-align: center;
	}
	.uploading_info
	{
		margin: 10px 0px;
		background-color: #FFFBDF;
		color: #CD7B01;
		border: #FFF297 solid 1px;
		box-sizing: border-box;
		padding: 8px 10px;
	}
	.error
	{
		margin: 10px 0px;
		background-color: #FFEADF;
		color: #CD3401;
		border: #FFB697 solid 1px;
		box-sizing: border-box;
		padding: 8px 10px;
	}
	td.center_text
	{
		text-align: center;
		color: #999999;
		font-style: italic;
	}
	.list_name, .project_number
	{
		width: 30%;
		min-width: 300px;
		padding: 10px;
		border: #cccccc solid 1px;
		background-color: #ffffff;
	}
	#exsisting_lists_holder
	{
		display: none;
	}
	td.disabled
	{
		opacity: 0.2;
	}
</style>
<script>
	$(document).ready(function()
	{
		console.log('ready');

		$('#create_empty_list').click(function()
		{
			popup('<h3>Project aanmaken</h3>Geef de naam van de lijst/project<br /><br /><input id="project_number" type="text" placeholder="Projectnummer" /><br /><br /><input id="list_name" type="text" placeholder="Projectnaam" /><br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>Terug</a> <a class="btn btn-accept popup_yes" href="/"><span class="fas fa-check-circle"></span>Aanmaken</a>');

			$('#project_number').focus().keydown(function(e)
			{
				if(e.keyCode == 13)
					$('.popup_yes').click();
			});
			$('#list_name').keydown(function(e)
			{
				if(e.keyCode == 13)
					$('.popup_yes').click();
			});
			$('.popup_no').click(function()
			{
				close_popup();
				reset_actions_selector();
				return false;
			});
			$('.popup_yes').click(function()
			{
				var project_number = $('#project_number').val();
				var list_name = $('#list_name').val();
				$.ajax(
				{
					url: '/ajax/project_lists/create_empty_list',
					type: 'post',
					data: {project_number:project_number,list_name:list_name},
					dataType: 'json',
					success: function(response)
					{
						console.log(response);
						if(response['succes'])
							window.location.href = '/project_lists/details/' + response['project_list_id'];
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

			return false;
		});

		$('#upload_file').click(function()
		{
			$('#input_file').trigger('click');
			return false;
		});

		$('#input_file').change(function(e)
		{
			var fd = new FormData();
      var files = $(this)[0].files[0];
      fd.append('file',files);

			$('.page_actions').hide();

			$.ajax(
			{
				url: '/ajax/project_lists/upload_list',
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
						load_project_list(response['filename']);
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
			  }
			});

		});
	});

	function upload_progress(perc)
	{
		console.log(perc);
		$('.upload_progress').show();
		$('.upload_progress').find('.bar').css('width', perc+'%');
		$('.upload_progress').find('.perc').html(perc+'%');

		$('.uploading_info').show();
		if(perc < 100)
			$('.uploading_info').html('Bestand wordt geupload');
		else
			$('.uploading_info').html('Bestand is klaar met uploaden');
	}
	function load_project_list(filename)
	{
		$('.error').remove();
		$.ajax(
		{
			url: '/ajax/project_lists/load_preview',
			type: 'post',
			data: {filename:filename},
			dataType: 'json',
			success: function(response)
			{
				console.log(response);

				if(response['succes'])
				{
					if(!response['error'])
					{
						var tbl = $('<table>');
						tbl.append('<tr><th>Straat</th><th>Huisnummer</th><th>Toevoeging</th><th>Postcode</th><th>Woonplaats</th><th>Telefoonnummer</th><th>Peko</th><th>Zadel</th><th>Interne opmerkingen</th><th>Bijzonderheden</th></tr>');
						for(r in response['data'])
						{
							var tr = $('<tr>');
							for(c in response['data'][r])
							{
								if(r == 0)
									var thd = $('<td class="disabled">');
								else
									var thd = $('<td>');
								thd.html(response['data'][r][c]);
								thd.appendTo(tr);
							}
							tr.appendTo(tbl);
						}
						if(response['num_rows'] > 10)
							tbl.append('<tr><td colspan="999" class="center_text">Nog ' + (response['num_rows'] - 10) + ' rijen</td></tr>');

						$('.output_window').html('<h2>Voorbeeldweergave</h2>');
						$('.output_window').append(tbl);
						$('.output_window').append('<br /><br /><i>Totaal ' + response['num_rows'] + ' rijen</i>');
						$('.output_window').append('<br /><br /><label for="skip_first_row"><input id="skip_first_row" checked type="checkbox" /> Eerste rij overslaan</label>');
						$('.output_window').append('<span id="project_number_holder"><br /><input id="project_number" class="project_number" placeholder="Projectnummer" /><br /></span>');
						$('.output_window').append('<span id="list_name_holder"><br /><input id="list_name" class="list_name" placeholder="Vul de naam van de lijst in" /></span>');

						$('.output_window').append('<br /><br /><label for="add_to_exsisting_list"><input id="add_to_exsisting_list" type="checkbox" /> Toevoegen aan bestaande lijst</label>');

						<?php
							foreach($project_lists as $project_list)
							{
								$project_lists_string .= '<option value="' . $project_list['Project_list']['id'] . '">' . str_replace('\'', '', str_replace('"', '', $project_list['Project_list']['name'])) . '</option>';
							}
						?>
						$('.output_window').append('<span id="exsisting_lists_holder"><br /><select id="exsisting_lists"><?=$project_lists_string?></select></span>');

						$('.output_window').append('<br /><br /><a id="import_list" class="btn" href="/"><span class="fas fa-file-import"></span>Lijst importeren</a>');

						$('#upload_file').html('<span class="fas fa-file-upload"></span>Een nieuw bestand uploaden');
						$('.upload_progress').slideUp(function()
						{

						});
						$('.page_actions').slideDown();

						$('#project_number').focus();
						$('#list_name').keydown(function(e)
						{
							if(e.keyCode == 13)
								submit_list(filename);
						});
						$('#skip_first_row').change(function()
						{
							var skip_first_row = $('#skip_first_row').prop('checked');
							if(skip_first_row)
								tbl.find('tr').first().next().find('td').addClass('disabled');
							else
								tbl.find('tr').first().next().find('td').removeClass('disabled');

							console.log(tbl.find('tr').first().next());
						});
						$('#import_list').click(function()
						{
							submit_list(filename);
							return false;
						});
						$('#add_to_exsisting_list').change(function()
						{
							var exsisting_list = $(this).prop('checked');
							if(exsisting_list)
							{
								$('#exsisting_lists_holder').show();
								$('#project_number_holder').hide();
								$('#list_name_holder').hide();
							}else
							{
								$('#exsisting_lists_holder').hide();
								$('#project_number_holder').show();
								$('#list_name_holder').show();
							}
						});
					}else
					{
						$('#upload_file').html('<span class="fas fa-file-upload"></span>Een nieuw bestand uploaden');
						$('.uploading_info').hide();
						$('.page_actions').show();
						$('.output_window').html('<div class="error">' + response['error'] + '</div>');
					}
				}
			}
		});


	}

	function submit_list(filename)
	{
		var skip_first_row = $('#skip_first_row').prop('checked');
		var project_number = $('#project_number').val();
		var list_name = $('#list_name').val();
		var add_to_exsisting_list = $('#add_to_exsisting_list').prop('checked');
		var exsisting_list = $('#exsisting_lists').val();

		console.log('import_list');
		console.log(filename);
		console.log('skip_first_row: ' + skip_first_row);
		console.log(list_name);

		$.ajax(
		{
			url: '/ajax/project_lists/import_list',
			type: 'post',
			data: {filename:filename, skip_first_row:skip_first_row, project_number:project_number, list_name:list_name, add_to_exsisting_list:add_to_exsisting_list, exsisting_list:exsisting_list},
			dataType: 'json',
			success: function(response)
			{
				console.log(response);
				if(response['succes'])
				{
					if(response['error'])
						alert(response['error']);
					window.location.href = '/project_lists/details/' + response['list_id'];
				}
			}
		});
	}

</script>
