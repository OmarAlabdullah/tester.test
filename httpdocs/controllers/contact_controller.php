<?php

require('lib/classes/phpmailer/PHPMailer.php');
require('lib/classes/phpmailer/SMTP.php');
require('lib/classes/phpmailer/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


if(post())
{
	$name = _strip_input($controller['post']['name']);
	$street_homenumber = _strip_input($controller['post']['street_homenumber']);
	$city = _strip_input($controller['post']['city']);
	$email = _strip_input($controller['post']['email']);
	$phone = _strip_input($controller['post']['phone']);
	$remarks = _strip_input($controller['post']['remarks']);
	
	$potensial_spam = false;
	if(!empty(_strip_input($controller['post']['surname'])))
		$potensial_spam = true;
	
	$time_started = (int)_strip_input($controller['post']['session_id']);
	if($time_started == 0)
		$potensial_spam = true;
	
	if(time() - $time_started < 2)
		$potensial_spam = true;
	
	$mailer = new PHPMailer();
					
					try
					{
						$mailer->SMTPDebug = 0;
						$mailer->isSMTP();
						$mailer->Timeout = 10;
						
						$mailer->SMTPOptions =
						[
							'ssl' =>
							[
								'verify_peer' => false,
								'verify_peer_name' => false,
								'allow_self_signed' => true
							]
						];
						
						$mailer->Host = 'smtp.hostnet.nl';
				    $mailer->SMTPAuth = true;
				    $mailer->Username = 'planning@drs-infra.nl';
				    $mailer->Password = 'Planningdrsinfra2020!';
				    
				    //$mailer->Username = 'drsinframail@gmail.com';
				    //$mailer->Password = 'Drsinfra2020!!';
				    
				    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				    $mailer->Port = 587;
				    
						$mailer->setFrom('planning@drs-infra.nl', 'Planning');
						$mailer->AddAddress('planning@drs-infra.nl', 'Planning');
						//$mailer->AddAddress('daanvanderzalm@gmail.com', 'Daan');
						//$mailer->addAddress($client_contact['Client_contact']['email'], $client_contact['Client_contact']['name']);
						
						$mailer->isHTML(true);
						$mailer->Subject = ($potensial_spam ? '*** ' : '') . 'Contactverzoek via website';
						
						$body = 'Het volgende contactverzoek is via de website verstuurd:<br /><br/>';
						$body .= 'Naam: <b>' . $name . '</b><br />';
						$body .= 'Straat + Huisnummer: <b>' . $street_homenumber . '</b><br />';
						$body .= 'Plaats: <b>' . $city . '</b><br />';
						$body .= 'Emailadres: <b>' . $email . '</b><br />';
						$body .= 'Telefoonnummer: <b>' . $phone . '</b><br />';
						$body .= '<br />';
						$body .= '<b>Opmerkingen:</b><br /><br />';
						$body .= (strlen($remarks) > 0 ? nl2br($remarks) : '<i>(geen)</i>');
						$body .= '<br /><br />';
						
						$mailer->msgHTML($body);
						
						if(!$potensial_spam)
							$mailer->send();
						
						$mailer->ClearAllRecipients();
						$mailer->SmtpClose();
						
					}catch(phpmailerException $e)
					{
						
					}
	
}

function _strip_input($input = '')
{
	$input = str_replace('\'', '', $input);
	$input = str_replace('"', '', $input);
	$input = str_replace('`', '', $input);
	$input = str_replace('<', '', $input);
	$input = str_replace('>', '', $input);
	$input = str_replace('/', '', $input);
	//$input = str_replace('\\', '', $input);
	
	return $input;
}
?>