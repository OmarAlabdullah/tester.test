<h1>Agenda</h1>

<div id="timeframes_select_box">
	<?=_get_select_box()?>
</div>

<div id="current_action"></div>

<h4>Week <span class="print_week_number"></span></h4>
<h5 class="print_date">21-09-2020 t/m 27-09-2020</h5>
<!--
<div class="page_actions">
	<a id="add_notification" class="btn btn-accept" href="<?=SELF?>"><span class="fas fa-plus-square"></span>Notitie toevoegen</a>
</div>
-->

<div class="page_actions">
	<a id="export_week_button" class="btn" href="/calendar/export_week?year=<?=($year > 0 ? $year : date('Y'))?>&week_number=<?=($week_number > 0 ? $week_number : date('W'))?>"><span class="fas fa-file-export"></span>Week exporteren</a>
</div>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="1">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Maandag</b> <span class="header_date">...</span><div class="header_right"></div></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th>Interne Opmerkingen</th>
		<th width="150">&nbsp;</th>
		<th width="60">&nbsp;</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="2">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Dinsdag</b> <span class="header_date">...</span><div class="header_right"></div></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th>Interne Opmerkingen</th>
		<th width="150">&nbsp;</th>
		<th width="60">&nbsp;</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="3">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Woensdag</b> <span class="header_date">...</span><div class="header_right"></div></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th>Interne Opmerkingen</th>
		<th width="150">&nbsp;</th>
		<th width="60">&nbsp;</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="4">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Donderdag</b> <span class="header_date">...</span><div class="header_right"></div></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th>Interne Opmerkingen</th>
		<th width="150">&nbsp;</th>
		<th width="60">&nbsp;</th>
	</tr>
</table>

<br />

<table class="day_table <?=($client_id > 0 ? 'can_choose' : '')?>" rel="5">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Vrijdag</b> <span class="header_date">...</span><div class="header_right"></div></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th>Interne Opmerkingen</th>
		<th width="150">&nbsp;</th>
		<th width="60">&nbsp;</th>
	</tr>
</table>
<!--
<br />

<table class="day_table weekend <?=($client_id > 0 ? 'can_choose' : '')?>" rel="6">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Zaterdag</b> <span class="header_date">...</span><div class="header_right"></div></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th>Interne Opmerkingen</th>
		<th width="150">&nbsp;</th>
		<th width="60">&nbsp;</th>
	</tr>
</table>

<br />

