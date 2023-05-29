
<div class="big_photo">
	<img src="/<?=$controller['get']['temp_filename']?>?<?=time()?>" />
</div>

<div class="app_center">
	<div class="maintext"><?=$client['Client']['street']?> <?=$client['Client']['homenumber']?><?=$client['Client']['addition']?></div>
</div>

<br />

<div class="app_list">
	<div class="app_list_header">
		<?=tl('Kies een foto type ')?>
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

<script>
$(document).ready(function()
{
	back_button('<?=tl('Woning')?>', '/clients/details/<?=$client['Client']['id']?>');
	
	$('.photo_type').click(function()
	{
		var type = $(this).attr('rel').toLowerCase();
		$.getJSON('/ajax/clients/set_photo/<?=$client['Client']['id']?>/' + type + '?temp_filename=<?=$controller['get']['temp_filename']?>', function(response)
		{
			//console.log(response);
			if(response['succes'])
				window.location.href = '/clients/details/<?=$client['Client']['id']?>';
		});
	});
});
</script>