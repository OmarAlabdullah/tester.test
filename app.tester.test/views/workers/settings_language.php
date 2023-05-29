
<div class="app_list">
	
	<a href="/" class="app_list_item" rel="1">
		<span class="app_list_item_prepend">
			<span class="fas fa-check <?=($userLoggedIn['Worker']['language_id'] == 1 ? 'fa-blue' : 'fa-grey')?>"></span>
		</span>
		Nederlands
	</a>
	
	<a href="/" class="app_list_item" rel="2">
		<span class="app_list_item_prepend">
			<span class="fas fa-check <?=($userLoggedIn['Worker']['language_id'] == 2 ? 'fa-blue' : 'fa-grey')?>"></span>
		</span>
		Englisch
	</a>
	<!--
	<a href="/" class="app_list_item" rel="3">
		<span class="app_list_item_prepend">
			<span class="fas fa-check <?=($userLoggedIn['Worker']['language_id'] == 3 ? 'fa-blue' : 'fa-grey')?>"></span>
		</span>
		Lituan
	</a>
	-->
	<div style="clear: both;"></div>
	
	<div class="app_center">
		<a href="/workers/settings"><?=tl('Terug naar instellingen')?></a>
	</div>
	
	<br /><br />
	
</div>

<script>
$(document).ready(function()
{
	back_button('<?=tl('Instellingen')?>', '/workers/settings');
	$('.app_list_item').click(function()
	{
		var language_id = parseInt($(this).attr('rel'));
		if(language_id > 0)
		{
			$('.app_list_item').find('.fa-blue').removeClass('fa-blue').addClass('fa-grey');
			$(this).find('.fa-grey').removeClass('fa-grey').addClass('fa-blue');
			
			$('.app_list_item').removeClass('selected');
			$(this).addClass('selected');
			
			if(language_id != <?=$userLoggedIn['Worker']['language_id']?>) //check for change in language
			{
				setTimeout(function()
				{
					black_popup('<?=tl('Bezig met taal aan het instellen...')?>');
					
					$.getJSON('/ajax/workers/setting_int/language_id/' + language_id, function(response)
					{
						location.reload(true);
					});
				}, 250);
			}
			
			return false;
		}
	});
});
</script>