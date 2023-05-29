

<div class="app_center">
	<div class="big_icon"><span class="fas fa-exclamation-triangle"></span></div>
	
	<br />
	
	<div class="subtext"><?=$notification['Notification']['title']?></div>
	<?=$notification['Notification']['content']?>
</div>

<br />


<div class="app_list">
	<div class="app_list_header">
		<?=tl('Acties')?>
	</div>
	
	<div class="app_list_item">
		<?=tl('Afgerond')?>
		<span class="app_list_item_append">
			<span class="app_list_item_checkbox <?=($notification['Notification']['status'] == 'finished' ? 'checked' : '')?>" id="notification_finished">
				<span class="app_list_item_checkbox_cursor"></span>
			</span>
		</span>
	</div>
	
	<a class="app_list_item app_list_item_high">
		<textarea class="app_list_item_textarea" id="notification_remarks" placeholder="<?=tl('Opmerkingen')?>"><?=$notification['Notification']['remarks']?></textarea>
	</a>
	
	<br /><br />
	
	<a class="app_list_item_ghost">
		<div class="iphone_button" id="save_button"><?=tl('Opslaan')?></div>
	</a>
	
	<div style="clear: both;"></div>
	
	<div class="app_center">
		<a href="/"><?=tl('Terug naar agenda')?></a>
	</div>
	
	<br /><br />
	
</div>

<script>
var notification_id = parseInt(<?=$notification['Notification']['id']?>);
$(document).ready(function()
{
	back_button('<?=tl('Agenda')?>', '/');
	
	$('#save_button').click(function()
	{
		var status = ($('#notification_finished').hasClass('checked') ? 'finished' : 'pending');
		var remarks = $('#notification_remarks').val();
		
		console.log(notification_id, status, remarks);
		
		var fd = new FormData();
		fd.append('notification_id', notification_id);
		fd.append('status', status);
		fd.append('remarks', remarks);
		
		$.ajax(
		{
			url: '/ajax/notifications/update_status',
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
					black_popup('<?=tl('Opgeslagen')?>');
					setTimeout(function()
					{
						close_black_popup(function()
						{
							window.location.href = '/';
						});
					}, 500);
				}else
				{
					white_popup('<?=tl('<b>Fout bij opslaan</b><br />Probeer het opnieuw')?>', '<?=tl('OK')?>', function()
					{
						close_white_popup();
					});
				}
			}
		});
	});
});
</script>