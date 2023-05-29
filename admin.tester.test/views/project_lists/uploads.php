<h1>Uploads</h1>
<h5>Alle bestanden in de map /files/</h5>

<div class="page_actions">
	<a class="btn" href="/"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>


<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th width="100">&nbsp;</th>
		<th>Bestandsnaam</th>
		<th>Gewijzigd op</th>
	</tr>
	<?php		foreach($files as $file)
		{
	?>
	<tr filename="<?=$file['filename']?>">
		<td class="check"><input type="checkbox" /></td>
		<td>
			<?php				switch($file['filetype'])
				{
					case 'excel':
			?>
			<a href="/project_lists/view_xlsx?filename=<?=$file['filename']?>"><span class="fas fa-th-list"></span></a>
			<?php					break;
					case 'image':
			?>
			<span class="fas fa-file-image"></span>
			<?php					break;
					case 'txt':
			?>
			<span class="fas fa-file-alt"></span>
			<?php					break;
					case 'dir':
			?>
			<span class="fas fa-folder"></span>
			<?php					break;
					default:
			?>
			<span class="fas fa-file"></span>
			<?php					break;
				}
			?>
		</td>
		<td><a href="/files/<?=$file['filename']?>"><?=$file['filename']?></a></td>
		<td><?=date('d-m-Y H:i:s', $file['filetime'])?></td>
	</tr>
	<?php		}
	?>
</table>

<div class="page_actions page_actions_bottom">
	<a id="remove_files" class="btn disabled" href="/"><span class="fas fa-times-circle"></span>Bestanden verwijderen</a>
</div>

<?php	//pr($files);
?>

<script>
$(document).ready(function()
{
	$('.check').find('input[type="checkbox"]').click(function()
	{
		update_bottom_buttons();
	});
	$('#remove_files').click(function()
	{
		var files = get_checked_files();
		if(files.length > 0)
		{
			window.location.href = '/project_lists/remove_uploads/?filecount=' + files.length + '&files=' + (files.join(','));
		}
		return false;
	});
});

function update_bottom_buttons()
{
	var total_boxes = 0;
	var checked_boxes = 0
	$('td.check').find('input[type="checkbox"]').each(function()
	{
		total_boxes++;
		if($(this).prop('checked'))
			checked_boxes++;
	});
	if(total_boxes > 0)
		$('th.check').find('input[type="checkbox"]').prop('checked', (checked_boxes == total_boxes));
	
	if(checked_boxes > 0)
		$('.page_actions_bottom').find('.btn').removeClass('disabled');
	else
		$('.page_actions_bottom').find('.btn').addClass('disabled');
}

function get_checked_files()
{
	var filenames = new Array();
	$('td.check').find('input[type="checkbox"]').each(function()
	{
		if($(this).prop('checked'))
		{
			filenames.push($(this).parent().parent().attr('filename'));
		}
	});
	return (filenames);
}
</script>