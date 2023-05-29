<h1>Agenda</h1>

<h4>Week <span class="print_week_number"></span></h4>
<h5 class="print_date">21-09-2020 t/m 27-09-2020</h5>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="1">
	<tr>
		<td style="background-color: #eeeeee; " colspan="6"><b>Maandag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="2">
	<tr>
		<td style="background-color: #eeeeee; " colspan="6"><b>Dinsdag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="3">
	<tr>
		<td style="background-color: #eeeeee; " colspan="6"><b>Woensdag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="4">
	<tr>
		<td style="background-color: #eeeeee; " colspan="6"><b>Donderdag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="5">
	<tr>
		<td style="background-color: #eeeeee; " colspan="6"><b>Vrijdag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
	</tr>
</table>

<br />

<table class="day_table weekend <?=($client_id > 0 ? 'can_choose' : '')?>" rel="6">
	<tr>
		<td style="background-color: #eeeeee; " colspan="6"><b>Zaterdag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
	</tr>
</table>

<br />

<table class="day_table weekend <?=($client_id > 0 ? 'can_choose' : '')?>" rel="7">
	<tr>
		<td style="background-color: #eeeeee; " colspan="6"><b>Zondag</b> <span class="header_date">...</span></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
	</tr>
</table>

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
	
	$(document).ready(function()
	{
		table_events();
		get_week();
		
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
				console.log('get_week', response);
				
				if(response['succes'])
				{
					year = parseInt(response['year']);
					week_number = parseInt(response['week_number']);
					$('.print_week_number').html(response['week_number']);
					$('.print_date').html(response['monday'] + ' t/m ' + response['sunday']);
					
					for(i in response['dates'])
					{
						$('.day_table[rel="' + i + '"]').find('.header_date').html(response['dates'][i]);
						
						_reset_table(i);
						
						for(d in response['appointments'][i])
						{
							_set_appointment(i, response['appointments'][i][d]['Client']['appointment_slot'], response['appointments'][i][d]);
						}
					}
					
					_set_address_bar();
					table_events();
					_stop_loading();
				}else
					_stop_loading();
			});
		}
	}
	function _reset_table(_day)
	{
		var jObj = $('.day_table[rel="' + _day + '"]').find('.slot');
		_empty_row(jObj);
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
		console.log(_day_index, _slot, _client);
		
		_empty_row($('.slot[client_id="' + _client['Client']['id'] + '"]'));
		
		var jObj = $('.day_table[rel="' + _day_index + '"]').find('.slot:eq(' + (_slot-1) + ')');
		jObj.attr('client_id', _client['Client']['id']);
		jObj.removeClass('empty');
		jObj.find('td:eq(1)').html(_client['Client']['street']);
		jObj.find('td:eq(2)').html(_client['Client']['homenumber'] + ' ' + _client['Client']['addition'].toUpperCase());
		jObj.find('td:eq(3)').html(_client['Client']['zipcode']);
		jObj.find('td:eq(4)').html(_client['Client']['phone']);
		jObj.find('td:eq(5)').html(_client['Client']['remarks']);
	}
	function table_events()
	{
		$('table.can_choose').find('.slot').unbind('mouseenter mouseleave click');
		$('table.can_choose').find('.slot.empty').hover(function()
		{
			$(this).addClass('choose_hover');
		}, function()
		{
			$(this).removeClass('choose_hover');
		}).click(function()
		{
			var jObj_table = $(this).closest('table.day_table');
			var date = jObj_table.find('.header_date').text().split("-").reverse().join("-");
			var slot = jObj_table.find('.slot').index(this)+1;
			
			_choose_client_slot(date, slot);
		});
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
	function _choose_client_slot(date, slot)
	{
		var client_id = parseInt(<?=($client_id+0)?>);
		if(client_id > 0 && slot > 0 && date.length == 10)
		{
			_start_loading();
			$.getJSON('/ajax/calendar/select_slot/' + client_id + '/' + date + '/' + slot, function(response)
			{
				console.log(response);
				if(response['succes'])
				{
					_set_appointment(response['day_index'], response['client']['Client']['appointment_slot'], response['client']);
					table_events();
					_stop_loading();
				}else
				{
					popup('<h3>Fout bij opslaan</h3>Probeer het opnieuw<br /><br /><a class="btn btn-accept popup_okay" href="/"><span class="fas fa-redo"></span>OK</a>');
					
					$('.popup_okay').click(function()
					{
						close_popup();
						location.reload(true);
						return false;
					});
				}
			});
		}
	}
	function _set_address_bar()
	{
		window.history.pushState(null, null, '<?=SELF?>?year=' + year + '&week_number=' + week_number + '<?=($client_id > 0 ? '&client_id=' . $client_id : '')?>');
	}
</script>
<style>
	.empty.choose_hover td
	{
		background-color: rgba(252, 109, 65, 0.8) !important;
		cursor: pointer;
		color: #ffffff;
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
</style>