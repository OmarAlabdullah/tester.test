
var current_page = 1;
var new_page = 1;
var number_of_pages = 1;

$(document).ready(function()
{
	$('.pagination').find('a[rel]').each(function()
	{
		var page_number = parseInt($(this).attr('rel'));
		if(page_number > number_of_pages)
			number_of_pages = page_number;
		
		if($(this).hasClass('selected'))
			if(page_number > 0)
				current_page = page_number;
		
	}).click(function()
	{
		pagination_click($(this).attr('rel'));
		return false;
	});
	
	$(document).keydown(function(e)
	{
		if(e.keyCode == 37)
			if(!$('input').is(":focus"))
				go_to_page(current_page - 1);
		if(e.keyCode == 39)
			if(!$('input').is(":focus"))
				go_to_page(current_page + 1);
		
	});
});

function pagination_click(rl)
{
	var page_number = parseInt(rl);
	if(page_number > 0)
	{
		new_page = page_number;
	}else
	{
		if(rl == 'prev')
			new_page = current_page - 1;
		if(rl == 'next')
			new_page = current_page + 1;
	}
	
	go_to_page(new_page);
}

function go_to_page(page)
{
	if(page < 1)
		page = 1;
	if(page > number_of_pages)
		page = number_of_pages;
	
	$('.pagination').find('a[rel]').removeClass('selected');
	$('.pagination').find('a[rel="' + page + '"]').addClass('selected');
	
	$('.pagination_element').hide();
	$('.pagination_element[rel="' + page + '"]').show();
	
	if(page != current_page)
		close_client_window();
	
	current_page = page;
}