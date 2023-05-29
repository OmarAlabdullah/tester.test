<h1>Tijdvak</h1>
<h5>Tijdvak bewerken/toevoegen</h5>

<div class="page_actions">
	<a class="btn" href="/calendar/timeframes"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>

<form method="post" action="<?=SELF?>">
	
	<label for="timeframe">
		Tijdvak:
	</label>
	<input type="text" id="timeframe" name="Timeframe[timeframe]" placeholder="08:00 - 09:00" value="<?=$timeframe['Timeframe']['timeframe']?>">
	
	<br /><br />
	
	<label for="email_text">
		Email tekst:
	</label>
	<input type="text" id="email_text" name="Timeframe[email_text]" placeholder="tussen 8:00 en 09:00" value="<?=$timeframe['Timeframe']['email_text']?>">
	
	<br /><br />
	
	<input type="submit" value="Opslaan" />
	
</form>