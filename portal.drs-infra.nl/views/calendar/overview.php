<h1>Agenda</h1>

<?php
	
	$params = array(
		'timeframes' => array(
			'conditions' => array(
				'archived' => '0000-00-00 00:00:00'
			),
			'order' => 'sort, created'
		)
	);
	$timeframes = $db->select($params);
	
	foreach($timeframes as $timeframe)
	{
?>
<input type="hidden" name="timeframe[<?=$timeframe['Timeframe']['id']?>]" value="<?=$timeframe['Timeframe']['timeframe']?>" />
<?php
	}
?>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="1">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Maandag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th width="50">&nbsp;</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="2">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Dinsdag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th width="50">&nbsp;</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="3">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Woensdag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th width="50">&nbsp;</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="4">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Donderdag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th width="50">&nbsp;</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="5">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Vrijdag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th width="50">&nbsp;</th>
	</tr>
</table>
<!--
<br />

<table class="day_table weekend <?=($client_id > 0 ? 'can_choose' : '')?>" rel="6">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Zaterdag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th width="50">&nbsp;</th>
	</tr>
</table>

<br />

<table class="day_table weekend <?=($client_id > 0 ? 'can_choose' : '')?>" rel="7">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Zondag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th width="50">&nbsp;</th>
	</tr>
</table>
-->
<div id="calendar_margin_bottom"></div>

<div id="calendar_nav">
	<div class="pagination">
		<a id="prev_week" href="<?=SELF?>" rel="prev"></a>
		<a id="this_week" href="<?=SELF?>" class="selected" style="">Week <span class="print_week_number"></span></a>
		<a id="next_week" href="<?=SELF?>" rel="next"></a>
	</div>
</div>

<script>
	var loading = false;
	var year = <?=($year > 0 ? $year : date('Y'))?>;;
	var week_number = <?=($week_number > 0 ? $week_number : date('W'))?>;
	var choosing_client = <?=($client_id > 0 ? $client_id : '0')?>;
	var tmout = 0;
	var tmouts = {};
	var tmouts2 = {};
	
	$(document).ready(function()
	{
		get_week();
		
		$(document).keydown(function(e)
		{
			if(e.keyCode == 37)
				if(!$('input').is(":focus"))
					_prev_week();
			if(e.keyCode == 39)
				if(!$('input').is(":focus"))
					_next_week();
			
		});
		
		$('#prev_week').click(function()
		{
			_prev_week();
			return false;
		});
		
		$('#this_week').click(function()
		{
			get_week();
			return false;
		});
		
		$('#next_week').click(function()
		{
			_next_week();
			return false;
		});
	});
	function _prev_week()
	{
		week_number--;
		if(week_number < 1)
		{
			week_number = 52;
			year--;
		}
		get_week();
	}
	function _next_week()
	{
		week_number++;
		if(week_number > 52)
		{
			week_number = 1;
			year++;
		}
		get_week();
	}
	
	function get_week()
	{
		if(!loading)
		{
			_start_loading();
			$.getJSON('/ajax/calendar/get_week/' + year + '/' + week_number, function(response)
			{
				if(response['succes'])
				{
					year = parseInt(response['year']);
					week_number = parseInt(response['week_number']);
					$('.print_week_number').html(response['week_number']);
					$('.print_date').html(response['monday'] + ' t/m ' + response['sunday']);
					
					$('#export_week_button').attr('href', '/calendar/export_week?year=' + year + '&week_number=' + week_number);
					
					for(i in response['dates'])
					{
						$('.day_table[rel="' + i + '"]').find('.header_date').html(response['dates'][i]);
						
						if(response['dates'][i] == '<?=date('d-m-Y')?>')
							$('.day_table[rel="' + i + '"]').css('border-top', 'rgba(252, 109, 65, 1) solid 2px');
						else
							$('.day_table[rel="' + i + '"]').css('border-top', '');
						
						_reset_table(i);
						
						_create_slots(i, Math.max(0, response['highest_slot'][i]+0));
						
						for(d in response['appointments'][i])
						{
							_set_appointment(i, response['appointments'][i][d]['Client']['appointment_slot'], response['appointments'][i][d]);
						}
					}
					
					
					_set_address_bar();
					_stop_loading();
				}else
					_stop_loading();
			});
		}
	}
	function _create_slots(_day, number_of_slots)
	{
		for(s = 1; s <= number_of_slots; s++)
		{
			var slot = '<tr class="slot empty" rel="' + s + '"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
			$(slot).appendTo($('.day_table[rel="' + _day + '"]'));
		}
	}
	function _reset_table(_day)
	{
		var jObj = $('.day_table[rel="' + _day + '"]').find('.slot').remove();
		$('.day_table[rel="' + _day + '"]').find('.notification_row').remove();
	}
	function _empty_row(jObj)
	{
		jObj.addClass('empty');
		jObj.each(function()
		{
			var i = 0;
			$(this).find('td').each(function()
			{
				if(i > 0)
					$(this).html('&nbsp;');
				i++;
			});
		});
		jObj.removeClass('choose_hover');
	}
	function _set_appointment(_day_index, _slot, _client)
	{
		_empty_row($('.slot[client_id="' + _client['Client']['id'] + '"]'));
		
		var jObj = $('.day_table[rel="' + _day_index + '"]').find('.slot:eq(' + (_slot-1) + ')');
		jObj.attr('client_id', _client['Client']['id']);
		jObj.removeClass('empty');
		jObj.find('td:eq(0)').html($('input[name="timeframe[' + _client['Client']['timeframe_id'] + ']"]').val());
		jObj.find('td:eq(1)').html('' + _client['Client']['street'] + '');
		jObj.find('td:eq(2)').html(_client['Client']['homenumber'] + ' ' + _client['Client']['addition'].toUpperCase());
		jObj.find('td:eq(3)').html(_client['Client']['zipcode']);
		jObj.find('td:eq(4)').html(_client['Client']['phone']);
		jObj.find('td:eq(5)').html('' + _client['Client']['remarks'] + '');
		
	}
	function _start_loading()
	{
		loading = true;
		$('table.day_table').css('opacity', 0.5);
	}
	function _stop_loading()
	{
		loading = false;
		$('table.day_table').css('opacity', '');
	}
	function _set_address_bar()
	{
		var addr = '<?=SELF?>?year=' + year + '&week_number=' + week_number;
		if(choosing_client > 0)
			addr += '&client_id=' + choosing_client;
		window.history.pushState(null, null, addr);
	}
