

<div class="app_center">
	<div class="big_icon"><span class="fas fa-home"></span></div>
	<div class="maintext"><?=$client['Client']['street']?> <?=$client['Client']['homenumber']?><?=strtoupper($client['Client']['addition'])?></div>
	<div class="maintext_secondary"><?=$client['Client']['zipcode']?> &nbsp;<?=$client['Client']['city']?></div>
	<?=(!empty($client['Client']['phone']) ? '<div class="subtext"><a href="tel:' . $client['Client']['phone'] . '">' . $client['Client']['phone'] . '</a> ' . (!empty($client['Client']['phone2']) ? ' / <a href="tel:' . $client['Client']['phone2'] . '">' . $client['Client']['phone2'] . '</a>' : '') . '</div>' : '')?>
	
	<br />
	<?php
		if(!empty($client['Client']['peko']) || !empty($client['Client']['zadel']))
		{
	?>
	<div class="subtext">Peko: <?=$client['Client']['peko']?> Zadel: <?=$client['Client']['zadel']?></div>
	<?php
		}
		
		
		if(!empty($client['Client']['remarks']))
		{
	?>
	<div class="subtext"><?=tl('Bijzonderheden')?>:</div>
	<?=$client['Client']['remarks']?>
	<?php
		}
	?>
</div>

<br />


<div class="app_list">
	<div class="app_list_header">
		<?=tl('Acties')?>
	</div>
	
	<a href="/clients/photo/<?=$client['Client']['id']?>" class="app_list_item <?=($client['Client']['finished'] == 1 ? 'app_list_finished' : 'app_list_alert_small')?>">
		<span class="app_list_item_prepend">
			<span class="fas fa-camera-retro"></span>
		</span>
		<?=tl('Upload foto\'s')?>
		<div class="arrow_right"></div>
	</a>
	
	<?php
		if($userLoggedIn['Worker']['can_input_costs'] == 1)
		{
	?>
	<a href="/clients/costs/<?=$client['Client']['id']?>" class="app_list_item <?=($client['Client']['extra_information'] == 1 ? 'app_list_finished' : 'app_list_alert_small')?>">
		<span class="app_list_item_prepend">
			<span class="fas fa-chart-bar"></span>
		</span>
		<?=tl('Extra gegevens doorgeven')?>
		<div class="arrow_right"></div>
	</a>
	<?php
		}
	?>
	
	<a href="/clients/documents/<?=$client['Client']['id']?>" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="far fa-folder-open"></span>
		</span>
		<?=tl('Documenten')?>
		<div class="arrow_right"></div>
	</a>
	
	<?php
		if($userLoggedIn['Worker']['show_route'] == 1)
		{
			$navigate_to = $client['Client']['street'] . ' ' . $client['Client']['homenumber'] . strtoupper($client['Client']['addition']) . ' ' . $client['Client']['city'];
			$navigate_to = str_replace(' ', '+', $navigate_to);
	?>
	<a id="list_item_route" href="http://maps.apple.com/?saddr=My+Location&daddr=<?=$navigate_to?>&dirflg=d" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="fas fa-map-marked-alt"></span>
		</span>
		<?=tl('Route')?>
		<div class="arrow_right"></div>
	</a>
	<?php
		}
	?>
	
</div>

<?php
	if($userLoggedIn['Worker']['show_photos'] == 1)
	{
		/*
		$photos = array();
		$files = scandir('files');
		foreach($files as $file)
		{
			if($file != '.' && $file != '..' && $file != 'photo.jpg')
			{
				$pathinfo = pathinfo($file);
				$ext = strtolower($pathinfo['extension']);
				if($ext == 'jpg')
				{
					$photos[] = $file;
				}
			}
		}
		if(count($photos) > 0)
		{
?>
<div class="app_list" id="list_photos">
	<div class="app_list_header">
		<?=tl('Fotos')?>
	</div>
	<div class="app_photo_holder">
<?php
			foreach($photos as $photo)
			{
?>
<img class="open_full_screen" src="/files/<?=$photo?>?<?=time()?>" />
<?php
			}
?>
	</div>
</div>
<?php
		}
		*/
		if(count($photos) > 0)
		{
?>
<div class="app_list" id="list_photos">
	<div class="app_list_header">
		<?=tl('Fotos')?>
	</div>
	<div class="app_photo_holder">
<?php
			foreach($photos as $photo)
			{
?>
<img class="open_full_screen" rel="<?=$photo['Photo']['type']?>" src="/photos/<?=$photo['Photo']['project_list_id']?>/<?=$photo['Photo']['id']?>.<?=$photo['Photo']['ext']?>" />
<?php
			}
?>
	</div>
</div>
<?php
		}
	}
?>


<script>
$(document).ready(function()
{
	back_button('<?=tl('Agenda')?>', '/');
});
</script>