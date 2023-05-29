<h1>Uren</h1>
<h5>Uren week <?=$week_number?></h5>

<?php
/*
?>

<br /><br />

<table>
	<tr>
		<th>Naam</th>
		<th>Maandag <?=date('d M', strtotime($monday))?></th>
		<th>Dinsdag <?=date('d M', strtotime(_pd($monday, 1)))?></th>
		<th>Woensdag <?=date('d M', strtotime(_pd($monday, 2)))?></th>
		<th>Donderdag <?=date('d M', strtotime(_pd($monday, 3)))?></th>
		<th>Vrijdag <?=date('d M', strtotime(_pd($monday, 4)))?></th>
		<th>Zaterdag <?=date('d M', strtotime(_pd($monday, 5)))?></th>
		<th>Zondag <?=date('d M', strtotime(_pd($monday, 6)))?></th>
		<th>Totaal</th>
		<th>&nbsp;</th>
	</tr>
	<?php
		if(count($parsed_hours) == 0)
		{
	?>
	<tr>
		<td colspan="10"><i>Geen uren opgegeven</i></td>
	</tr>
	<?php
		}
		$total_wage = 0.0;
		foreach($parsed_hours as $worker_id => $data)
		{
			$worker = $db->first('workers', $worker_id);
			$total = 0;
			foreach($data['normal'] as $date => $hours)
				$total += (float)$hours;
	?>
	<tr>
		<td><?=$worker['Worker']['name']?></td>
		<td><?=_pn($data['normal'][$monday])?> <?=($data['bonus'][$monday] > 0 ? '<span class="bonus"><span class="fas fa-star"></span> ' . _pn($data['bonus'][$monday]) . '</span>' : '')?></td>
		<td><?=_pn($data['normal'][_pd($monday, 1)])?> <?=($data['bonus'][_pd($monday, 1)] > 0 ? '<span class="bonus"><span class="fas fa-star"></span> ' . _pn($data['bonus'][_pd($monday, 1)]) . '</span>' : '')?></td>
		<td><?=_pn($data['normal'][_pd($monday, 2)])?> <?=($data['bonus'][_pd($monday, 2)] > 0 ? '<span class="bonus"><span class="fas fa-star"></span> ' . _pn($data['bonus'][_pd($monday, 2)]) . '</span>' : '')?></td>
		<td><?=_pn($data['normal'][_pd($monday, 3)])?> <?=($data['bonus'][_pd($monday, 3)] > 0 ? '<span class="bonus"><span class="fas fa-star"></span> ' . _pn($data['bonus'][_pd($monday, 3)]) . '</span>' : '')?></td>
		<td><?=_pn($data['normal'][_pd($monday, 4)])?> <?=($data['bonus'][_pd($monday, 4)] > 0 ? '<span class="bonus"><span class="fas fa-star"></span> ' . _pn($data['bonus'][_pd($monday, 4)]) . '</span>' : '')?></td>
		<td><?=_pn($data['normal'][_pd($monday, 5)])?> <?=($data['bonus'][_pd($monday, 5)] > 0 ? '<span class="bonus"><span class="fas fa-star"></span> ' . _pn($data['bonus'][_pd($monday, 5)]) . '</span>' : '')?></td>
		<td><?=_pn($data['normal'][_pd($monday, 6)])?> <?=($data['bonus'][_pd($monday, 6)] > 0 ? '<span class="bonus"><span class="fas fa-star"></span> ' . _pn($data['bonus'][_pd($monday, 6)]) . '</span>' : '')?></td>
		<td><?=_pn($total)?></td>
		<?php
			$wage = $total * $worker['Worker']['wage'];
			$total_wage += $wage;
		?>
		<td style="text-align: right; "><?=($worker['Worker']['wage'] <> 0.0 ? '&euro; ' . number_format($wage, 2, ',', '.') : '')?> &nbsp;</td>
	</tr>
	<?php
		}
		if($total_wage <> 0.0)
		{
	?>
	<tr>
		<td colspan="9"><b>Totaal loon</b></td>
		<td style="text-align: right; "><b>&euro; <?=number_format($total_wage, 2, ',', '.')?> &nbsp;</b></td>
	</tr>
	<?php
		}
	?>
</table>
<?php
*/
?>

<br /><br />

