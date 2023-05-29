
<div class="app_list">
	
	<!--
	<a class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="far fa-paper-plane"></span>
		</span>
		Push berichten
		<span class="app_list_item_append">
			<span class="app_list_item_checkbox" id="gs">
				<span class="app_list_item_checkbox_cursor"></span>
			</span>
		</span>
	</a>
	-->
	
	<a href="/workers/settings_language" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="fas fa-font"></span>
		</span>
		<?=tl('Taal')?>
		<div class="arrow_right"></div>
	</a>
	
	<a href="/workers/settings_preferences" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="fas fa-sliders-h"></span>
		</span>
		<?=tl('Voorkeuren')?>
		<div class="arrow_right"></div>
	</a>
	
	<a href="/workers/settings_info" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="fas fa-info"></span>
		</span>
		<?=tl('Informatie')?>
		<div class="arrow_right"></div>
	</a>
	
	<a href="/users/logout" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="fas fa-ban"></span>
		</span>
		<?=tl('Uitloggen')?>
	</a>
	
</div>