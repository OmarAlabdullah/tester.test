
var shifted = false;
var checkboxes_checked = false;
var number_of_checkboxes_checked = 0;
var last_checkbox_checked = false;

$(document).ready(function()
{
	$(document).on('keyup keydown', function(e){shifted = e.shiftKey} );
	
	var rl = 0;
	$('td.check').find('input[type="checkbox"]').each(function()
	{
		if($(this).parent().hasClass('check'))
		{
			$(this).attr('rel', rl);
			rl++;
		}
	});
	
	$('th.check').change(function()
	{
		main_checkbox_changed($(this).find('input[type="checkbox"]').prop('checked'));
	});
	$('td.check').change(function()
	{
		
		var box = $(this).find('input[type="checkbox"]');
		
		if(shifted)
		{
			if(last_checkbox_checked !== false)
			{
				var start_check_boxes = last_checkbox_checked.attr('rel');
				var end_check_boxes = box.attr('rel');
				
				for(i = Math.min(start_check_boxes, end_check_boxes); i <= Math.max(start_check_boxes, end_check_boxes); i++)
				{
					$('td.check').find('input[type="checkbox"][rel="' + i + '"]').each(function()
					{
						if($(this).parent().hasClass('check'))
						{
							$(this).prop('checked', true);
						}
					});
				}
			}
		}
		
		if(box.prop('checked'))
			last_checkbox_checked = box;
		else
			last_checkbox_checked = false;
		
		checkbox_changed();
	});
	
	$('select.checkbox_actions').change(function()
	{
		if(typeof checkbox_action == 'function')
		{
			if($(this).val() != 0 && $(this).val() != '0')
				checkbox_action($(this).val());
		}
		
	});
});

function main_checkbox_changed(checked)
{
	console.log('checked: ' + checked);
	if(checkboxes_checked)
		uncheck_all();
	else
		check_all();
	
	checkbox_changed();
}
function checkbox_changed()
{
	var all_checked = true;
	var all_unchecked = true;
	number_of_checkboxes_checked = 0;
	
	$('td.check').find('input[type="checkbox"]').each(function()
	{
		if($(this).parent().hasClass('check'))
		{
			if($(this).prop('checked'))
			{
				number_of_checkboxes_checked++;
				all_unchecked = false;
				checkboxes_checked = true;
			}else
			{
				all_checked = false;
			}
		}
	});
	
	if(all_checked)
		$('th.check').find('input[type="checkbox"]').prop('checked', true).removeClass('partial');
	else
	{
		$('th.check').find('input[type="checkbox"]').prop('checked', false);
		if(number_of_checkboxes_checked> 0)
			$('th.check').find('input[type="checkbox"]').addClass('partial');
		else
			$('th.check').find('input[type="checkbox"]').removeClass('partial');
	}
	
	update_number_of_checkboxes_checked();
}
function check_all()
{
	$('input[type="checkbox"]').each(function()
	{
		if($(this).parent().hasClass('check'))
		{
			$(this).prop('checked', true);
		}
	});
	checkboxes_checked = true;
}
function uncheck_all()
{
	$('input[type="checkbox"]').each(function()
	{
		if($(this).parent().hasClass('check'))
		{
			$(this).prop('checked', false);
		}
	});
	//checkbox_changed();
	checkboxes_checked = false;
}
function update_number_of_checkboxes_checked()
{
	if(number_of_checkboxes_checked == 0)
		$('.checkboxes_checked').css('visibility', 'hidden');
	else
		$('.checkboxes_checked').css('visibility', 'visible');
	
	$('.number_of_checkboxes_checked').html(number_of_checkboxes_checked + ' geselecteerd');
}

function get_all_checked_ids()
{
	var ret = new Array();
	
	$('td.check').find('input[type="checkbox"]').each(function()
	{
		if($(this).parent().hasClass('check'))
		{
			if($(this).prop('checked'))
			{
				if($(this).attr('id') != undefined)
					ret.push($(this).attr('id'));
			}
		}
	});
	
	return ret;
}
function reset_actions_selector()
{
	$('select.checkbox_actions').val('0');
}