
<div class="app_list">
	
	<div class="app_list_header">
		<?=tl('Week')?> <?=$week_number?>
	</div>
	
	<a class="app_list_item <?=($monday == date('Y-m-d') ? 'highlighted' : '')?>">
		<?=tl('Ma')?> <?=date('d M', strtotime($monday))?>
		<span class="app_list_item_append_input double_width">
			<input id="bonus_hours_mon" name="bonus_hours[<?=$monday?>]" value="<?=$parsed_bonus_hours[$monday]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input app_list_item_text_input_bonus" />
			<label for="bonus_hours_mon"><i class="fas fa-star"></i></label>
			<input name="hours[<?=$monday?>]" value="<?=$parsed_hours[$monday]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input" />
		</span>
	</a>
	
	<a class="app_list_item <?=(_pd($monday, 1) == date('Y-m-d') ? 'highlighted' : '')?>">
		<?=tl('Di')?> <?=date('d M', strtotime(_pd($monday, 1)))?>
		<span class="app_list_item_append_input double_width">
			<input id="bonus_hours_tue" name="bonus_hours[<?=_pd($monday, 1)?>]" value="<?=$parsed_bonus_hours[_pd($monday, 1)]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input app_list_item_text_input_bonus" />
			<label for="bonus_hours_tue"><i class="fas fa-star"></i></label>
			<input name="hours[<?=_pd($monday, 1)?>]" value="<?=$parsed_hours[_pd($monday, 1)]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input" />
		</span>
	</a>
	
	<a class="app_list_item <?=(_pd($monday, 2) == date('Y-m-d') ? 'highlighted' : '')?>">
		<?=tl('Wo')?> <?=date('d M', strtotime(_pd($monday, 2)))?>
		<span class="app_list_item_append_input double_width">
			<input id="bonus_hours_wed" name="bonus_hours[<?=_pd($monday, 2)?>]" value="<?=$parsed_bonus_hours[_pd($monday, 2)]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input app_list_item_text_input_bonus" />
			<label for="bonus_hours_wed"><i class="fas fa-star"></i></label>
			<input name="hours[<?=_pd($monday, 2)?>]" value="<?=$parsed_hours[_pd($monday, 2)]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input" />
		</span>
	</a>
	
	<a class="app_list_item <?=(_pd($monday, 3) == date('Y-m-d') ? 'highlighted' : '')?>">
		<?=tl('Do')?> <?=date('d M', strtotime(_pd($monday, 3)))?>
		<span class="app_list_item_append_input double_width">
			<input id="bonus_hours_thu" name="bonus_hours[<?=_pd($monday, 3)?>]" value="<?=$parsed_bonus_hours[_pd($monday, 3)]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input app_list_item_text_input_bonus" />
			<label for="bonus_hours_thu"><i class="fas fa-star"></i></label>
			<input name="hours[<?=_pd($monday, 3)?>]" value="<?=$parsed_hours[_pd($monday, 3)]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input" />
		</span>
	</a>
	
	<a class="app_list_item <?=(_pd($monday, 4) == date('Y-m-d') ? 'highlighted' : '')?>">
		<?=tl('Vr')?> <?=date('d M', strtotime(_pd($monday, 4)))?>
		<span class="app_list_item_append_input double_width">
			<input id="bonus_hours_fri" name="bonus_hours[<?=_pd($monday, 4)?>]" value="<?=$parsed_bonus_hours[_pd($monday, 4)]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input app_list_item_text_input_bonus" />
			<label for="bonus_hours_fri"><i class="fas fa-star"></i></label>
			<input name="hours[<?=_pd($monday, 4)?>]" value="<?=$parsed_hours[_pd($monday, 4)]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input" />
		</span>
	</a>
	
	<!--
	<a class="app_list_item <?=(_pd($monday, 5) == date('Y-m-d') ? 'highlighted' : '')?>">
		<?=tl('Za')?> <?=date('d M', strtotime(_pd($monday, 5)))?>
		<span class="app_list_item_append_input double_width">
			<input name="hours[<?=_pd($monday, 5)?>]" value="<?=$parsed_hours[_pd($monday, 5)]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input" />
		</span>
	</a>
	
	<a class="app_list_item <?=(_pd($monday, 6) == date('Y-m-d') ? 'highlighted' : '')?>">
		<?=tl('Zo')?> <?=date('d M', strtotime(_pd($monday, 6)))?>
		<span class="app_list_item_append_input double_width">
			<input name="hours[<?=_pd($monday, 6)?>]" value="<?=$parsed_hours[_pd($monday, 6)]?>" type="number" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="app_list_item_text_input" />
		</span>
	</a>
	-->
	
	<br /><br />
	
	<a class="app_list_item_ghost">
		<div id="save_hours" class="iphone_button"><?=tl('Opslaan')?></div>
	</a>
	
	<div style="clear: both;"></div>
	
	<br /><br />
	
	<div class="prev_next">
		<a href="/hours/overview/<?=_pd($monday, -7)?>"><span class="fas fa-caret-left"></span> <?=tl('Week')?> <?=(int)date('W', strtotime(_pd($monday, -7)))?></a>
		<a href="/hours/overview/<?=_pd($monday, 7)?>"><?=tl('Week')?> <?=(int)date('W', strtotime(_pd($monday, 7)))?> <span class="fas fa-caret-right"></span></a>
	</div>
	
	<div style="clear: both;"></div>
	
	<?php
		if($current_bonus_hours['balance'] > 0)
		{
	?>
	<div class="app_center"><?=tl('Opgebouwde bonus')?>:</div>
	<a class="bonus_button" href="/hours/bonus"><?=($current_bonus_hours['balance_eur'] > 0 ? '&euro; <span class="current_bonus_hours_eur">' . number_format($current_bonus_hours['balance_eur'], 2, ',', '.') . '</span>' : $current_bonus_hours['balance'] . ' uur')?></a>
	<?php
		}
	?>
	
	<br /><br />
	