<table class="day_table weekend <?=($client_id > 0 ? 'can_choose' : '')?>" rel="7">
	<tr>
		<td style="background-color: #eeeeee; " colspan="9"><b>Zondag</b> <span class="header_date">...</span><div class="header_right"></div></td>
	</tr>
	<tr>
		<th width="150">Tijd</th>
		<th width="300">Straat</th>
		<th width="100">Huisnummer</th>
		<th width="100">Postcode</th>
		<th width="150">Telefoonnummer</th>
		<th>Bijzonderheden</th>
		<th>Interne Opmerkingen</th>
		<th width="150">&nbsp;</th>
		<th width="60">&nbsp;</th>
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
		table_events();
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
		
		if(choosing_client > 0)
		{
			$('#current_action').html('Adres inplannen<br /><br /><a href="<?=SELF?>" id="cancel_planning" class="btn">Annuleren</a>');
			$('#cancel_planning').click(function()
			{
				_remove_choosing();
				_set_address_bar();
				get_week();
				return false;
			});
			$('#current_action').fadeIn();
		}
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
					
					$('#export_week_button').attr('href', '/calendar/export_week?year=' + year + '&week_number=' + week_number);
					
					for(i in response['dates'])
					{
						$('.day_table[rel="' + i + '"]').find('.header_date').html(response['dates'][i]);
						var date_setting_slots = get_date_setting_slots_select(response['highest_slot'][i]);
						date_setting_slots.change(function()
						{
							var date_setting_date = $(this).parent().parent().find('.header_date').text().split('-').reverse().join('-');
							var date_setting_slot = parseInt($(this).val());
							if(date_setting_date.length == 10)
							{
								if(date_setting_slot > 0)
								{
									$.getJSON('/ajax/calendar/set_date_setting_slot/' + date_setting_date + '/' + date_setting_slot, function(response)
									{
										if(response['succes'])
											get_week();
									});
								}else
								{
									if(date_setting_slot == -1)
									{
										$.getJSON('/ajax/calendar/remove_date_setting/' + date_setting_date, function(response)
										{
											if(response['succes'])
												get_week();
										});
									}
								}
							}
						});
						$('.day_table[rel="' + i + '"]').find('.header_right').html(date_setting_slots);
						
						if(response['dates'][i] == '<?=date('d-m-Y')?>')
							$('.day_table[rel="' + i + '"]').css('border-top', 'rgba(252, 109, 65, 1) solid 2px');
						else
							$('.day_table[rel="' + i + '"]').css('border-top', '');
						
						_reset_table(i);
						
						var slots = 10;
						var date_setting = response['date_settings'][response['dates'][i].split('-').reverse().join('-')];
						if(date_setting !== undefined)
						{
							slots = parseInt(date_setting['Date_setting']['slots']);
							date_setting_slots.val(slots);
						}
						
						if(!(slots > 0))
							slots = 10;
						
						_create_slots(i, Math.max(slots, response['highest_slot'][i]+0));
						
						for(d in response['appointments'][i])
						{
							_set_appointment(i, response['appointments'][i][d]['Client']['appointment_slot'], response['appointments'][i][d]);
						}
						
						for(n in response['notifications'][response['dates'][i]])
						{
							_set_notification(i, response['notifications'][response['dates'][i]][n]);
						}
					}
					
					$('.ghost.remarks').keyup(function(e)
					{
						if(e.keyCode == 13)
							$(this).blur();
						
						if(e.keyCode == 38)
						{
							$(this).parent().parent().prev().find('.ghost.remarks').focus();
						}
						if(e.keyCode == 40)
						{
							$(this).parent().parent().next().find('.ghost.remarks').focus();
						}
						
						var new_val = $(this).val();
						var client_id = parseInt($(this).parent().parent().attr('client_id'));
						clearTimeout(tmouts[client_id]);
						tmouts[client_id] = setTimeout(function()
						{
							_update_client_remarks(client_id, new_val);
						}, 500);
					});
					$('.ghost.internal_remarks').keyup(function(e)
					{
						if(e.keyCode == 13)
							$(this).blur();
						
						if(e.keyCode == 38)
						{
							$(this).parent().parent().prev().find('.ghost.internal_remarks').focus();
						}
						if(e.keyCode == 40)
						{
							$(this).parent().parent().next().find('.ghost.internal_remarks').focus();
						}
						
						var new_val = $(this).val();
						var client_id = parseInt($(this).parent().parent().attr('client_id'));
						clearTimeout(tmouts2[client_id]);
						tmouts2[client_id] = setTimeout(function()
						{
							_update_client_internal_remarks(client_id, new_val);
						}, 500);
					});
					
					
					_set_address_bar();
					table_events();
					set_add_slots();
					_stop_loading();
				}else
					_stop_loading();
				
				hide_icons_remove_slot();
			});
		}
	}
	function _create_slots(_day, number_of_slots)
	{
		for(s = 1; s <= number_of_slots; s++)
		{
			var timeframes_select_box = $('#timeframes_select_box').html();
			var slot = '<tr class="slot empty" rel="' + s + '"><td>' + timeframes_select_box + '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td class="slot_progress"><span class="far fa-envelope" title="Nog geen mail verstuurd"></span><span class="fas fa-image" title="Nog niet alle fotos gemaakt"></span><span class="fas fa-chart-bar" title="Nog geen extra gegevens ingevuld"></span> &nbsp;<span class="fab fa-neos" title="Nog geen nestor formulier"></span><span class="fab fa-dyalog" title="Nog geen DGT rapport"></span></td><td><span class="far fa-envelope mail_appointment"></span><span class="fas fa-pencil-alt change_slot"></span><span class="fas fa-times remove_slot"></span><span class="far fa-sticky-note add_notification" title="Notitie toevoegen"></span></td></tr>';
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
		jObj.find('td:eq(0)').find('.calendar_timeframe_select').val(_client['Client']['timeframe_id']);
		jObj.find('td:eq(1)').html('<a href="/clients/details/' + _client['Client']['id'] + '">' + _client['Client']['street'] + '</a>');
		jObj.find('td:eq(2)').html(_client['Client']['homenumber'] + ' ' + _client['Client']['addition'].toUpperCase());
		jObj.find('td:eq(3)').html(_client['Client']['zipcode']);
		jObj.find('td:eq(4)').html(_client['Client']['phone']);
		jObj.find('td:eq(5)').html('<input type="text" class="ghost remarks" value="' + _client['Client']['remarks'] + '" />');
		jObj.find('td:eq(6)').html('<input type="text" class="ghost internal_remarks" value="' + _client['Client']['internal_remarks'] + '" />');
		
		if(choosing_client > 0)
			if(choosing_client == _client['Client']['id'])
				jObj.addClass('choosing_self');
		
		if(_client['Client']['appointment_mail'] != '' && _client['Client']['appointment_mail'] != '0000-00-00 00:00:00')
		{
			jObj.addClass('definitive');
			jObj.find('.slot_progress').find('.fa-envelope').addClass('done').attr('title', 'Mail verstuurd');
		}else
		{
			//mail_appointment
		}
		if(_client['Client']['extra_information'] == 1)
		{
			jObj.find('.slot_progress').find('.fa-chart-bar').addClass('done').attr('title', 'Extra gegevens ingevuld');
		}
		if(_client['Client']['finished'] == 1)
		{
			jObj.find('.slot_progress').find('.fa-image').addClass('done').attr('title', 'Alle fotos gemaakt');
		}
		if(_client['Client']['extra_information'] == 1 && _client['Client']['finished'] == 1)
		{
			jObj.addClass('finished');
		}
		
		if(_client['has_nestor'])
		{
			jObj.find('.slot_progress').find('.fa-neos').addClass('done').attr('title', 'Nestor formulier');
		}
		if(_client['has_dgt'])
		{
			jObj.find('.slot_progress').find('.fa-dyalog').addClass('done').attr('title', 'DGT rapport');
		}
		if(_client['has_nestor'] && _client['has_dgt'])
		{
			jObj.addClass('complete');
		}
	}
	function _set_notification(_day_index, _notification)
	{
		var slot = Math.floor(parseFloat(_notification['Notification']['slot']));
		console.log('_set_notification', _day_index, slot);
		
		var jObj = $('.day_table[rel="' + _day_index + '"]').find('.slot:eq(' + (slot-1) + ')');
		
		/*var nRow = $('<tr class="notification_row notification_' + _notification['Notification']['status'] + '"><td colspan="2"><b>' + _notification['Notification']['title'] + '</b></td><td colspan="5">' + _notification['Notification']['content'] + '</td><td><span class="fas fa-pencil-alt change_notification"></span></td></tr>');
		nRow.insertAfter(jObj);*/
		jObj.removeClass('empty');
		jObj.addClass('notification_row');
		jObj.addClass('notification_' + _notification['Notification']['status']);
		jObj.html('<td colspan="2"><b>' + _notification['Notification']['title'] + '</b></td><td colspan="6">' + _notification['Notification']['content'] + '</td><td><a href="/notifications/edit_notification/' + _notification['Notification']['id'] + '"><span class="fas fa-pencil-alt change_notification"></span></a></td>');
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
		
		$('.remove_slot').unbind('click');
		$('.remove_slot').click(function()
		{
			var tr = $(this).parent().parent();
			var slot = parseInt(tr.attr('rel'));
			var date = tr.parent().find('.header_date').text().split("-").reverse().join("-");
			
			_start_loading();
			$.getJSON('/ajax/calendar/remove_slot/' + date + '/' + slot, function(response)
			{
				_stop_loading();
				get_week();
			});
		});
		
		$('.change_slot').unbind('click');
		$('.change_slot').click(function()
		{
			var tr = $(this).parent().parent();
			tr.addClass('choosing_self');
			$('.day_table').addClass('can_choose');
			choosing_client = parseInt(tr.attr('client_id'));
			
			table_events();
		});
		
		$('.mail_appointment').unbind('click');
		$('.mail_appointment').click(function()
		{
			var client_id = parseInt($(this).parent().parent().attr('client_id'));
			
			console.log('calendar_timeframe_select', client_id);
			
			if(client_id > 0)
			{
				$.getJSON('/ajax/clients/get_client_info/' + client_id, function(response)
				{
					console.log(response);
					if(response['succes'])
					{
						var popup_content = '<h3>Verstuur afspraakbevestiging</h3>';
						popup_content += '<select class="mail_template_id">';
						for(i in response['mail_templates'])
						{
							popup_content += '<option value="' + response['mail_templates'][i]['Mail_template']['id'] + '">' + response['mail_templates'][i]['Mail_template']['name'] + '</option>';
						}
						popup_content += '</select>';
						popup_content += '<br /><br />';
						popup_content += '<a href="<?=SELF?>" class="btn popup_no"><span class="fas fa-chevron-circle-left"></span>Terug</a>';
						popup_content += ' &nbsp; ';
						popup_content += '<a href="<?=SELF?>" class="btn btn-accept popup_send"><span class="fas fa-chevron-circle-right"></span>Verstuur bevestiging</a>';
						
						popup(popup_content);
						
						$('.popup_no').click(function()
						{
							close_popup();
							return false;
						});
						$('.popup_send').click(function()
						{
							var mail_template_id = parseInt($('.mail_template_id:visible').first().val());
							if(client_id > 0 && mail_template_id > 0)
							{
								$.getJSON('/ajax/clients/sent_appointment_mail/' + client_id + '/' + mail_template_id, function(response)
								{
									console.log(response);
									if(response['succes'])
									{
										$('.slot[client_id="' + client_id + '"]').addClass('definitive').find('.slot_progress').find('.fa-envelope').addClass('done').attr('title', 'Mail verstuurd');;
										close_popup();
									}
								});
							}
							
							return false;
						});
					}
				});
			}
			
			return false;
		});
		
		$('.calendar_timeframe_select').unbind('change');
		$('.calendar_timeframe_select').change(function()
		{
			var client_id = parseInt($(this).parent().parent().attr('client_id'));
			var timeframe_id = parseInt($(this).val());
			//console.log('calendar_timeframe_select', client_id, timeframe_id);
			if(client_id > 0 && timeframe_id > 0)
			{
				$.getJSON('/ajax/calendar/set_timeframe_id/' + client_id + '/' + timeframe_id, function(response)
				{
					
				});
			}
		});
		
		$('.add_notification').unbind('click');
		$('.add_notification').click(function()
		{
			var date = $(this).parent().parent().parent().find('.header_date').text().split('-').reverse().join('-');
			var slot = parseInt($(this).parent().parent().attr('rel'));
			notification_popup(date, slot);
			return false;
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
		var client_id = choosing_client;
		
		if(client_id > 0 && slot > 0 && date.length == 10)
		{
			_start_loading();
			$.getJSON('/ajax/calendar/select_slot/' + client_id + '/' + date + '/' + slot, function(response)
			{
				console.log(response);
				if(response['succes'])
				{
					_stop_loading();
					choosing_client = 0;
					_remove_choosing();
					get_week();
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
		var addr = '<?=SELF?>?year=' + year + '&week_number=' + week_number;
		if(choosing_client > 0)
			addr += '&client_id=' + choosing_client;
		window.history.pushState(null, null, addr);
	}
	function set_add_slots()
	{
		$('.add_slot').remove();
		$('.add_slot_line').remove();
		
		var i = 1;
		$('.day_table').each(function() //$('.day_table.can_choose') || $('.day_table')
		{
			var date = $(this).find('.header_date').text().split("-").reverse().join("-");
			var prev_empty = false;
			$(this).find('.slot').each(function()
			{
				if(!prev_empty && !$(this).hasClass('empty') && !$(this).hasClass('choosing_self'))
				{
					//$(this).addClass('insert_line_above');
					
					var table_row = $(this);
					
					var jObj = $('<div>');
					jObj.addClass('add_slot');
					jObj.attr('rel', i);
					
					var slot_height = parseInt($(this).height());
					var slot_offset = $(this).offset();
					
					jObj.css('left', parseInt(slot_offset['left']) - 27);
					jObj.css('top', parseInt(slot_offset['top']) - 12);
					
					jObj.hover(function()
					{
						$('.add_slot_line[rel="' + $(this).attr('rel') + '"]').show();
					}, function()
					{
						$('.add_slot_line').hide();
					}).click(function()
					{
						add_slot(table_row, date);
					});
					
					jObj.appendTo($('body'));
					
					
					jObj = $('<div>');
					jObj.addClass('add_slot_line');
					jObj.attr('rel', i);
					
					var slot_height = parseInt($(this).height());
					var slot_offset = $(this).offset();
					
					jObj.css('width', $(this).width());
					jObj.css('left', parseInt(slot_offset['left']));
					jObj.css('top', parseInt(slot_offset['top']));
					
					jObj.appendTo($('body'));
					
					i++;
				}
				prev_empty = $(this).hasClass('empty');
			});
		});
	}
	function add_slot(jObj, date)
	{
		var slot = parseInt(jObj.attr('rel'));
		var client_id = choosing_client;
		
		if(slot > 0 && date.length == 10)
		{
			_start_loading();
			$.getJSON('/ajax/calendar/select_existing_slot/' + date + '/' + slot + '/' + client_id, function(response)
			{
				console.log(response);
				if(response['succes'])
				{
					_stop_loading();
					choosing_client = 0;
					_remove_choosing();
					get_week();
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
	function _remove_choosing()
	{
		choosing_client = 0;
		$('.day_table').removeClass('can_choose');
		$('.slot').removeClass('choosing_self');
		$('#current_action').fadeOut();
	}
	
	function notification_popup(date, slot)
	{
		var notification_html = '';
		notification_html += '<h3>Notitie</h3><br />';
		
		notification_html += 'Titel:<br /><input class="notification_title" type="text" style="width: 100%; " />';
		notification_html += '<br /><br />Inhoud:<br /><textarea class="notification_content" style="width: 100%; height: 200px; "></textarea>';
		
		notification_html += '<br /><br /><a class="btn popup_no" href="/"><span class="fas fa-chevron-circle-left"></span>terug</a> &nbsp; <a class="btn btn-accept popup_save" href="/"><span class="fas fa-check"></span>Opslaan</a>';
		popup(notification_html);
		
		$('.popup_body').css('width', 500);
		
		$('.popup_no').click(function()
		{
			close_popup();
			return false;
		});
		$('.popup_save').click(function()
		{
			var fd = new FormData();
			fd.append('date', date);
			fd.append('slot', slot);
			fd.append('title', $('.notification_title:visible').first().val());
			fd.append('content', $('.notification_content:visible').first().val());
			
			$.ajax(
			{
				url: '/ajax/calendar/add_notification',
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
						close_popup();
						get_week();
					}else
					{
						close_popup();
						popup('<h3>Fout [901]</h3>Er is iets fout gegeaan met opslaan<br /><br/><a class="btn popup_no" href="/"><span class="fas fa-redo"></span>OK</a>');
						$('.popup_no').click(function()
						{
							close_popup();
							return false;
						});
					}
				}
			});
			
			return false;
		});
	}
	
	
	var updating_que = [];
	var updateing_que_i = 0;
	
	
	function _update_client_remarks(client_id, new_val)
	{
		updating_que[updateing_que_i] = {
			url : '/ajax/clients/update_remarks',
			client_id : client_id,
			new_val: new_val
		};
		updateing_que_i++;
		
		console.log(updating_que);
		if(updateing_que_i == 1)
			_run_updateing_que();
	}
	function _update_client_internal_remarks(client_id, new_val)
	{
		updating_que[updateing_que_i] = {
			url : '/ajax/clients/update_internal_remarks',
			client_id : client_id,
			new_val: new_val
		};
		updateing_que_i++;
		
		console.log(updating_que);
		if(updateing_que_i == 1)
			_run_updateing_que();
	}
	function _run_updateing_que()
	{
		var fd = new FormData();
		fd.append('client_id', updating_que[updateing_que_i-1]['client_id']);
		fd.append('new_val', updating_que[updateing_que_i-1]['new_val']);
		
		updating_busy = true;
		
		$.ajax(
		{
			url: updating_que[updateing_que_i-1]['url'],
			type: 'post',
			dataType: 'json',
			data: fd,
			contentType: false,
			processData: false,
			success: function(response)
			{
				console.log(response);
				
				updating_que.shift();
				updateing_que_i--;
				if(updateing_que_i > 0)
					_run_updateing_que();
			}
		});
	}
	
	/*
	var updating_busy = false;
	function _update_client_remarks(client_id, new_val)
	{
		client_id = parseInt(client_id);
		
		if(client_id > 0 && !updating_busy)
		{
			var fd = new FormData();
			fd.append('client_id', client_id);
			fd.append('remarks', new_val);
			
			updating_busy = true;
			
			$.ajax(
			{
				url: '/ajax/clients/update_remarks',
				type: 'post',
				dataType: 'json',
				data: fd,
				contentType: false,
				processData: false,
				success: function(response)
				{
					updating_busy = false;
					console.log(response);
				},
				error: function(response)
				{
					updating_busy = false;
				}
			});
		}
	}*/
	/*function _update_client_internal_remarks(client_id, new_val)
	{
		client_id = parseInt(client_id);
		
		if(client_id > 0 && !updating_busy)
		{
			var fd = new FormData();
			fd.append('client_id', client_id);
			fd.append('internal_remarks', new_val);
			
			updating_busy = true;
			
			$.ajax(
			{
				url: '/ajax/clients/update_internal_remarks',
				type: 'post',
				dataType: 'json',
				data: fd,
				contentType: false,
				processData: false,
				success: function(response)
				{
					updating_busy = false;
					console.log(response);
				},
				error: function(response)
				{
					updating_busy = false;
				}
			});
		}
	}*/
	
	function get_date_setting_slots_select(current_occupied_slots = 0)
	{
		var date_setting_slots = $('<select class="date_setting_slots"></select>');
		date_setting_slots.append('<option value="-1">...</option>');
		for(s = 1; s <= 15; s++)
		{
			date_setting_slots.append('<option value="' + s + '" ' + (s < current_occupied_slots ? 'disabled' : '') + '>' + s + '</option>');
		}
		
		return date_setting_slots;
	}
	function hide_icons_remove_slot()
	{
		$('.remove_slot:visible').each(function()
		{
			var row = $(this).parent().parent();
			var slot = (row.index()) - 1;
			
			has_filled_row_after = false;
			row.parent().find('tr:gt(' + row.index() + ')').each(function()
			{
				if(!$(this).hasClass('empty'))
					has_filled_row_after = true;
			});
			if(!has_filled_row_after)
				$(this).hide();
		});
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
	.slot
	{
		border-bottom: #cccccc solid 1px;
	}
	.slot:hover
	{
		background-color: rgba(200, 200, 200, 0.4) !important;
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
	.slot .add_notification
	{
		margin-left: 10px;
		display: none;
	}
	.slot.empty .add_notification
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
	.change_slot:hover, .remove_slot:hover, .mail_appointment:hover, .add_notification:hover
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
	.header_right
	{
		width: 200px;
		float: right;
	}
	select.date_setting_slots option:disabled
	{
		background-color: #eeeeee;
	}
</style>