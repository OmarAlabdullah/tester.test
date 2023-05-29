<h1>Bonus uren</h1>
<h5><?=$worker['Worker']['name']?></h5>

<div class="page_actions">
	<a class="btn" href="/hours/overview"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>

<br />

Weergave: 
<select id="type_view">
	<option value="day">Per dag</option>
	<option value="week">Per week</option>
	<option value="month">Per maand</option>
</select>

<br /><br />

<?php
	$balance = $starting_balance;
	$bonus_hours_per_week = array();
	$bonus_hours_per_month = array();
?>

<table class="sum_table" id="sum_per_day">
	<tr>
		<th>Datum</th>
		<th>Transactie</th>
		<th>Saldo</th>
		<th>&euro;</th>
		<th>&nbsp;</th>
	</tr>
	
	<?php
		if($bonus_transaction)
		{
	?>
	<tr>
		<td><?=get_date_formatted($bonus_transaction['Bonus_transaction']['date'])?></td>
		<td>Nieuw saldo</td>
		<td><?=(float)$bonus_transaction['Bonus_transaction']['balance']?></td>
		<td><?=($worker['Worker']['wage'] > 0 ? '&euro; ' . number_format($balance * $worker['Worker']['wage'], 2, ',', '.') : '&nbsp;')?></td>
		<td><span class="fas fa-times remove_bonus_transaction" rel="<?=(int)$bonus_transaction['Bonus_transaction']['id']?>"></span></td>
	</tr>
	<?php
		}
		
		if(count($bonus_hours) > 0)
		{
			foreach($bonus_hours as $bonus_hour)
			{
				$balance += $bonus_hour['Bonus_hour']['hours'];
				
				$week_nr = (int)date('W', strtotime($bonus_hour['Bonus_hour']['date']));
				$bonus_hours_per_week[$week_nr] += $bonus_hour['Bonus_hour']['hours'];
				
				$month = (int)date('m', strtotime($bonus_hour['Bonus_hour']['date']));
				$bonus_hours_per_month[$month] += $bonus_hour['Bonus_hour']['hours'];
	?>
	<tr>
		<td><?=get_date_formatted($bonus_hour['Bonus_hour']['date'])?></td>
		<td><?=(float)$bonus_hour['Bonus_hour']['hours']?></td>
		<td><?=(float)$balance?></td>
		<td><?=($worker['Worker']['wage'] > 0 ? '&euro; ' . number_format($balance * $worker['Worker']['wage'], 2, ',', '.') : '&nbsp;')?></td>
		<td>&nbsp;</td>
	</tr>
	<?php
			}
		}
	?>
	
</table>

<?php
	$balance = $starting_balance;
?>

<table class="sum_table" id="sum_per_week">
	<tr>
		<th>Datum</th>
		<th>Transactie</th>
		<th>Saldo</th>
		<th>&euro;</th>
		<th>&nbsp;</th>
	</tr>
	
	<?php
		if($bonus_transaction)
		{
	?>
	<tr>
		<td><?=get_date_formatted($bonus_transaction['Bonus_transaction']['date'])?></td>
		<td>Nieuw saldo</td>
		<td><?=(float)$bonus_transaction['Bonus_transaction']['balance']?></td>
		<td><?=($worker['Worker']['wage'] > 0 ? '&euro; ' . number_format($balance * $worker['Worker']['wage'], 2, ',', '.') : '&nbsp;')?></td>
		<td><span class="fas fa-times remove_bonus_transaction" rel="<?=(int)$bonus_transaction['Bonus_transaction']['id']?>"></span></td>
	</tr>
	<?php
		}
		
		if(count($bonus_hours_per_week) > 0)
		{
			foreach($bonus_hours_per_week as $week_nr => $bonus_hour_per_week)
			{
				$balance += $bonus_hour_per_week;
	?>
	<tr>
		<td>Week <?=$week_nr?></td>
		<td><?=(float)$bonus_hour_per_week?></td>
		<td><?=(float)$balance?></td>
		<td><?=($worker['Worker']['wage'] > 0 ? '&euro; ' . number_format($balance * $worker['Worker']['wage'], 2, ',', '.') : '&nbsp;')?></td>
		<td>&nbsp;</td>
	</tr>
	<?php
			}
		}
	?>
	
</table>

<?php
	$balance = $starting_balance;
?>

