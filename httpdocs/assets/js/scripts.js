
var check_address_timeout = 0;
var selected_zipcode = false;
var selected_homenumber = false;
var selected_addition = false;
var notab = true;

$(document).ready(function()
{
	//console.log('start');
	
	var iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
	
	$(this).scroll(function()
	{
		var opacity = ($(window).scrollTop() / 500);
		if(opacity > 1)
			opacity = 1;
		//console.log(opacity);
		header_shadow(opacity);
	});
	
	logos_carousel_init();
	
	$('.hamburger_menu').click(function()
	{
		toggle_mobile_menu();
	});
	
	if(iOS || true)
	{
		$('.zipcode').on('touchstart', function()
		{
		  $(this).attr('type', 'number');
		   
		}).on('keydown blur', function()
		{
		   $(this).attr('type', 'text');
		}).keyup(function()
		{
			var vl = $(this).val();
			$(this).val(vl.toUpperCase());
			
			if($(this).val().length >= 6)
			{
		   	if(_is_zipcode($(this).val()) && $('.homenumber').val() == '')
		   		$('.homenumber').focus();
		   	else
		   		check_address();
		  }
		}).css('font-size', 16);
		
		$('.homenumber').on('touchstart', function()
		{
		  $(this).attr('type', 'number');
		   
		}).on('keydown blur', function()
		{
		   $(this).attr('type', 'text');
		}).keyup(function()
		{
			check_address();
		}).css('font-size', 16);
	}
	
	$('.select_address').click(function()
	{
		if(!$(this).hasClass('disabled'))
		{
			//console.log('selected:');
			//console.log(selected_zipcode);
			//console.log(selected_homenumber);
			//console.log(selected_addition);
			
			$.ajax(
			{
				url: '/ajax/clients/check_address',
				type: 'post',
				data: {zipcode:selected_zipcode, homenumber:selected_homenumber, addition:selected_addition},
				dataType: 'json',
				success: function(response)
				{
					//console.log(response);
					if(response['succes'])
					{
						if(response['check'])
						{
							$('.select_address').unbind('click');
							$('.select_address').addClass('disabled').html('VERZENDEN');
							$('#step1').hide();
							$('#step2').show();
							
							$('.email').unbind('keyup');
							$('.email').keyup(function()
							{
								//if($('.email').val() != '' && $('.phone').val() != '')
								if($('.phone').val() != '')
									$('.select_address').removeClass('disabled');
								else
									$('.select_address').addClass('disabled');
							});
							
							$('.phone').unbind('keydown');
							$('.phone').keydown(function(e)
							{
								//console.log(e.keyCode);
								if(e.keyCode == 109)
									return false;
							});
							
							$('.phone').unbind('keyup');
							$('.phone').keyup(function()
							{
								var phone_val = $('.phone').val();
								console.log(phone_val);
								//if($('.email').val() != '' && $('.phone').val() != '')
								if($('.phone').val() != '')
									$('.select_address').removeClass('disabled');
								else
									$('.select_address').addClass('disabled');
							});
							
							$('.select_address').click(function()
							{
								if(!$(this).hasClass('disabled'))
								{
									var email = $('.email').val();
									var phone = $('.phone').val();
									var phone2 = $('.phone2').val();
									
									$.ajax(
									{
										url: '/ajax/clients/post_contact_details',
										type: 'post',
										data: {zipcode:selected_zipcode, homenumber:selected_homenumber, addition:selected_addition, email:email, phone:phone, phone2:phone2},
										dataType: 'json',
										success: function(response)
										{
											console.log(response);
											if(response['succes'])
											{
												if(response['error'])
												{
													$('#contact_error').html(response['error'] + '<br />');
												}else
												{
													$('#contact_error').empty();
													
													if(response['address_check'] && response['contact_check'] && response['saved_succes'])
													{
														window.location.href = '/contactgegevens_ontvangen';
													}else
													{
														$('#contact_error').html('Helaas is er iets fout gegaan.<br />Neem contact op met onze klantenservice op 0182 239 888<br />');
													}
												}
											}
										},
										error: function(response)
										{
											//console.error(response);
										}
									});
									
								}
								return false;
							});
							
						}else
						{
							window.location.href = '/contactgegevens_doorgeven';
						}
					}
				},
				error: function(response)
				{
					//console.error(response);
				}
			});
			
		}
		return false;
	});
	
	$('.homenumber').keydown(function(e)
	{
		if(e.keyCode == 9 && notab)
		{
			notab = false;
			return false;
		}
	});
	
	$('.qanda_q').click(function()
	{
		var jObj = $(this).parent();
		jObj.toggleClass('open');
		if(jObj.hasClass('open'))
		{
			jObj.find('.qanda_a').slideDown();
		}else
		{
			jObj.find('.qanda_a').slideUp();
		}
	});
});

