
<div class="app_list">
	
	<?php
	if(count($day_clients) > 0)
	{
		foreach($day_clients as $day => $clients)
		{
			$pekos = array();
			$zadels = array();
		?>
		<div class="app_list_header">
			<?=ucfirst($day)?>
		</div>
		<?php
			if(count($clients) > 0)
			{
				foreach($clients as $client)
				{
					if($client['Client']['id'] > 0)
					{
						if(!empty($client['Client']['peko']))
							$pekos[str_replace('.', ',', strtoupper($client['Client']['peko']))]++;
						if(!empty($client['Client']['zadel']))
							$zadels[str_replace('.', ',', strtoupper($client['Client']['zadel']))]++;
			?>
			<a href="/clients/details/<?=$client['Client']['id']?>" class="app_list_item">
				<span class="app_list_item_prepend <?=($client['Client']['finished'] && $client['Client']['extra_information'] == 1 ? 'finished' : 'pending')?>">
					<span class="<?=($client['Client']['finished'] ? 'far fa-check-circle' : 'fas fa-circle')?>"></span>
				</span>
				<div class="double_row">
					<b><?=$client['Client']['street']?> <?=$client['Client']['homenumber']?><?=strtoupper($client['Client']['addition'])?></b><br />
					<?=$client['Client']['zipcode']?> &nbsp;<?=$client['Client']['city']?>
				</div>
				<div class="arrow_right"></div>
			</a>
			<?php
					}
					if($client['Notification']['id'] > 0)
					{
			?>
			<a href="/clients/notification/<?=$client['Notification']['id']?>" class="app_list_item app_list_alert_small <?=$client['Notification']['status']?>">
				<span class="app_list_item_prepend">
					<span class="fas <?=($client['Notification']['status'] == 'finished' ? 'fa-exclamation-circle' : 'fa-exclamation-triangle')?>"></span>
				</span>
				<div class="double_row">
					<b><?=$client['Notification']['title']?></b><br />
					<?=$client['Notification']['content']?>
				</div>
				<div class="arrow_right"></div>
			</a>
			<?php
					}
				}
				if(count($pekos) > 0 || count($zadels) > 0)
				{
			?>
			<div class="app_list_item info_box">
				<?php
					if(count($pekos) > 0)
					{
				?>
				<div>
				<b>PEKO:</b> &nbsp;
				<?php
						foreach($pekos as $peko => $amount)
						{
				?>
				<span class="peko_zadel_block peko_block"><span><?=$amount?>x</span> <?=$peko?></span>
				<?php
						}
				?>
			</div>
				<?php
					}
					if(count($zadels) > 0)
					{
				?>
				<div>
				<b>ZADEL:</b> &nbsp;
				<?php
						foreach($zadels as $zadel => $amount)
						{
				?>
				<span class="peko_zadel_block zadel_block"><span><?=$amount?>x</span> <?=$zadel?></span>
				<?php
						}
				?>
			</div>
				<?php
					}
				?>
			</div>
			<?php
				}
				/*
				foreach($day_notifications[$day] as $notification)
				{
			?>
			<a href="/clients/notification/<?=$notification['Notification']['id']?>" class="app_list_item app_list_alert_small <?=$notification['Notification']['status']?>">
				<span class="app_list_item_prepend">
					<span class="fas <?=($notification['Notification']['status'] == 'finished' ? 'fa-exclamation-circle' : 'fa-exclamation-triangle')?>"></span>
				</span>
				<div class="double_row">
					<b><?=$notification['Notification']['title']?></b><br />
					<?=$notification['Notification']['content']?>
				</div>
				<div class="arrow_right"></div>
			</a>
			<?php
				}
				*/
			}else
			{
			?>
			<div style="height: 50px; line-height: 50px; text-align: center; color: #999999; "><?=tl('Geen afspraken')?></div>
			<?php
			}
		}
	}else
	{
?>
<div class="full_body_center">
	<?=tl('Geen afspraken')?>
</div>
<?php
	}
?>

<div style="clear: both;"></div>

<br /><br />

<div class="prev_next">
	<a href="/" class="prev_week" rel="<?=date('Y-m-d', strtotime(_pd($monday, -7)))?>"><span class="fas fa-caret-left"></span> <?=tl('Week')?> <?=(int)date('W', strtotime(_pd($monday, -7)))?></a>
	<a href="/" class="next_week" rel="<?=date('Y-m-d', strtotime(_pd($monday, 7)))?>"><?=tl('Week')?> <?=(int)date('W', strtotime(_pd($monday, 7)))?> <span class="fas fa-caret-right"></span></a>
</div>

<div style="clear: both;"></div>
	
<br /><br />

</div>

<script>
$(document).ready(function()
{
	if(localStorage.getItem('notification_finished') == '1')
		$('#noti').addClass('finished');
	
	$('.prev_week').click(function()
	{
		var new_date = $(this).attr('rel');
		$.getJSON('/ajax/clients/change_home_date/' + new_date, function(response)
		{
			if(response['succes'])
				location.reload(true);
		});
		return false;
	});
	$('.next_week').click(function()
	{
		var new_date = $(this).attr('rel');
		$.getJSON('/ajax/clients/change_home_date/' + new_date, function(response)
		{
			if(response['succes'])
				location.reload(true);
		});
		return false;
	});
});
</script>
<style>
.info_box
{
	color: #666666 !important;
	padding: 15px 30px;
	white-space: normal;
	height: auto;
	line-height: 25px;
}
.peko_zadel_block
{
	font-size: 13px;
	background-color: #eeeeee;
	padding: 2px 4px;
	border-radius: 2px;
}
.peko_zadel_block span
{
	
}
.zadel_block
{
	background-color: #CBF1FE;
}
</style>