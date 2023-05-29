<?php
	
	$controller['developer'] = 1;
	$controller['revision'] = 62;
	$controller['layout'] = 'default';
	$controller['css'][] = 'default.css';
	$controller['js'][] = 'jquery-3.3.1.min.js';
	$controller['js'][] = 'jquery.animate-shadow-min.js';
	$controller['js'][] = 'scripts.js';
	
	$db = runClass('dbi');
	$db->name = 'testdb';
	$db->user = 'testdb';
	$db->password = 'testdb';
	
	$controller['routes'] = array(
		'/' => 'home'
	);
	
	$controller['webp'] = (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false);
	$controller['mobile'] = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	
	$title_for_layout = 'DRS Infra';
	$keywords_for_layout = 'DRS, leidingen, gasleiding';
	$description_for_layout = 'DRS Infra is gespecialiseerd in het inspecteren, controleren en vervangen van dienst- en aansluitleidingen van het gasnetwerk in Nederland. Dit doen wij in opdracht van Van Vulpen en energienetbeheerders Stedin en Enexis. Wij voeren deze onderhoudswerkzaamheden uit vanaf de hoofdleiding tot aan de meterkast van woningen en bedrijfspanden.
Heeft u een brief ontvangen waarin wij vragen om uw contactgegevens? Dan starten wij binnenkort met werkzaamheden bij u in de wijk. Volg onderstaande stappen om uw contactgegevens door te geven. Wij nemen vervolgens contact met u op om een afspraak in te plannen.';
	$meta_image = '';
	
	switch($controller['params'][0])
	{
		case 'contact':
			$title_for_layout = 'DRS Infra';
			$keywords_for_layout = 'DRS, leidingen, gasleiding';
			$description_for_layout = 'DRS &#9989; Leidingbouw';
			$meta_image = '';
		break;
	}

	function userLoggedIn()
	{
		return false;
	}
	
?>
