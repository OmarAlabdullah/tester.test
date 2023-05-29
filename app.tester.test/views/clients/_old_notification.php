

<div class="app_center">
	<div class="big_icon"><span class="fas fa-home"></span></div>
	<div class="maintext">Valeriusstraat 23<br />2394 XL  Hazerswoude Rijndijk</div>
	<div class="subtext"><a href="tel:0614275442">0614275442</a></div>
	
	<br />
	
	<div class="subtext"><?=tl('Bijzonderheden')?>:</div>
	Langsgaan en aangeven wat er weggehaald moet worden uit de kelder
</div>

<br />


<div class="app_list">
	<div class="app_list_header">
		<?=tl('Acties')?>
	</div>
	
	<div class="app_list_item">
		<?=tl('Afgerond')?>
		<span class="app_list_item_append">
			<span class="app_list_item_checkbox" id="notification_finished">
				<span class="app_list_item_checkbox_cursor"></span>
			</span>
		</span>
	</div>
	
	<a class="app_list_item app_list_item_high">
		<textarea class="app_list_item_textarea" id="notification_remarks" placeholder="<?=tl('Opmerkingen')?>"></textarea>
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
$(document).ready(function()
{
	back_button('<?=tl('Agenda')?>', '/');
	
	if(localStorage.getItem('notification_finished') == '1')
		$('#notification_finished').addClass('checked');
	
	$('#notification_remarks').val(localStorage.getItem('notification_remarks'));
	
	$('#notification_finished').change(function()
	{
		//localStorage.setItem('notification_finished', ($(this).hasClass('checked') ? '1' : '0'));
	});
	$('#save_button').click(function()
	{
		localStorage.setItem('notification_finished', ($('#notification_finished').hasClass('checked') ? '1' : '0'));
		localStorage.setItem('notification_remarks', $('#notification_remarks').val());
		window.location.href = '/';
	});
});
</script>