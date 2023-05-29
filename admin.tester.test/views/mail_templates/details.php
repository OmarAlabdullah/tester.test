
<div class="page_actions">
	<a class="btn" href="/mail_templates/overview"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>

<form method="post" action="<?=SELF?>">
	
	<select name="type">
		<option value="appointment" <?=($mail_template['Mail_template']['type'] == 'appointment' ? 'selected' : '')?>>Afspraakbevestiging</option>
		<option value="contact_details" <?=($mail_template['Mail_template']['type'] == 'contact_details' ? 'selected' : '')?>>Contactgegevens ontvangen</option>
		<option value="cancel_appointment" <?=($mail_template['Mail_template']['type'] == 'cancel_appointment' ? 'selected' : '')?>>Annulering afspraak</option>
	</select>
	
	<br /><br />
	
	<input type="text" name="name" value="<?=$mail_template['Mail_template']['name']?>" placeholder="Template Naam" autocomplete="off" />
	
	<br /><br />
	
	<input type="text" style="width: 100%; " name="subject" value="<?=$mail_template['Mail_template']['subject']?>" placeholder="Email onderwerp" autocomplete="off" />
	
	<br /><br />
	
	<textarea name="content" class="mail_template" placeholder="Mail content"><?=db_to_textarea($mail_template['Mail_template']['content'])?></textarea>
	
	<br /><br />
	
	[datum] = Datum van de afspraak, [tijd] = Tijdvak van de afspraak
	
	<br /><br />
	
	<label for="default">
		<input type="checkbox" id="default" name="default" <?=($mail_template['Mail_template']['default'] == 1 ? 'checked' : '')?> /> Als standaard instellen
	</label>
	
	<br />
	
	<input type="submit" value="Opslaan" />
	
</form>