function _is_zipcode(subject)
{
	subject = subject.replace(/ /g, '');
	subject = subject.replace(/[^\w\s!?]/g,'');
	
	if(isNaN(subject.substr(0, 4)))
		return false;
	
	if(!isNaN(subject.substr(4, 1)))
		return false;
	
	if(!isNaN(subject.substr(5, 1)))
		return false;
	
	return true;
}
function check_address()
{
	clearTimeout(check_address_timeout);
	check_address_timeout = setTimeout(function()
	{
		_check_address();
	}, 500);
}
function _check_address()
{
	var zipcode = $('.zipcode').val();
	zipcode = zipcode.replace(/ /g, '');
	zipcode = zipcode.replace(/[^\w\s!?]/g,'');
	
	var homenumber = parseInt($('.homenumber').val().replace(/[^0-9]/g, ''));
	
	if(_is_zipcode(zipcode) && !isNaN(homenumber) && homenumber > 0)
	{
		//console.log('check_address: ' + zipcode + ' , ' + homenumber);
		
		$.ajax(
		{
			url: '/ajax/clients/get_addresses',
			type: 'post',
			data: {zipcode:zipcode, homenumber:homenumber},
			dataType: 'json',
			success: function(response)
			{
				//console.log(response);
				
				if(response['succes'])
				{
					selected_zipcode = false;
					selected_homenumber = false;
					selected_addition = '';
					
					if(response['addresss_count'] > 0)
					{
						selected_zipcode = response['zipcode'];
						selected_homenumber = response['homenumber'];
						
						$('.addition option').remove();
						if(response['addresss_count'] == 1 && response['additions'][0] == '')
						{
							selected_addition = '';
							
							$('.addition').prop('disabled', true);
							$('.addition').append('<option></option>');
							
							$('.contactdetails_address').empty();
							$('.contactdetails_address').append('<span class="normal">' + response['addresses'][0] + '</span>');
							
							$('.select_address').removeClass('disabled');
						}else
						{
							$('.select_address').addClass('disabled');
							$('.addition').prop('disabled', false);
							$('.addition').append('<option rel="_" value="_">Kies een toevoeging</option>');
							for(a in response['additions'])
							{
								$('.addition').append('<option rel="' + response['additions'][a] + '" value="' + a + '">' + (response['additions'][a] == '' ? '(geen)' : response['additions'][a].toUpperCase()) + '</option>');
							}
							$('.contactdetails_address').html('<i>Kies een toevoeging</i>');
							
							for(d in response['addresses'])
							{
								$('.contactdetails_address').append('<span style="display: none; " rel="' + d + '" class="normal">' + response['addresses'][d] + '</span>');
							}
							
							//kiezen
							$('.addition').unbind('change');
							$('.addition').change(function()
							{
								var jObj = $(this).find(':selected');
								if(jObj.length)
									selected_addition = jObj.attr('rel').toUpperCase();
								
								$('.contactdetails_address i').remove();
								$('.normal').hide();
								$('.normal[rel="' + $(this).val() + '"]').show();
								
								if($(this).val() != '_')
									$('.select_address').removeClass('disabled');
								else
									$('.select_address').addClass('disabled');
								
							});
						}
					}else
					{
						$('.addition option').remove();
						$('.addition').prop('disabled', true);
						$('.contactdetails_address').html('<i>Adres niet gevonden</i>');
						$('.select_address').addClass('disabled');
					}
				}
			},
			error: function(response)
			{
				//console.error(response);
			}
		});
	}
}
function header_shadow(opacity)
{
	$('.header.loose').stop().animate({boxShadow: '0 4px 14px -2px rgba(100, 100, 100, ' + opacity + ')'}, 1000);
}

function logos_carousel_init()
{
	var i = 0;
	$('.partners_holder').find('.partner').each(function()
	{
		$(this).attr('rel', i);
		$(this).css('left', i * 33.33 + '%');
		$(this).show();
		i++;
	});
	
	/*setTimeout(function()
	{
		logos_carousel_shift(3);
	}, 2000);*/
}
function logos_carousel_shift(amount)
{
	if($('.partners_holder').find('.partner').first().css('position') == 'absolute')
	{
		var i = 0;
		$('.partners_holder').find('.partner').each(function()
		{
			var rl = parseInt($(this).attr('rel'));
			rl -= amount;
			$(this).animate({'left': rl * 33.33 + '%'}, 1000, function()
			{
				if(i < amount)
				{
					$(this).detach().appendTo($('.partners_holder'));
				}
				i++;
			});
		});
	}
	
	setTimeout(function()
	{
		logos_carousel_init();
	}, 1100);
}

var mobile_menu = false;
function toggle_mobile_menu()
{
	if(mobile_menu)
		close_mobile_menu();
	else
		open_mobile_menu();
	mobile_menu = !mobile_menu;
}
function open_mobile_menu()
{
	var mm = $('<div>');
	mm.addClass('mobile_menu_holder');
	
	$('.header_menu').find('a').each(function()
	{
		mm.append($(this).clone());
	});
	
	mm.hide();
	mm.appendTo($('body'));
	mm.slideDown();
}
function close_mobile_menu()
{
	$('.mobile_menu_holder').slideUp('fast', function()
	{
		$('.mobile_menu_holder').remove();
	});
}