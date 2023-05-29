<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="icon" type="image/png" href="/assets/images/favicon-16.png" sizes="16x16">
		<link rel="icon" type="image/png" href="/assets/images/favicon-32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/assets/images/favicon-192.png" sizes="192x192">
		<meta name="robots" content="index,follow">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>-->
		<?=$keywords_for_layout?>
		<?=$description_for_layout?>
		<title><?=$title_for_layout ?></title>
	</head>
	<body>
		<?=$css_for_layout ?>
		<?=$js_for_layout ?>
		
		<div class="header_holder"></div>
		<div class="header loose">
			<div class="header_content">
				<a href="/">
					<img class="drs-infra-logo <?=($controller['webp'] ? 'webp' : 'no-webp')?>" src="/assets/images/drs-logo.<?=($controller['webp'] ? 'webp' : 'png')?>" height="86" />
				</a>
				<div class="header_menu">
					<a href="/"<?=($controller['params'][0] == '' ? ' class="active"' : '')?>>Home</a>
					<a href="/diensten"<?=($controller['params'][0] == 'diensten' ? ' class="active"' : '')?>>Diensten</a>
					<a href="/vraag_en_antwoord"<?=($controller['params'][0] == 'vraag_en_antwoord' ? ' class="active"' : '')?>>Vraag en Antwoord</a>
					<a href="/contactgegevens_doorgeven"<?=($controller['params'][0] == 'contactgegevens_doorgeven' ? ' class="active"' : '')?>>Mijn Contactgegevens Doorgeven</a>
					<a href="/contact"<?=($controller['params'][0] == 'contact' ? ' class="active"' : '')?>>Contact</a>
					<div class="hamburger_menu"><span></span><span></span><span></span></div>
				</div>
			</div>
		</div>
		
		<div class="content_for_layout_holder">
			<?=$content_for_layout?>
		</div>
		
		<h2>Wij werken in opdracht van:</h2>
		
		<div class="site-width">
			<div class="partners_holder">
				<div class="partner"><a href="https://www.stedin.net/" target="_blank" rel="noreferrer noopener"><img src="/assets/images/partner-1.png" /></a></div>
				<!--<div class="partner"><a href="/"><img src="/assets/images/partner-2.png" /></a></div>-->
				<div class="partner"><a href="https://www.enexis.nl/" target="_blank" rel="noreferrer noopener"><img src="/assets/images/partner-3.png" /></a></div>
				<div class="partner"><a href="https://vanvulpen.eu/" target="_blank" rel="noreferrer noopener"><img src="/assets/images/partner-4.png" /></a></div>
				<!--<div class="partner"><a href="/"><img src="/assets/images/partner-5.png" /></a></div>
				<div class="partner"><a href="/"><img src="/assets/images/partner-6.png" /></a></div>
				<div class="partner"><a href="/"><img src="/assets/images/partner-7.png" /></a></div>-->
			</div>
		</div>
		
		<h2>&nbsp;</h2>
		
		<a href="/contactgegevens_doorgeven" class="foot-liner">
			<span class="wht foot-liner-first">BRIEF ONTVANGEN?</span> <span class="foot-liner-second">GEEF HIER UW CONTACTGEGEVENS DOOR</span> <span class="black-dot black-dot-medium"></span>
		</a>
		
		<div class="footer_holder">
			<div class="footer">
				<div class="footer_left">
					
					<ul class="location">
						<li>DRS INFRA<br />
							Waaldijk 125<br />
							5327KP HURWENEN
						</li>
					</ul>
					
					<br />
					
					<ul class="phone">
						<li>
							<a href="tel:0418234444">+31 (0)418 – 23 44 44</a>
						</li>
					</ul>
					
					<br />
					
					<ul class="mail">
						<li>
							<a href="mailto:info@drs-infra.nl">INFO@DRS-INFRA.NL</a>
						</li>
					</ul>
					
					<br /><br />
					
				</div>
				<div class="footer_right">
					<a href="/">HOME</a><br />
					<a href="/diensten">DIENSTEN</a><br />
					<a href="/vraag_en_antwoord">VRAAG & ANTWOORD</a><br />
					<a href="/contactgegevens_doorgeven">DOORGEVEN CONTACTGEGEVENS</a><br />
					<a href="/privacy">PRIVACY STATEMENT</a><br />
					<a href="/contact">CONTACT</a><br />
				</div>
				<span class="clear"></span>
			</div>
		</div>
		
	</body>
</html>
