
<div class="tabs">
	<a href="/project_lists/details/<?=$project_list['Project_list']['id']?>">
		<span class="far fa-address-card"></span>
		<br />
		Adressen
	</a>
	<a class="selected" href="/project_lists/settings/<?=$project_list['Project_list']['id']?>">
		<span class="fas fa-cog"></span>
		<br />
		Instellingen
	</a>
	<a href="/project_lists/documents/<?=$project_list['Project_list']['id']?>">
		<span class="fas fa-file-alt"></span>
		<br />
		Documenten
	</a>
</div>

<h1>Instellingen</h1>
<h5><?=$project_list['Project_list']['name']?></h5>
<!--
<div class="page_actions">
	<a class="btn" href="/project_lists/details/<?=$project_list['Project_list']['id']?>"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>
-->

<br /><br />

<form method="post" action="<?=SELF?>" id="project_list_form">
	
	<label for="zipcode">
		Projectnaam:
	</label>
	<input type="text" id="zipcode" name="Project_list[name]" placeholder="Postcode" value="<?=$project_list['Project_list']['name']?>" autocomplete="">
	
	<br /><br />
	
	<label for="zipcode">
		Projectnummer:
	</label>
	<input type="text" id="zipcode" name="Project_list[project_number]" placeholder="Postcode" value="<?=$project_list['Project_list']['project_number']?>" autocomplete="">
	
	<br /><br />
	
	<label for="status">
		Status:
	</label>
	<select id="status" name="Project_list[status]" autocomplete="">
		<option value="open" <?=($project_list['Project_list']['status'] == 'open' ? 'selected' : '')?>>Openstaand</option>
		<option value="finished" <?=($project_list['Project_list']['status'] == 'finished' ? 'selected' : '')?>>Afgerond</option>
		<option value="special" <?=($project_list['Project_list']['status'] == 'special' ? 'selected' : '')?>>Bijzonderheden</option>
	</select>
	
	<br /><br />
	
	<label for="zipcode">
		Verplichte fotos:
	</label>
	
	<br />
	
	<span id="required_photos_holder">
	<?php
		foreach($project_list['Project_list']['required_photos_array'] as $required_photo)
//        echo $required_photo;
		{
	?>
		<input class="required_photo" type="text" value="<?=$required_photo?>" />
	<?php
		}
	?>
		<input class="required_photo" type="text" value="" />
	</span>


<!--    /////////////// die heb ik toegevoegd voor additional_data //////////////////////////-->
    <label for="additional_data">
        Extra gegevens:
    </label>

    <br />

    <span id="additional_data_holder">
    <?php
    foreach($project_list['Project_list']['additional_data_array'] as $additional_data)
    {
        ?>
        <input class="additional_data" type="text" value="<?=$additional_data?>" />
        <?php
    }
    ?>
    <input class="additional_data" type="text" value="" />
</span>

<!--    /////////////////////////////////////////////////////////////////////////////////////////-->

	<label for="zipcode">
		Ploeg(en):
	</label>
	
	<br />
	
	<?php
		foreach($crews as $crew)
		{
	?>
	<label for="crew_<?=$crew['Crew']['id']?>">
		<input type="checkbox" name="crews[<?=$crew['Crew']['id']?>]" value="<?=$crew['Crew']['id']?>" id="crew_<?=$crew['Crew']['id']?>" <?=(isset($project_list['crews'][$crew['Crew']['id']]) ? 'checked' : '')?> /> <?=$crew['Crew']['name']?>
	</label>
	<?php
		}
	?>
	
	<br /><br />
	
	<input type="submit" value="Opslaan" />
	
</form>

<script>
$(document).ready(function()
{
	check_required_photos_inputs();
	required_photos_inputs_events();
	$('#project_list_form').submit(function()
	{
		var i = 0;
		$('input.required_photo').each(function()
		{
			$(this).attr('name', 'required_photo[' + i + ']');
			i++;
		});
		return true;
	});
});
function check_required_photos_inputs()
{
	var last_val = $('input.required_photo').last().val();
	var required_photos_inputs_count = $('input.required_photo').length;
	if(last_val != '')
	{
			add_required_photos_input();
	}else
		if($($('input.required_photo').get(required_photos_inputs_count-2)).val() == '')
			remove_required_photos_input();
}
function add_required_photos_input()
{
	$('#required_photos_holder').append('<input class="required_photo" type="text" value="" />');
	required_photos_inputs_events();
}
function required_photos_inputs_events()
{
	$('input.required_photo').unbind('keyup');
	$('input.required_photo').keyup(function()
	{
		check_required_photos_inputs();
	});
}
function remove_required_photos_input()
{
	$('input.required_photo').last().remove();
}


//////////////////////////////die heb ik toegevoegd voor additional_data////////////////////////////////////
$(document).ready(function() {
    check_additional_data_inputs();
    additional_data_inputs_events();
    $('#project_list_form').submit(function() {
        var i = 0;
        $('input.additional_data').each(function() {
            $(this).attr('name', 'additional_data[' + i + ']');
            i++;
        });
        return true;
    });
});

function check_additional_data_inputs() {
    var last_val = $('input.additional_data').last().val();
    var additional_data_inputs_count = $('input.additional_data').length;
    if (last_val != '') {
        add_additional_data_input();
    } else if ($($('input.additional_data').get(additional_data_inputs_count - 2)).val() == '') {
        remove_additional_data_input();
    }
}

function add_additional_data_input() {
    $('#additional_data_holder').append('<input class="additional_data" type="text" value="" />');
    additional_data_inputs_events();
}

function additional_data_inputs_events() {
    $('input.additional_data').unbind('keyup');
    $('input.additional_data').keyup(function() {
        check_additional_data_inputs();
    });
}

function remove_additional_data_input() {
    $('input.additional_data').last().remove();
}



</script>
<style>
input.required_photo
{
	display: block;
	margin-bottom: 5px;
}
input.additional_data {
    display: block;
    margin-bottom: 5px;
}

</style>