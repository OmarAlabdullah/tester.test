<h1>Werker <?=(isset($worker['Worker']['id']) ? 'aanpassen' : 'toevoegen')?></h1>
<h5>Werker <?=(isset($worker['Worker']['id']) ? 'aanpassen' : 'toevoegen')?></h5>

<div class="page_actions">
	<a class="btn" href="/workers/overview"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>

<?php
	if(permissions('master') && isset($worker['Worker']['id']))
	{
?>
<div class="info_bar">
	Laatste online: <?=date('d-m-Y H:i', strtotime($worker['Worker']['last_online']))?>
</div>
<?php
	}
?>

<form method="post" action="<?=SELF?>">
	
	<?php
		if(isset($worker['Worker']['id']))
		{
	?>
	<input type="hidden" name="Worker[id]" value="<?=$worker['Worker']['id']?>" />
	<?php
		}
	?>
	
	<label for="crew">
		Ploeg:
	</label>
	<select name="Worker[crew_id]">
		<?php
			foreach($crews as $crew)
			{
		?>
		<option value="<?=$crew['Crew']['id']?>"><?=$crew['Crew']['name']?></option>
		<?php
			}
		?>
	</select>
	
	<br /><br />
	
	<label for="name">
		Naam:
	</label>
	<input type="text" id="name" name="Worker[name]" placeholder="Naam van werker" value="<?=$worker['Worker']['name']?>">
	
	<br /><br />
	
	<label for="email">
		Email:
	</label>
	<input type="text" id="email" name="Worker[email]" placeholder="Email van werker" value="<?=$worker['Worker']['email']?>">
	
	<br /><br />
	
	<label for="password">
		Wachtwoord: <?=(isset($worker['Worker']['id']) ? '(invullen als wachtwoord wijzigen)' : '')?>
	</label>
	<input type="password" id="password" name="Worker[password]" placeholder="Wachtwoord voor de app" value="">
	
	<br /><br />
	
	<label for="wage">
		Uurloon:
	</label>
	<input type="text" id="wage" name="Worker[wage]" placeholder="Uurloon van werker" value="<?=number_format($worker['Worker']['wage'], 2, ',', '')?>">
	
	<br /><br />
	
	<label for="crew">
		Taal van de app:
	</label>
	<select name="Worker[language_id]">
		<option value="1">Nederlands</option>
		<option value="2" <?=($worker['Worker']['language_id'] == 2 ? 'selected' : '')?>>Engels</option>
		<option value="3" <?=($worker['Worker']['language_id'] == 3 ? 'selected' : '')?>>Lituaans</option>
	</select>
	
	<br /><br />
	
	<input type="submit" value="Opslaan" />
	
</form>