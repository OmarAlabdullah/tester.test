
function link_address(project_list_id, cllbck)
{
	project_list_id = parseInt(project_list_id);
	if(project_list_id > 0)
	{
		console.log('project_list_id', project_list_id);
		
		$.getJSON('/ajax/project_lists/get_addresses/' + project_list_id, function(response)
		{
			console.log('get_addresses', response);
			
			var popup_body = $('<div>');
			
			var slct = $('<select>');
			slct.addClass('link_address_zipcode');
			for(zipcode in response['parsed'])
			{
				slct.append('<option value="' + zipcode + '">' + zipcode + '</option>');
				
				
				var slct2 = $('<select>');
				slct2.addClass('link_address_homenumber');
				slct2.attr('rel', zipcode);
				slct2.hide();
				for(homenumber in response['parsed'][zipcode])
				{
					slct2.append('<option value="' + homenumber + '" homenumber="' + response['parsed'][zipcode][homenumber]['homenumber'] + '" addition="' + response['parsed'][zipcode][homenumber]['addition'] + '" client_id="' + response['parsed'][zipcode][homenumber]['client_id'] + '">' + homenumber + '</option>');
				}
				slct2.appendTo(popup_body);
				
			}
			popup_body.prepend(' &nbsp;');
			slct.prependTo(popup_body);
			popup_body.prepend('<h3>Document toewijzen</h3>');
			
			popup_body.append('<br /><br />');
			popup_body.append('<a href="/" class="btn popup_no">Annuleren</a>');
			popup_body.append(' &nbsp;');
			popup_body.append('<a href="/" class="btn btn-alert popup_remove">Verwijderen</a>');
			popup_body.append(' &nbsp;');
			popup_body.append('<a href="/" class="btn btn-accept popup_yes">Toewijzen</a>');
			
			popup(popup_body.get(0));
			$('.popup_no').click(function()
			{
				close_popup();
				return false;
			});
			$('.popup_remove').click(function()
			{
				if(typeof cllbck === "function")
				{
					var jObj = $('.link_address_homenumber:visible option:selected');
					cllbck.call(this, {action: 'remove', zipcode: $('.link_address_zipcode').val(), homenumber: jObj.attr('homenumber'), addition: jObj.attr('addition'), client_id: jObj.attr('client_id')});
				}
				
				return false;
			});
			$('.popup_yes').click(function()
			{
				if(typeof cllbck === "function")
				{
					var jObj = $('.link_address_homenumber:visible option:selected');
					cllbck.call(this, {action: 'update', zipcode: $('.link_address_zipcode').val(), homenumber: jObj.attr('homenumber'), addition: jObj.attr('addition'), client_id: jObj.attr('client_id')});
				}
				
				return false;
			});
			
			_set_selected_zipcode();
			
			slct.change(function()
			{
				_set_selected_zipcode();
			});
			
		});
	}
}

function _set_selected_zipcode()
{
	$('.link_address_homenumber').hide();
	$('.link_address_homenumber[rel="' + $('.link_address_zipcode').val() + '"]').show();
}