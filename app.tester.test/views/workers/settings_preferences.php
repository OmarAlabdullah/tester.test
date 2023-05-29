
<div class="app_list">
	
	<a class="app_list_item open_info" rel="settings_route_info">
		<span class="app_list_item_prepend">
			<span class="fas fa-map-marked-alt"></span>
		</span>
		<?=tl('Route weergeven')?>
		<span class="app_list_item_append">
			<span class="app_list_item_checkbox <?=($userLoggedIn['Worker']['show_route'] == 1 ? 'checked' : '')?>" id="settings_route">
				<span class="app_list_item_checkbox_cursor"></span>
			</span>
		</span>
	</a>
	<a class="app_list_item info_box" id="settings_route_info" style="display: none; ">
		<?=tl('Laat de route knop zien bij het adresoverzicht')?>
	</a>
	
	<a class="app_list_item open_info" rel="settings_photos_info">
		<span class="app_list_item_prepend">
			<span class="far fa-images"></span>
		</span>
		<?=tl('Fotos weergeven')?>
		<span class="app_list_item_append">
			<span class="app_list_item_checkbox <?=($userLoggedIn['Worker']['show_photos'] == 1 ? 'checked' : '')?>" id="settings_photos">
				<span class="app_list_item_checkbox_cursor"></span>
			</span>
		</span>
	</a>
	<a class="app_list_item info_box" id="settings_photos_info" style="display: none; ">
		<?=tl('Geef fotos weer bij het adresoverzicht, je kunt dit uitschakelen om mobiele data te besparen')?>
	</a>
	
	<a class="app_list_item open_info" rel="settings_force_camera_info">
		<span class="app_list_item_prepend">
			<span class="fas fa-camera-retro"></span>
		</span>
		<?=tl('Forceer camera')?>
		<span class="app_list_item_append">
			<span class="app_list_item_checkbox <?=($userLoggedIn['Worker']['force_camera'] == 1 ? 'checked' : '')?>" id="settings_force_camera">
				<span class="app_list_item_checkbox_cursor"></span>
			</span>
		</span>
	</a>
	<a class="app_list_item info_box" id="settings_force_camera_info" style="display: none; ">
		<?=tl('Forceer de camera direct een foto te maken, je kunt dan geen fotos uit je bibliotheek gebruiken')?>
	</a>
	
</div>

<script>
$(document).ready(function()
{
	back_button('<?=tl('Instellingen')?>', '/workers/settings');
	
	$('#settings_route').change(function()
	{
		$.getJSON('/ajax/workers/setting/show_route/' + ($(this).hasClass('checked') ? '1' : '0'), function(response)
		{
			
		});
	});
	$('#settings_photos').change(function()
	{
		$.getJSON('/ajax/workers/setting/show_photos/' + ($(this).hasClass('checked') ? '1' : '0'), function(response)
		{
			
		});
	});
	$('#settings_force_camera').change(function()
	{
		$.getJSON('/ajax/workers/setting/force_camera/' + ($(this).hasClass('checked') ? '1' : '0'), function(response)
		{
			
		});
	});
	
	$('.open_info').click(function(e)
	{
		if($(e.target).closest('.app_list_item_append').length == 0)
		{
			var jObj = $('#' + $(this).attr('rel'));
			jObj.toggleClass('expanded');
			if(jObj.hasClass('expanded'))
			{
				jObj.slideDown();
			}else
			{
				jObj.slideUp();
			}
		}
	});
});
</script>
<style>
.info_box
{
	color: #999999 !important;
	font-style: italic;
	padding: 15px 30px;
	white-space: normal;
	height: auto;
	line-height: 25px;
}
</style>