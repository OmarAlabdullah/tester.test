<h1>Tests</h1>
<h5>Addresen opzoeken</h5>

<div class="page_actions">
	<a class="btn" href="/"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>

<form method="post" action="<?=SELF?>">
	
	<label for="zipcode">
		Postcode:
	</label>
	<input type="text" id="zipcode" name="zipcode" placeholder="Postcode" value=" " autocomplete="">
	
	<br /><br />
	
	<label for="homenumber">
		Huisnummer:
	</label>
	<input type="text" id="homenumber" name="homenumber" placeholder="Huisnummer" value="" autocomplete="7db39dnas908shdd">
	
	<br /><br />
	
	<div id="output"></div>
	
	<select id="additions" style="display: none;"></select>
	
	<br /><br />
	
	<table id="addresses" style="display: none;">
	</table>
	
</form>

<script>
	
	var zipcode = '';
	var homenumber = 0;
	
	$(document).ready(function()
	{
		$('#zipcode').keyup(function()
		{
			zipcode = $(this).val();
			zipcode = zipcode.toUpperCase();
			zipcode = zipcode.replace(/[^\w\s]/gi, '');
			zipcode = zipcode.replace(/ /g,'');
			
			$(this).val(zipcode);
			
			if(zipcode.length == 6)
				check_address();
		});
		
		$('#homenumber').keyup(function()
		{
			homenumber = $(this).val();
			homenumber = homenumber.toUpperCase();
			homenumber = homenumber.replace(/[^\w\s]/gi, '');
			homenumber = homenumber.replace(/ /g,'');
			homenumber = parseInt(homenumber);
			
			if(!(homenumber > 0))
				homenumber = '';
			
			$(this).val(homenumber);
			
			if(homenumber > 0)
				check_address();
		});
	});
	
	function check_address()
	{
		if(zipcode.length == 6 && homenumber > 0)
		{
			console.log(zipcode);
			console.log(homenumber);
			
			$('#output').empty();
			$('#additions').hide();
			$('#addresses').hide();
			
			$.ajax(
			{
				url: '/ajax/clients/check_address',
				type: 'post',
				data: {zipcode:zipcode, homenumber:homenumber},
				dataType: 'json',
				success: function(response)
				{
					console.log(response);
					
					if(response['additions'])
					{
						if(response['addresses'].length >= 1)
						{
							$('#addresses').empty();
							$('#addresses').append('<tr><th>Adres</th></tr>');
							for(a in response['addresses'])
							{
								$('#addresses').append('<tr><td>' + response['addresses'][a] + '</td></tr>');
								$('#addresses').show();
							}
						}
						
						if(response['additions'].length == 1)
						{
							if(response['additions'][0] == '')
							{
								$('#output').html('gevonden');
								return;
							}else
							{
								$('#output').html(response['additions'][0].toUpperCase());
								return;
							}
						}
						
						if(response['additions'].length >= 1)
						{
							$('#additions').empty();
							for(a in response['additions'])
							{
								var opt = $('<option>' + response['additions'][a].toUpperCase() + '</option>');
								
								opt.appendTo($('#additions'));
								$('#additions').show();
							}
						}
					}
					
				},
				error: function(response)
				{
					console.error(response);
				}
			});
		}
	}
	
</script>