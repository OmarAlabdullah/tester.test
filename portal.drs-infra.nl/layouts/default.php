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
			
			<a class="main <?=($controller['render'][0] == 'calendar' && $controller['render'][1] == 'overview' ? 'selected' : '')?>" href="/calendar/overview"><span class="fas fa-calendar"></span>Agenda</a>
			
			<br /><br />
			
			<a class="main" href="/users/logout"><span class="fas fa-lock"></span>Uitloggen</a>
			
		</div>
		
		<?=$content_for_layout?>
		
	</body>
</html>