<table>
	<tr>
		<th>Naam</th>
		<th>Maandag <?=date('d M', strtotime($monday))?></th>
		<th>Dinsdag <?=date('d M', strtotime(_pd($monday, 1)))?></th>
		<th>Woensdag <?=date('d M', strtotime(_pd($monday, 2)))?></th>
		<th>Donderdag <?=date('d M', strtotime(_pd($monday, 3)))?></th>
		<th>Vrijdag <?=date('d M', strtotime(_pd($monday, 4)))?></th>
		<th>Totaal</th>
		<th>&nbsp;</th>
	</tr>
	<?php
		if(count($parsed_hours) == 0)
		{
	?>
	<tr>
		<td colspan="10"><i>Geen uren opgegeven</i></td>
	</tr>
	<?php
		}
		$total_wage = 0.0;
		foreach($parsed_hours as $worker_id => $data)
		{
			$worker = $db->first('workers', $worker_id);
			$total = 0;
			foreach($data['normal'] as $date => $hours)
				$total += (float)$hours;
	?>
	<tr>
		<td><a href="/hours/details/<?=$worker['Worker']['id']?>"><?=$worker['Worker']['name']?></a></td>
		<td class="input_holder">
			<input type="text" class="hours_normal" rel="<?=$monday?>" worker_id="<?=$worker_id?>" value="<?=_pn($data['normal'][$monday])?>" />
			<input id="bonus_hours_mon_<?=$worker_id?>" type="text" class="hours_bonus" rel="<?=$monday?>" worker_id="<?=$worker_id?>" value="<?=_pn($data['bonus'][$monday])?>" />
			<label for="bonus_hours_mon_<?=$worker_id?>"><i class="fas fa-star"></i></label>
		</td>
		<td class="input_holder">
			<input type="text" class="hours_normal" rel="<?=_pd($monday, 1)?>" worker_id="<?=$worker_id?>" value="<?=_pn($data['normal'][_pd($monday, 1)])?>" />
			<input id="bonus_hours_tue_<?=$worker_id?>" type="text" class="hours_bonus" rel="<?=_pd($monday, 1)?>" worker_id="<?=$worker_id?>" value="<?=_pn($data['bonus'][_pd($monday, 1)])?>" />
			<label for="bonus_hours_tue_<?=$worker_id?>"><i class="fas fa-star"></i></label>
		</td>
		<td class="input_holder">
			<input type="text" class="hours_normal" rel="<?=_pd($monday, 2)?>" worker_id="<?=$worker_id?>" value="<?=_pn($data['normal'][_pd($monday, 2)])?>" />
			<input id="bonus_hours_wed_<?=$worker_id?>" type="text" class="hours_bonus" rel="<?=_pd($monday, 2)?>" worker_id="<?=$worker_id?>" value="<?=_pn($data['bonus'][_pd($monday, 2)])?>" />
			<label for="bonus_hours_wed_<?=$worker_id?>"><i class="fas fa-star"></i></label>
		</td>
		<td class="input_holder">
			<input type="text" class="hours_normal" rel="<?=_pd($monday, 3)?>" worker_id="<?=$worker_id?>" value="<?=_pn($data['normal'][_pd($monday, 3)])?>" />
			<input id="bonus_hours_thu_<?=$worker_id?>" type="text" class="hours_bonus" rel="<?=_pd($monday, 3)?>" worker_id="<?=$worker_id?>" value="<?=_pn($data['bonus'][_pd($monday, 3)])?>" />
			<label for="bonus_hours_thu_<?=$worker_id?>"><i class="fas fa-star"></i></label>
		</td>
		<td class="input_holder">
			<input type="text" class="hours_normal" rel="<?=_pd($monday, 4)?>" worker_id="<?=$worker_id?>" value="<?=_pn($data['normal'][_pd($monday, 4)])?>" />
			<input id="bonus_hours_fri_<?=$worker_id?>" type="text" class="hours_bonus" rel="<?=_pd($monday, 4)?>" worker_id="<?=$worker_id?>" value="<?=_pn($data['bonus'][_pd($monday, 4)])?>" />
			<label for="bonus_hours_fri_<?=$worker_id?>"><i class="fas fa-star"></i></label>
		</td>
		<td><?=_pn($total)?></td>
		<?php
			$wage = $total * $worker['Worker']['wage'];
			$total_wage += $wage;
		?>
		<td style="text-align: right; "><?=($worker['Worker']['wage'] <> 0.0 ? '&euro; ' . number_format($wage, 2, ',', '.') : '')?> &nbsp;</td>
	</tr>
	<?php
		}
		if($total_wage <> 0.0)
		{
	?>
	<tr>
		<td colspan="7"><b>Totaal loon</b></td>
		<td style="text-align: right; "><b>&euro; <?=number_format($total_wage, 2, ',', '.')?> &nbsp;</b></td>
	</tr>
	<?php
		}
	?>