</script>
<style>
	.empty.choose_hover td
	{
		background-color: rgba(252, 109, 65, 0.2) !important;
		cursor: pointer;
		/*color: #ffffff;*/
	}
	.can_choose .empty td:nth-child(1)
	{
		position: relative;
	}
	.can_choose .empty td:nth-child(1)::after
	{
		position: absolute;
		right: 10px;
		top: 0px;
		font-family: "Font Awesome 5 Free";
		font-weight: 900;
		content: "\f111";
		color: #fc6d41;
		font-size: 7px;
	}
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
	.calendar_timeframe_select
	{
		border: transparent solid 1px;
		background-color: transparent;
		outline: none;
		font-family: Roboto;
		color: #373737;
	}
	.calendar_timeframe_select:hover
	{
		border: #cccccc solid 1px;
		background-color: #ffffff;
	}
	.slot.insert_line_below
	{
		border-bottom: rgba(252, 109, 65, 1) dashed 1px;
	}
	.slot.insert_line_above
	{
		border-top: rgba(252, 109, 65, 1) dashed 1px;
	}
	.add_slot
	{
		position: absolute;
		left: 300px;
		top: 300px;
		width: 25px;
		height: 25px;
		/*background-color: rgba(100, 100, 100, 0.4);*/
		cursor: pointer;
	}
	.add_slot_line
	{
		position: absolute;
		left: 300px;
		top: 300px;
		width: 10px;
		height: 2px;
		cursor: pointer;
		border-top: rgba(252, 109, 65, 1) dashed 1px;
		display: none;
	}
	.add_slot::before
	{
		position: absolute;
		right: 2px;
		top: 5px;
		font-family: "Font Awesome 5 Free";
		font-weight: 900;
		content: "\f0da";
		color: #cccccc;
		font-size: 15px;
	}
	.add_slot:hover::before
	{
		color: #fc6d41;
	}
	#timeframes_select_box
	{
		display: none;
	}
	.day_table .slot.empty .calendar_timeframe_select
	{
		display: none;
	}
	.slot .remove_slot
	{
		display: none;
	}
	.slot.empty .remove_slot
	{
		display: inline;
		cursor: pointer;
	}
	.slot:last-child .remove_slot
	{
		display: none;
	}
	.change_slot
	{
		display: inline;
		cursor: pointer;
	}
	.slot.empty .change_slot
	{
		display: none;
	}
	.change_slot:hover, .remove_slot:hover, .mail_appointment:hover
	{
		color: #fc6d41;
	}
	.slot.choosing_self td
	{
		background-color: rgba(145, 204, 240, 0.5) !important;
	}
	.slot.definitive td
	{
		/*background-color: rgba(190, 243, 141, 0.5) !important;*/
		/*background-color: rgba(255, 137, 6, 0.25) !important;*/
		background-color: rgba(5, 181, 239, 0.25) !important;
	}
	.slot.definitive.finished td
	{
		/*background-color: rgba(190, 243, 141, 0.5) !important;*/
		background-color: rgba(255, 137, 6, 0.25) !important;
	}
	.slot.definitive.finished.complete td
	{
		background-color: rgba(190, 243, 141, 0.5) !important;
	}
	.notification_row td
	{
		background-color: rgba(252, 109, 65, 0.1) !important;
	}
	.notification_row td:nth-child(1)
	{
		position: relative;
		padding-left: 35px;
	}
	.notification_row td:nth-child(1)::before
	{
		position: absolute;
		left: 10px;
		top: 0px;
		font-family: "Font Awesome 5 Free";
		font-weight: 900;
		content: "\f071";
		color: rgba(252, 109, 65, 1);
		font-size: 11px;
	}
	.notification_row .change_notification
	{
		color: rgba(252, 109, 65, 0.8);
		cursor: pointer;
	}
	.notification_row .change_notification:hover
	{
		color: rgba(252, 109, 65, 1);
	}
	.notification_row.notification_finished .change_notification
	{
		color: rgba(70, 218, 7, 1);
	}
	.notification_row.notification_finished td:nth-child(1)::before
	{
		color: rgba(70, 218, 7, 1);
	}
	.notification_row.notification_finished td
	{
		background-color: rgba(190, 243, 141, 0.5) !important;
	}
	/*
	.slot.definitive td:first-child
	{
		position: relative;
	}
	.slot.definitive td:first-child::before
	{
		position: absolute;
		right: 2px;
		top: 0px;
		font-family: "Font Awesome 5 Free";
		font-weight: 900;
		content: "\f14a";
		color: #A0EE59;
		font-size: 11px;
	}
	*/
	.can_choose .change_slot, .can_choose .remove_slot
	{
		display: none !important;
	}
	#current_action
	{
		position: fixed;
		right: 20px;
		top: 120px;
		min-width: 200px;
		min-height: 50px;
		box-shadow: 0px 0px 5px 3px rgba(0,0,0,0.5);
		background-color: #ffffff;
		display: none;
		z-index: 990;
		padding: 10px;
		text-align: center;
	}
	input.ghost
	{
		background-color: transparent;
		border: transparent solid 1px;
	}
	input.ghost:hover, input.ghost:focus
	{
		border: #cccccc solid 1px;
		background-color: rgba(255, 255, 255, 0.5);
	}
	.mail_appointment
	{
		margin-right: 10px;
		cursor: pointer;
	}
	.slot.empty .mail_appointment
	{
		display: none;
	}
	.slot_progress .fas, .slot_progress .far, .slot_progress .fab
	{
		margin: 0px 4px;
		color: #cccccc;
	}
	.slot_progress .fas.done, .slot_progress .far.done, .slot_progress .fab.done
	{
		color: rgba(5, 181, 239, 1);
	}
	.slot.finished .slot_progress .fas.done, .slot.finished .slot_progress .far.done, .slot.finished .slot_progress .fab.done
	{
		color: rgba(252, 109, 65, 1);
	}
	.slot.complete .slot_progress .fas.done, .slot.complete .slot_progress .far.done, .slot.complete .slot_progress .fab.done
	{
		color: rgba(70, 218, 7, 1);
	}
</style>