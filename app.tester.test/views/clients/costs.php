
<div class="app_list">

	<a class="app_list_item">
<!--		test: --><?php //=($project_list['Project_list']['required_additional_data'])?>
<!--		<span class="app_list_item_append">-->
<!--			<span class="app_list_item_checkbox --><?php //=($project_list['Project_list']['required_additional_data'])?><!--" id="gs">-->
<!--				<span class="app_list_item_checkbox_cursor"></span>-->
<!--			</span>-->
<!--		</span>-->
        <?php
        $required_photos = explode('|', $project_list['Project_list']['required_photos']);
        foreach($required_photos as $required_photo)
        {

            ?>
            <a class="app_list_item">
                <?=$required_photo?>
            </a>
            <?php
        }
        ?>
	</a>


<!--    $required_additional_data = explode('|', $project_list['Project_list']['required_additional_data']);-->
    <?php
    $required_additional_data = explode('|', $project_list['Project_list']['required_additional_data']);
    foreach($required_additional_data as $d)
    {
    ?>
        d: <?=$d?> <span class="app_list_item_checkbox_cursor"> <?=$d?></span>
    <?php
    }
    ?>

	
	<a class="app_list_item">
		Meerwerk
		<span class="app_list_item_append">
			<span class="app_list_item_checkbox <?=(strlen($client['Client']['meerwerk']) > 0 ? 'checked' : '')?>" id="mw">
				<span class="app_list_item_checkbox_cursor"></span>
			</span>
		</span>
	</a>
	<a class="app_list_item app_list_item_high" id="mw_input_holder" style="display: <?=(strlen($client['Client']['meerwerk']) > 0 ? '' : 'none')?>; ">
		<textarea class="app_list_item_textarea" placeholder="<?=tl('Vul het meerwerk in')?>"><?=(strlen($client['Client']['meerwerk']) > 0 ? $client['Client']['meerwerk'] : '')?></textarea>
	</a>
	
	<br /><br />
	
	<a class="app_list_item_ghost">
		<div id="save_settings" class="iphone_button"><?=tl('Opslaan')?></div>
	</a>
	
	<div style="clear: both;"></div>
	
	<div class="app_center">
		<a href="/clients/details/<?=$client['Client']['id']?>"><?=tl('Terug naar woning')?></a>
	</div>
	
	<br /><br />
	
</div>

<script>
$(document).ready(function()
{
	back_button('<?=tl('Woning')?>', '/clients/details/<?=$client['Client']['id']?>');
	
	$('#ol').change(function()
	{
		if($(this).hasClass('checked'))
		{
			_show_ol_input();
		}else
		{
			_hide_ol_input();
		}
	});
	$('#mw').change(function()
	{
		if($(this).hasClass('checked'))
		{
			_show_mw_input();
		}else
		{
			_hide_mw_input();
		}
	});
	$('#save_settings').click(function()
	{
		$(this).addClass('depressed');
		var gs = $('#gs').hasClass('checked');
		var vwi = $('#vwi').hasClass('checked');
		var ol = $('#ol').hasClass('checked');
		var mw = $('#mw').hasClass('checked');
		
		var error = false;
		
		if(ol)
		{
			var ol_val = $('#ol_input_holder').find('input').val();
			if(!(ol_val.length > 0))
			{
				error = true;
				white_popup('<b><?=tl('Vul een geldige lengte in')?></b><br /><?=tl('Vul de overlengte in in het textveld')?>', '<?=tl('OK')?>', function()
				{
					close_white_popup(function()
					{
						$('#ol_input_holder').find('input').focus();
						$('#save_settings').removeClass('depressed');
					});
				});
			}
		}
		
		if(mw)
		{
			var mw_val = $('#mw_input_holder').find('textarea').val();
			if(!(mw_val.length > 0) && !error)
			{
				error = true;
				white_popup('<b><?=tl('Vul het meerwerk')?></b><br /><?=tl('Vul een omschrijving van het meerwerk in in het textveld')?>', '<?=tl('OK')?>', function()
				{
					close_white_popup(function()
					{
						$('#mw_input_holder').find('textarea').focus();
						$('#save_settings').removeClass('depressed');
					});
				});
			}
		}
		
		if(!error)
		{

			console.log(gs, vwi, ol, mw, ol_val, mw_val);
			
			var fd = new FormData();
			fd.append('client_id', parseInt(<?=$client['Client']['id']?>));
			fd.append('gas_stop', gs);
			fd.append('vwi', vwi);
			fd.append('ol', (ol ? parseFloat(ol_val) : 0.0));
			fd.append('meerwerk', (mw ? mw_val : ''));
			
			$.ajax(
			{
				url: '/ajax/clients/save_additional_information',
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
						black_popup('<?=tl('Opgeslagen')?>');
						setTimeout(function()
						{
							close_black_popup(function()
							{
								window.location.href = '/clients/details/<?=$client['Client']['id']?>';
							});
						}, 500);
					}else
					{
						white_popup('<?=tl('<b>Fout bij opslaan</b><br />Probeer het opnieuw')?>', '<?=tl('OK')?>', function()
						{
							close_white_popup();
						});
					}
				}
			});
			
		}
	});
});
function _show_ol_input()
{
	$('#ol_input_holder').slideDown(function()
	{
		$(this).find('input').focus();
	});
}
function _hide_ol_input()
{
	$('#ol_input_holder').slideUp();
}
function _show_mw_input()
{
	$('#mw_input_holder').slideDown(function()
	{
		$(this).find('textarea').focus();
	});
}
function _hide_mw_input()
{
	$('#mw_input_holder').slideUp();
}
</script>