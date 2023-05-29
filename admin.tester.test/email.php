<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

exit();

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

	//$mailer->addAddress('murielgrootaers@hotmail.com', 'Muri�l');
	$mailer->addAddress('daanvanderzalm@gmail.com', 'Daan');

	$mailer->XMailer = ' ';
	$mailer->isHTML(true);
	$mailer->Subject = 'Hoi';
	$mailer->Body = 'Nog een test <b>mailtje<b>.';
	$mailer->send();
	$mailer->ClearAllRecipients();

	Print("MAIL HAS BEEN SENT SUCCESSFULLY");
}catch(Exception $e)
{
	print("EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo);
}

?>