</div>

<script>
$(document).ready(function()
{
	$('#save_hours').click(function()
	{
		save_hours();	
		return false;
	});
	$('input[name^="hours"]').keyup(function(e)
	{
		if(e.keyCode == 13)
			save_hours();
	});
	$('input[name^="bonus_hours"]').keyup(function(e)
	{
		if(e.keyCode == 13)
			save_hours();
	});
});

function save_hours()
{
	var fd = new FormData();
	$('input[name^="hours"]').each(function()
	{
		//console.log(parseFloat($(this).val()), $(this).val().replace(',', '.'), Number($(this).val().replace(',', '.')));
		//var vl = parseFloat($(this).val());
		var vl = Number($(this).val().replace(',', '.'));
		if(!(vl > 0))
			vl = 0;
		var dte = $(this).attr('name').substr(6, 10);
		
		fd.append('normal[' + dte + ']', vl);
	});
	
	$('input[name^="bonus_hours"]').each(function()
	{
		//var vl = parseFloat($(this).val());
		var vl = Number($(this).val().replace(',', '.'));
		if(!(vl > 0))
			vl = 0;
		var dte = $(this).attr('name').substr(12, 10);
		
		fd.append('bonus[' + dte + ']', vl);
	});
	
	
	$.ajax(
	{
		url: '/ajax/hours/save_hours',
		type: 'post',
		dataType: 'json',
		data: fd,
		contentType: false,
		processData: false,
		success: function(response)
		{
			console.log(response);
			if(response['succes'])
			{
				//if(response['parsed_hours'].length > 0)
					$('input[name^="hours"]').val('');
				for(i in response['parsed_hours'])
				{
					$('input[name="hours[' + i + ']"]').val(response['parsed_hours'][i]);
				}
				
				for(i in response['parsed_bonus_hours'])
				{
					$('input[name="bonus_hours[' + i + ']"]').val(response['parsed_bonus_hours'][i]);
				}
				
				if(response['can_edit'])
				{
					black_popup('<?=tl('Opgeslagen')?>');
					setTimeout(function()
					{
						close_black_popup();
					}, 1000);
				}else
				{
					white_popup('<?=tl('<b>Fout bij opslaan</b><br />Je mag deze data niet wijzigen')?>', '<?=tl('OK')?>', function()
					{
						close_white_popup();
					});
				}
				
				if(response['current_bonus_eur'].length > 0)
				{
					$('.current_bonus_hours_eur').html(response['current_bonus_eur']);
				}
				
			}
		},
	  error: function(response)
	  {
	  	console.error(response);
	  }
	});
}
</script>