<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="robots" content="index,follow">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>-->
		<link rel="manifest" href="/assets/manifest.json?r<?=$controller['revision']?>">
		<link rel="apple-touch-icon" sizes="192x192" href="/assets/images/gas-icon-192.png?r=<?=$controller['revision']?>">
		<link rel="apple-touch-icon" sizes="512x512" href="/assets/images/gas-icon-512.png?r=<?=$controller['revision']?>">
		<link rel="icon" type="image/png" href="/assets/images/gas-icon-16.png?r=<?=$controller['revision']?>" sizes="16x16">
		<link rel="icon" type="image/png" href="/assets/images/gas-icon-32.png?r=<?=$controller['revision']?>" sizes="32x32">
		<link rel="icon" type="image/png" href="/assets/images/gas-icon-192.png?r=<?=$controller['revision']?>" sizes="192x192">
		<?=$keywords_for_layout?>
		<?=$description_for_layout?>
		<title><?=$title_for_layout ?></title>
	</head>
	<body>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
		<?=$css_for_layout ?>
		<?=$js_for_layout ?>
		
		<div id="app_master">
			
			<div id="app_header">
				<a id="app_header_left">...</a>
				<a href="/" id="app_header_center">DRS Infra</a>
				<a id="settings" href="/workers/settings">
					<span class="fas fa-cog"></span>
				</a>
			</div>
			
			<?=$content_for_layout?>
			
			<div id="app_footer">
				<a href="/" class="<?=($controller['render'][0] == 'home' || $controller['render'][0] == 'clients' ? 'selected' : '')?>"><span class="fas fa-calendar-alt"></span><br /><?=tl('Agenda')?></a>
				<a href="/projects/overview" class="<?=($controller['render'][0] == 'projects' ? 'selected' : '')?>"><span class="fas fa-directions"></span><br /><?=tl('Projecten')?></a>
				<a href="/hours/overview" class="<?=($controller['render'][0] == 'hours' ? 'selected' : '')?>"><span class="far fa-clock"></span><br /><?=tl('Uren')?></a>
			</div>
			
		</div>
		
	</body>
</html>