<table class="sum_table" id="sum_per_month">
	<tr>
		<th>Datum</th>
		<th>Transactie</th>
		<th>Saldo</th>
		<th>&euro;</th>
		<th>&nbsp;</th>
	</tr>
	
	<?php
		if($bonus_transaction)
		{
	?>
	<tr>
		<td><?=get_date_formatted($bonus_transaction['Bonus_transaction']['date'])?></td>
		<td>Nieuw saldo</td>
		<td><?=(float)$bonus_transaction['Bonus_transaction']['balance']?></td>
		<td><?=($worker['Worker']['wage'] > 0 ? '&euro; ' . number_format($balance * $worker['Worker']['wage'], 2, ',', '.') : '&nbsp;')?></td>
		<td><span class="fas fa-times remove_bonus_transaction" rel="<?=(int)$bonus_transaction['Bonus_transaction']['id']?>"></span></td>
	</tr>
	<?php
		}
		
		if(count($bonus_hours_per_month) > 0)
		{
			foreach($bonus_hours_per_month as $month => $bonus_hour_per_month)
			{
				$balance += $bonus_hour_per_month;
	?>
	<tr>
		<td><?=get_month_by_int($month)?></td>
		<td><?=(float)$bonus_hour_per_month?></td>
		<td><?=(float)$balance?></td>
		<td><?=($worker['Worker']['wage'] > 0 ? '&euro; ' . number_format($balance * $worker['Worker']['wage'], 2, ',', '.') : '&nbsp;')?></td>
		<td>&nbsp;</td>
	</tr>
	<?php
			}
		}
	?>
	
</table>

<br /><br />

<a class="btn" id="add_bonus_transaction" href="<?=SELF?>"><span class="fas fa-plus-square"></span>Nieuw saldo</a>

<script>
$(document).ready(function()
{
	var local_type_view = localStorage.getItem('bonus_hours_type_view');
	if(local_type_view != null)
	{
		set_type_view(local_type_view);
		$('#type_view').val(local_type_view);
	}
	$('#type_view').change(function()
	{
		var vl = $(this).val();
		set_type_view(vl);
	});
	$('#add_bonus_transaction').click(function()
	{
		popup('<h3>Nieuw saldo toevoegen</h3>Datum<br /><input class="bonus_transaction_date" type="date" value="<?=date('Y-m-d')?>" /><br /><br />Uren-saldo op datum<br /><input class="bonus_transaction_balance" type="text" value="0" style="width: 100px; text-align: center; " /><br /><br /><br /><a href="<?=SELF?>" class="btn popup_back"><span class="fas fa-arrow-alt-circle-left"></span>Terug</a> &nbsp; <a href="<?=SELF?>" class="btn btn-accept popup_save"><span class="fas fa-check-circle"></span>Toevoegen</a>');
		$('.popup_back').click(function()
		{
			close_popup();
			return false;
		});
		$('.popup_save').click(function()
		{
			var worker_id = parseInt(<?=$worker['Worker']['id']?>);
			var bonus_transaction_date = $('.bonus_transaction_date:visible').first().val();
			var bonus_transaction_balance = parseFloat($('.bonus_transaction_balance:visible').first().val().replace(',', '.'));
			bonus_transaction_balance = Math.round(bonus_transaction_balance * 4) / 4;
			
			if(worker_id > 0 && bonus_transaction_date.length == 10)
			{
				$.ajax(
				{
					url: '/ajax/hours/add_bonus_transaction',
					type: 'post',
					dataType: 'json',
					data: {worker_id:worker_id, date:bonus_transaction_date, balance:bonus_transaction_balance},
					success: function(response)
					{
						if(response['succes'])
						{
							location.reload(true);
						}
					}
				});
			}
			
			return false;
		});
		
		return false;
	});
	$('.remove_bonus_transaction').click(function()
	{
		var bonus_transaction_id = parseInt($(this).attr('rel'));
		popup('<h3>Uitbetaling verwijderen?</h3><br /><br /><br /><a href="<?=SELF?>" class="btn popup_back"><span class="fas fa-arrow-alt-circle-left"></span>Terug</a> &nbsp; <a href="<?=SELF?>" class="btn btn-alert popup_remove"><span class="fas fa-times-circle"></span>Verwijderen</a>');
		$('.popup_back').click(function()
		{
			close_popup();
			return false;
		});
		$('.popup_remove').click(function()
		{
			if(bonus_transaction_id > 0)
			{
				$.getJSON('/ajax/hours/remove_bonus_transaction/' + bonus_transaction_id, function(response)
				{
					if(response['succes'])
					{
						location.reload(true);
					}
				});
			}
			
			return false;
		});
		
		return false;
	});
});
function set_type_view(tpe)
{
	$('.sum_table').hide();
	$('#sum_per_' + tpe).show();
	
	localStorage.setItem('bonus_hours_type_view', tpe);
}
</script>

<style>
#sum_per_week
{
	display: none;
}
#sum_per_month
{
	display: none;
}
.sum_table tr td:nth-child(4)
{
	width: 100px;
	text-align: right;
}
.sum_table tr td:nth-child(5)
{
	width: 100px;
	text-align: right;
	padding-right: 20px;
}
.sum_table tr td:nth-child(5) .fas
{
	cursor: pointer;
}
</style>