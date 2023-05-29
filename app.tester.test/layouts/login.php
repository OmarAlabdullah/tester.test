<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="robots" content="index,follow">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>-->
		<link rel="manifest" href="/assets/manifest.json">
		<link rel="apple-touch-icon" sizes="192x192" href="/assets/images/app_logo_192.png">
		<link rel="apple-touch-icon" sizes="512x512" href="/assets/images/app_logo_512.png">
		<?=$keywords_for_layout?>
		<?=$description_for_layout?>
		<title><?=$title_for_layout ?></title>
	</head>
	<body>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
		<?=$css_for_layout ?>
		<?=$js_for_layout ?>
		
		<?=$content_for_layout?>
		
		<script>
			$(document).ready(function()
			{
				<?php
					if($controller['iphone'])
					{
				?>
				if(!(window.matchMedia('(display-mode: standalone)').matches))
				{
					show_iphone_install_tutorial();
				}
				<?php
					}
				?>
			});
			function show_iphone_install_tutorial()
			{
				//alert('install');
			}
		</script>
		
	</body>
</html>