</table>

<div id="calendar_margin_bottom"></div>

<div id="calendar_nav">
	<div class="pagination">
		<a id="prev_week" href="<?=SELF?>?date=<?=_pd($monday, -7)?>" rel="prev"></a>
		<a id="this_week" href="<?=SELF?>?date=<?=_pd($monday)?>" class="selected" style="">Week <?=$week_number?></a>
		<a id="next_week" href="<?=SELF?>?date=<?=_pd($monday, 7)?>" rel="next"></a>
	</div>
</div>

<script>
	var save_que = [];
	var save_timeout = 0;
	$(document).ready(function()
	{
		$(document).keydown(function(e)
		{
			if(e.keyCode == 37)
				_prev_week();
			if(e.keyCode == 39)
				_next_week();
		});
		
		$('#prev_week').click(function()
		{
			_prev_week();
			return false;
		});
		
		$('#this_week').click(function()
		{
			window.location.href = $('#this_week').attr('href');
			return false;
		});
		
		$('#next_week').click(function()
		{
			_next_week();
			return false;
		});
		
		$('.hours_normal').keyup(function()
		{
			add_to_save_que('normal', $(this).attr('worker_id'), $(this).attr('rel'), $(this).val());
			
			clearTimeout(save_timeout);
			save_timeout = setTimeout(function()
			{
				_save_que();
			}, 1000);
		});
		$('.hours_bonus').keyup(function()
		{
			add_to_save_que('bonus', $(this).attr('worker_id'), $(this).attr('rel'), $(this).val());
			
			clearTimeout(save_timeout);
			save_timeout = setTimeout(function()
			{
				_save_que();
			}, 1000);
		});
	});
	function _prev_week()
	{
		window.location.href = $('#prev_week').attr('href');
	}
	function _next_week()
	{
		window.location.href = $('#next_week').attr('href');
	}
	function add_to_save_que(type, worker_id, date, value)
	{
		for(i in save_que)
		{
			if(save_que[i]['worker_id'] == worker_id && save_que[i]['date'] == date && save_que[i]['type'] == type)
				save_que.splice(i, 1);
		}
		save_que.push(
		{
			'type': type,
			'date': date,
			'worker_id': worker_id,
			'value': value
		});
	}
	function _save_que()
	{
		console.log(save_que);
		
		$.ajax(
		{
			url: '/ajax/hours/save_hour_data',
			type: 'post',
			dataType: 'json',
			data: {save_que:save_que},
			success: function(response)
			{
				console.log(response);
				
				if(response['succes'])
				{
					save_que = [];
					
					for(i in response['updates'])
					{
						var value = parseFloat(response['updates'][i]['value']);
						if(value == 0)
							value = '';
						$('.hours_' + response['updates'][i]['type'] + '[rel="' + response['updates'][i]['date'] + '"][worker_id="' + response['updates'][i]['worker_id'] + '"]').val(value);
					}
				}
			}
		});
	}
</script>

<style>
	#calendar_margin_bottom
	{
		height: 50px;
	}
	#calendar_nav
	{
		position: fixed;
		bottom: 0px;
		right: 0px;
		height: 50px;
		
		box-shadow: 0 -5px 5px -5px rgba(0, 0, 0, 0.1);
		width: calc(100% - 300px);
		background-color: #ffffff;
		text-align: center;
		font-size: 31px;
	}
	#this_week
	{
		width: 200px;
	}
	.bonus
	{
		background-color: rgba(250, 181, 20, 0.2);
		color: rgba(250, 181, 20, 0.8);
		font-size: 11px;
		border-radius: 3px;
		padding: 5px 10px;
	}
	.input_holder
	{
		position: relative;
	}
	.hours_normal, .hours_bonus
	{
		width: 60px !important;
		text-align: center;
		border: transparent hidden 0px !important;
		border-radius: 4px;
		background-color: rgba(0, 0, 0, 0.05);
		position: relative;
	}
	.hours_bonus
	{
		background-color: rgba(250, 181, 20, 0.2);
	}
	.hours_bonus + label
	{
		color: rgba(250, 181, 20, 0.2);
		position: absolute;
		left: 96px;
		top: 0px;
	}
</style>