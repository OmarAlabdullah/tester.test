
var client_window_open = 0;

$(document).ready(function()
{
	
	$('tr.client').find('td').click(function()
	{
		if(!$(this).hasClass('check'))
		{
			var client_id = parseInt($(this).parent().attr('id').substr(7));
			
			if(client_id > 0)
				open_client_window($(this).parent(), client_id);
		}
	});
	
});

function close_client_window()
{
	$('tr.client_window').remove();
	$('.client_window_open').removeClass('client_window_open');
}
function open_client_window(jObj, client_id)
{
	close_client_window();
	
	if(client_id != client_window_open)
	{
		//hier alles doen
		var row = $('<tr class="client_window"><td class="client_window" colspan="999">Laden...</td></tr>');
		row.insertAfter(jObj);
		
		get_client_window_content(client_id);
		
		client_window_open = client_id;
		
		jObj.addClass('client_window_open');
	}else
		client_window_open = 0;
}
var client_timeout = 0;
var client_internal_timeout = 0;
function client_events()
{
	$('.client_remarks').keydown(function()
	{
		clearTimeout(client_timeout);
		client_timeout = setTimeout(function()
		{
			save_client_data();
		}, 500);
	});
	$('.client_internal_remarks').keydown(function()
	{
		clearTimeout(client_internal_timeout);
		client_internal_timeout = setTimeout(function()
		{
			save_client_data();
		}, 500);
	});
}
function save_client_data()
{
	var client_id = parseInt($('.client_id[name="client_id"]').first().val());
	if(client_id > 0)
	{
		var client_remarks = $('.client_remarks').first().val();
		var client_internal_remarks = $('.client_internal_remarks').first().val();
		console.log('client_internal_remarks', client_internal_remarks);
		$.ajax(
		{
			url: '/ajax/clients/save_data',
			type: 'post',
			data: {client_id:client_id, client_remarks:client_remarks, client_internal_remarks:client_internal_remarks},
			dataType: 'json',
			success: function(response)
			{
				if(response['succes'])
				{
					//$('.client_remarks').first().val(response['remarks']);
				}
			},
			error: function(response)
			{
				console.error(response);
			}
		});
	}
}

function get_client_window_content(client_id)
{
	$.ajax(
	{
		url: '/ajax/clients/get_client',
		type: 'post',
		data: {client_id:client_id},
		dataType: 'json',
		success: function(response)
		{
			console.log(response);
			if(response['succes'])
			{
				$('td.client_window').empty();
				$('td.client_window').html(response['content']);
				
				client_events();
			}else
				$('td.client_window').html('<b>Fout bij het laden van gegevens</b>');
		},
		error: function(response)
		{
			console.error(response);
			$('td.client_window').html('<b>Fout bij het laden van gegevens</b>');
		}
	});
}