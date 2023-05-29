<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="icon" type="image/png" href="/assets/images/favicon-16.png" sizes="16x16">
		<link rel="icon" type="image/png" href="/assets/images/favicon-32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/assets/images/favicon-192.png" sizes="192x192">
		<meta name="robots" content="index,follow">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>-->
		<?=$keywords_for_layout?>
		<?=$description_for_layout?>
		<title><?=$title_for_layout ?></title>
	</head>
	<body>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
		<?=$css_for_layout ?>
		<?=$js_for_layout ?>
		
		<div class="header">
			<img src="/assets/images/drs-logo.png" />
		</div>
		
		<div class="menu">
			
			<a class="main <?=($controller['render'][0] == 'dashboard' ? 'selected' : '')?>" href="/"><span class="fas fa-tachometer-alt"></span>Dashboard</a>
			
			<a class="main <?=($controller['render'][0] == 'project_lists' && $controller['render'][1] == 'overview' ? 'selected' : '')?>" href="/project_lists/overview"><span class="fas fa-th-list"></span>Projecten</a>
			
			<a class="<?=($controller['render'][0] == 'project_lists' && $controller['render'][1] == 'import' ? 'selected' : '')?>" href="/project_lists/import">Lijst importeren</a>
			
			<?php			if(permissions('master'))
			{
			?>
			<a class="<?=($controller['render'][0] == 'project_lists' && $controller['render'][1] == 'uploads' ? 'selected' : '')?>" href="/project_lists/uploads">Uploads</a>
			
			<?php			}
			?>
			
			<a class="main <?=($controller['render'][0] == 'calendar' && $controller['render'][1] == 'overview' ? 'selected' : '')?>" href="/calendar/overview"><span class="fas fa-calendar"></span>Agenda</a>
			
			<?php			//if(permissions('master'))
			//{
			?>
			<a class="<?=($controller['render'][0] == 'calendar' && $controller['render'][1] == 'timeframes' ? 'selected' : '')?>" href="/calendar/timeframes">Tijdvakken</a>
			<a class="<?=($controller['render'][0] == 'notifications' && $controller['render'][1] == 'overview' ? 'selected' : '')?>" href="/notifications/overview">Notities</a>
			
			<?php			//}
			?>
			
			<a class="main <?=($controller['render'][0] == 'mail_templates' ? 'selected' : '')?>" href="/mail_templates/overview"><span class="fas fa-mail-bulk"></span>Mail templates</a>
			
			<a class="main <?=($controller['render'][0] == 'users' && $controller['render'][1] == 'overview' ? 'selected' : '')?>" href="/users/overview"><span class="fas fa-user-tie"></span>Gebruikers</a>
			
			<a class="main <?=($controller['render'][0] == 'workers' && $controller['render'][1] == 'overview' ? 'selected' : '')?>" href="/workers/overview"><span class="fas fa-user-cog"></span>Werkers</a>
			
			<a class="<?=($controller['render'][0] == 'hours' ? 'selected' : '')?>" href="/hours/overview">Uren</a>
			
			<?php			if(permissions('master'))
			{
			?>
			<a class="main <?=($controller['render'][0] == 'logs' ? 'selected' : '')?>" href="/logs"><span class="fas fa-th-list"></span>Log's</a>
			<a class="main <?=($controller['render'][0] == 'tests' && empty($controller['render'][1]) ? 'selected' : '')?>" href="/tests/addresses"><span class="fas fa-vial"></span>Tests</a>
			<a class="<?=($controller['render'][0] == 'tests' && $controller['render'][1] == 'addresses' ? 'selected' : '')?>" href="/tests/addresses">Test adressen</a>
			<a class="<?=($controller['render'][0] == 'tests' && $controller['render'][1] == 'upload' ? 'selected' : '')?>" href="/tests/upload">Upload</a>
			<a class="<?=($controller['render'][0] == 'tests' && $controller['render'][1] == 'removed_photos' ? 'selected' : '')?>" href="/tests/removed_photos">Verwijderde fotos</a>
			<?php			}
			?>
			
			<br /><br />
			
			<a class="main" href="/users/logout"><span class="fas fa-lock"></span>Uitloggen</a>
			
		</div>
		
		<?=$content_for_layout?>
		
	</body>
</html>
