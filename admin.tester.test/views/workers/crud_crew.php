<h1>Ploeg <?=(isset($crew['Crew']['id']) ? 'aanpassen' : 'toevoegen')?></h1>
<h5>Werkploeg <?=(isset($crew['Crew']['id']) ? 'aanpassen' : 'toevoegen')?></h5>

<div class="page_actions">
	<a class="btn" href="/workers/crews"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>

<form method="post" action="<?=SELF?>">
	
	<?php
		if(isset($crew['Crew']['id']))
		{
	?>
	<input type="hidden" name="Crew[id]" value="<?=$crew['Crew']['id']?>" />
	<?php
		}
	?>
	
	<label for="name">
		Naam:
	</label>
	<input type="text" id="name" name="Crew[name]" placeholder="Naam" value="<?=$crew['Crew']['name']?>">
	
	<br /><br />
	
	<input type="submit" value="Opslaan" />
	
</form>