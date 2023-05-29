<h1>Gebruiker toevoegen</h1>
<h5>Administratieve gebruiker toevoegen</h5>

<div class="page_actions">
	<a class="btn" href="/users/overview"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>

<form method="post" action="<?=SELF?>">
	
	<label for="name">
		Naam:
	</label>
	<input type="text" id="name" name="User[name]" placeholder="Naam" value="<?=$user['User']['name']?>">
	
	<br /><br />
	
	<label for="username">
		Gebruikersnaam:
	</label>
	<input type="text" id="username" name="User[username]" placeholder="Gebruikersnaam" value="<?=$user['User']['username']?>">
	
	<br /><br />
	
	<label for="password">
		Wachtwoord:
	</label>
	<input type="password" id="password" name="User[password]" placeholder="Wachtwoord" value="">
	
	<br /><br />
	
	<label for="email">
		Email:
	</label>
	<input type="text" id="email" name="User[email]" placeholder="Email" value="<?=$user['User']['email']?>">
	
	<br /><br />
	
	<label for="phone">
		Telefoonnummer:
	</label>
	<input type="text" id="phone" name="User[phone]" placeholder="Telefoonnummer" value="<?=$user['User']['phone']?>">
	
	<br /><br />
	
	<label for="permissions">
		Rechten:
	</label>
	<select name="User[permissions]" id="permissions">
		<option value="viewer">Alleen agenda</option>
		<option value="admin">Admin</option>
        <?php
			if(permissions('master'))
			{
		?>
		<option value="master">Master</option>
                <?php
			}
		?>
	</select>
	
	<br /><br />
	
	<input type="submit" value="Opslaan" />
	
</form>