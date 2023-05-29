<div class="tabs">
	<a class="selected" href="/project_lists/details/<?=$project_list['Project_list']['id']?>">
		<span class="far fa-address-card"></span>
		<br />
		Adressen
	</a>
	<a href="/project_lists/settings/<?=$project_list['Project_list']['id']?>">
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

<h1><?=$project_list['Project_list']['name']?></h1>
<h5>Aangemaakt op <?=date('d-m-Y', strtotime($project_list['Project_list']['created']))?></h5>

<div class="page_actions">
	<a class="btn" href="/project_lists/details/<?=$project_list['Project_list']['id']?>"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>

<form method="post" action="<?=SELF?>">
	
	<label for="street">
		Straat:
	</label>
	<input type="text" id="street" name="Client[street]" placeholder="Straat" value="" autocomplete="new-password" required>
	
	<br /><br />
	
	<label for="homenumber">
		Huisnummer:
	</label>
	<input type="text" id="homenumber" name="Client[homenumber]" placeholder="Huisnummer" value="" autocomplete="new-password" required>
	
	<br /><br />
	
	<label for="addition">
		Toevoeging:
	</label>
	<input type="text" id="addition" name="Client[addition]" placeholder="Toevoeging" value="" autocomplete="new-password">
	
	<br /><br />
	
	<label for="zipcode">
		Postcode:
	</label>
	<input type="text" id="zipcode" name="Client[zipcode]" placeholder="Postcode" value="" autocomplete="new-password" required>
	
	<br /><br />
	
	<label for="city">
		Woonplaats:
	</label>
	<input type="text" id="city" name="Client[city]" placeholder="Woonplaats" value="" autocomplete="new-password" required>
	
	<br /><br />
	
	<input type="submit" value="Opslaan" />
	
</form>