
var app_permissions_push = false;

$(document).ready(function()
{
	$('.app_list_item_checkbox').click(function()
	{
		console.log('check clicked');
		toggle_app_checkbox($(this));
	});
	$('.open_full_screen').click(function()
	{
		var src = $(this).attr('src');
		if(src.length > 0)
		{
			var title = $(this).attr('rel');
			_open_full_screen(src, title);
		}
	});
});
function _open_full_screen(src, title)
{
	var jObjo = $('<div class="photo_popup_overlay"></div>');
	jObjo.click(function(e)
	{
		var clicked_jObj = $(e.target);
		if(! (clicked_jObj.hasClass('photo_popup_controls_remove') || clicked_jObj.parent().hasClass('photo_popup_controls_remove')) )
			_close_full_screen();
	});
	
	var jObj = $('<div class="photo_popup"></div>');
	//jObj.append('<div class="photo_popup_controls"><div class="photo_popup_controls_change"><span class="far fa-caret-square-up"></span></div><div></div><div class="photo_popup_controls_remove"><span class="far fa-trash-alt"></span></div></div>');
	jObj.append('<div class="photo_popup_controls"><div></div><div></div><div class="photo_popup_controls_remove"><span class="far fa-trash-alt"></span></div></div>');
	
	var img = $('<img src="' + src + '" />');
	jObj.append(img);
	jObj.appendTo(jObjo);
	
	jObjo.appendTo($('body'));
	
	$('.photo_popup_controls_remove').click(function()
	{
		var jObj_box_holder = $('<div class="popup_box_remove_holder" style="position: fixed; z-index: 152; left: 0px; top: 0px; width: 100%; height: 100vh; "></div>');
		var jObj_box = $('<div class="popup_box_remove" style="z-index: 153; color: #cc0000; position: absolute; right: 10px; bottom: 50px; background-color: #ffffff; padding: 10px 20px; border-radius: 5px; display: none; "><span class="far fa-trash-alt"></span> &nbsp; Verwijderen</div>');
		jObj_box.appendTo(jObj_box_holder);
		
		jObj_box_holder.appendTo($('body'));
		
		jObj_box.fadeIn(200);
		
		$('.popup_box_remove_holder').click(function(e)
		{
			var clicked_jObj = $(e.target);
			if(!clicked_jObj.hasClass('popup_box_remove'))
				$(this).fadeOut(200, function()
				{
					$(this).remove();
				});
		});
		$('.popup_box_remove').click(function()
		{
			$(this).css('background-color', '#FFF0F0');
			_remove_photo(src);
		});
	});
	
	
	if(title.length > 0)
	{
		jObjo.append('<div class="photo_popup_title"><span>' + title + '</span></div>');
	}
	
	jObjo.fadeIn(200);
}
function _close_full_screen()
{
	$('.photo_popup_overlay').fadeOut(200, function()
	{
		$(this).remove();
	});
}
function back_button(cntnt, lnk)
{
	$('#app_header_left').attr('href', lnk).html(cntnt).show();
}
function black_popup(cntnt)
{
	var jObjo = $('<div class="popup_overlay"></div>');
	jObjo.appendTo($('body'));
	
	var jObj = $('<div class="black_popup">' + cntnt + '</div>');
	jObj.hide();
	jObj.appendTo($('body'));
	jObj.css('top', ($(window).height() - jObj.height()) / 2);
	jObj.show();
}
function close_black_popup(callbck)
{
	$('.popup_overlay').fadeOut('fast', function()
	{
		$(this).remove();
	});
	$('.black_popup').fadeOut('fast', function()
	{
		$(this).remove();
		if(typeof callbck === 'function')
		{
			callbck.call(this);
		}
	});
}
function white_popup(cntnt, button1, callbck1)
{
	var jObjo = $('<div class="popup_overlay"></div>');
	jObjo.appendTo($('body'));
	
	var jObj = $('<div class="white_popup has_buttons">' + cntnt + '</div>');
	
	var bsjObj = $('<div class="white_popup_buttons"></div>');
	
	var bjObj = $('<div class="white_popup_button">' + button1 + '</div>');
	bjObj.click(function()
	{
		if(typeof callbck1 == 'function')
		{
			callbck1.call(this);
		}
	});
	bjObj.appendTo(bsjObj);
	
	bsjObj.appendTo(jObj);
	
	jObj.hide();
	jObj.appendTo($('body'));
	jObj.css('top', ($(window).height() - (jObj.height() + 40)) / 2);
	jObj.css('left', ($(window).width() - (jObj.width() + 40)) / 2);
	jObj.show();
}
function close_white_popup(callbck)
{
	$('.popup_overlay').fadeOut('fast', function()
	{
		$(this).remove();
	});
	$('.white_popup').fadeOut('fast', function()
	{
		$(this).remove();
		if(typeof callbck === 'function')
		{
			callbck.call(this);
		}
	});
}
function toggle_app_checkbox(jObj)
{
	if(jObj.hasClass('checked'))
	{
		_uncheck_app_checkbox(jObj, function()
		{
			jObj.removeClass('checked');
			jObj.trigger('change');
		});
	}else
	{
		_check_app_checkbox(jObj, function()
		{
			jObj.addClass('checked');
			jObj.trigger('change');
		});
	}
}
function _uncheck_app_checkbox(jObj, callbck)
{
	jObj.animate({backgroundColor: '#ffffff'}, 150, function()
	{
		if(typeof callbck == 'function')
			callbck.call(this);
	});
	jObj.find('.app_list_item_checkbox_cursor').animate({left: 0}, 150).css('right', 'auto');
}
function _check_app_checkbox(jObj, callbck)
{
	jObj.animate({backgroundColor: '#4bd863'}, 150, function()
	{
		if(typeof callbck == 'function')
			callbck.call(this);
	});
	jObj.find('.app_list_item_checkbox_cursor').animate({right: 0}, 150).css('left', 'auto');
}

function _remove_photo(src)
{
	var photo_id = parseInt(src.split('/')[3].split('.')[0]);
	if(photo_id > 0)
	{
		$.getJSON('/ajax/photos/remove_photo/' + photo_id, function(response)
		{
			console.log(response);
			if(response['succes'])
			{
				$('.popup_box_remove_holder').fadeOut(200, function()
				{
					$(this).remove();
				});
				$('.photo_popup_overlay').fadeOut(200, function()
				{
					$(this).remove();
					$('img[src="' + src + '"]').fadeOut(200, function()
					{
						$(this).remove();
					});
				});
				
				if(!response['client_finished'])
				{
					$('#take_picture').removeClass('app_list_finished').addClass('app_list_alert_small');
				}
			}
		});
	}
}