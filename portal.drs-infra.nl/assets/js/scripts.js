$(document).ready(function()
{
	$('.btn.disabled').click(function()
	{
		return false;
	});
	$('input.search').keypress(function(e)
	{
		if(e.keyCode == 13)
		{
			var vl = $(this).val();
			
			var url = new URL(window.location.href);
			var params = new URLSearchParams(url.search.slice(1));
			params.set('q', vl);
			
			if(vl.length > 0)
				window.location.href = '?' + params.toString();
		}
	});
	$('.close_search').click(function()
	{
		var url = new URL(window.location.href);
		var params = new URLSearchParams(url.search.slice(1));
		params.delete('q');
		
		window.location.href = '?' + params.toString();
		
		return false;
	});
	$('.add_filter').click(function()
	{
		var url = new URL(window.location.href);
		var params = new URLSearchParams(url.search.slice(1));
		
		if($(this).hasClass('remove_filter'))
		{
			params.delete('filter');
		}else
		{
			params.set('filter', $(this).attr('rel'));
		}
		
		window.location.href = '?' + params.toString();
		
		return false;
	});
	
	$(document).keydown(function(e)
	{
		if(e.keyCode == 27) //esc
		{
			if($('.popup_bg').length)
			{
				close_popup();
				reset_actions_selector();
			}else
			{
				if(client_window_open > 0)
				{
					close_client_window();
					client_window_open = 0;
				}else
				{
					if($('.close_search').length)
					{
						/*console.log(window.location.href.replace(window.location.search,''));
						window.location.href = window.location.href.replace(window.location.search,'');
						window.location.reload(true);*/
					}
				}
			}
		}
	});
	
});

function popup(cntnt)
{
	var bg = $('<div>');
	bg.addClass('popup_bg');
	bg.appendTo($('body'));
	
	var body = $('<div>');
	body.addClass('popup_body');
	body.html(cntnt);
	body.appendTo(bg);
}
function close_popup()
{
	$('.popup_bg,.popup_body').remove();
}