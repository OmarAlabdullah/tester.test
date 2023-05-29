<?php

date_default_timezone_set('Europe/Amsterdam');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '/var/www/vhosts/franckeninfra.nl/admin.franckeninfra.nl/PHPMailer/Exception.php';
require '/var/www/vhosts/franckeninfra.nl/admin.franckeninfra.nl/PHPMailer/PHPMailer.php';
require '/var/www/vhosts/franckeninfra.nl/admin.franckeninfra.nl/PHPMailer/SMTP.php';

$mailer = new PHPMailer(false);
try
{
	$mailer->SMTPDebug = 2;
	$mailer->isSMTP();
	$mailer->Host = 'smtp.hostnet.nl';
	$mailer->SMTPAuth = true;
	$mailer->Username = 'planning@franckeninfra.nl';
	$mailer->Password = 'Planning2019a!';
	$mailer->SMTPSecure = 'STARTTLS';
	$mailer->Port = 587;
	$mailer->setFrom('planning@franckeninfra.nl', 'Planning');
	
	//$mailer->addAddress('murielgrootaers@hotmail.com', 'Muriël');
	$mailer->addAddress('daanvanderzalm@gmail.com', 'Daan');
	
	$mailer->XMailer = ' ';
	$mailer->isHTML(true);
	$mailer->Subject = 'Reminder van de dag';
	$mailer->Body = 'Het is nu <b>' . date('d-m-Y H:i:s') . '<b>.';
	$mailer->send();
	$mailer->ClearAllRecipients();
	
	Print("MAIL HAS BEEN SENT SUCCESSFULLY");
}catch(Exception $e)
{
	print("EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo);
}

?>