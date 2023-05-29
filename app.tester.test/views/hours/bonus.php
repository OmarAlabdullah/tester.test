
<?php
	$balance = $starting_balance;
?>

<div class="app_list">
	
	<?php
		if($bonus_transaction)
		{
			if($balance != 0)
			{
	?>
	<a class="app_list_item">
		<?=date('d-m-Y', strtotime($bonus_transaction['Bonus_transaction']['date']))?>
		<span class="app_list_item_append_input double_width">
			&euro; <?=number_format($balance * $userLoggedIn['Worker']['wage'], 2, ',', '.')?>
		</span>
	</a>
	<?php
			}
		}
		
		foreach($bonus_hours as $bonus_hour)
		{
	?>
	<a class="app_list_item">
		<?=date('d-m-Y', strtotime($bonus_hour['Bonus_hour']['date']))?>
		<span class="app_list_item_append_input double_width">
			&euro; <?=number_format($bonus_hour['Bonus_hour']['hours'] * $userLoggedIn['Worker']['wage'], 2, ',', '.')?>
		</span>
	</a>
	<?php
		}
	?>
	
	<div style="clear: both;"></div>
	
	<?php
		if($current_bonus_hours['balance'] > 0)
		{
	?>
	<div class="app_center"><?=tl('Opgebouwde bonus')?>:</div>
	<a class="bonus_button"><?=($current_bonus_hours['balance_eur'] > 0 ? '&euro; ' . number_format($current_bonus_hours['balance_eur'], 2, ',', '.') . '' : $current_bonus_hours['balance'] . ' uur')?></a>
	<?php
		}
	?>
	
	
	
</div>

<script>
$(document).ready(function()
{
	back_button('<?=tl('Uren')?>', '/hours/overview');
	
});
</script>