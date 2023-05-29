<?php
	define('DOMAIN', preg_replace('%(http://|www\.)%i', '', $_SERVER['HTTP_HOST']));
	$parts = explode('.', DOMAIN);
	if(count($parts) > 1)
		$domain_alias = substr(DOMAIN, 0, strlen(DOMAIN) - strlen(end($parts)) - 1);
	else
		$domain_alias = DOMAIN;
	define('DOMAIN_ALIAS', $domain_alias);
	unset($parts);
	unset($domain_alias);
	define('DS', DIRECTORY_SEPARATOR);
	define('SELF', '/' . substr($_SERVER['REDIRECT_URL'], 1));
?>
