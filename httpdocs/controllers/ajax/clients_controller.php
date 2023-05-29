<?php
require('lib/classes/phpmailer/PHPMailer.php');
require('lib/classes/phpmailer/SMTP.php');
require('lib/classes/phpmailer/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function get_addresses()
{
	global $controller, $db;
	
	$return = array(
		'succes' => false,
		'addresss_count' => 0
	);
	
	if(post())
	{
		$return['post'] = $controller['post'];
		
		$zipcode = si($controller['post']['zipcode']);
		$homenumber = si($controller['post']['homenumber']);
		
		if(!empty($zipcode) && !empty($homenumber))
		{
			$return['succes'] = true;
			$return['zipcode'] = $zipcode;
			$return['homenumber'] = $homenumber;
		}
		
		$params = array(
			'clients' => array(
				'conditions' => array(
					'zipcode' => $zipcode,
					'homenumber' => $homenumber,
					'(project_list_id IN (SELECT id FROM project_lists WHERE `archived`="0000-00-00 00:00:00"))',
					'archived' => '0000-00-00 00:00:00'
				)
			)
		);
		$clients = $db->select($params);
		
		foreach($clients as $client)
		{
			if(!in_array($client['Client']['addition'], $return['additions']))
			{
				$return['additions'][] = $client['Client']['addition'];
				$return['addresses'][] = $client['Client']['street'] . ' ' . $client['Client']['homenumber'] . strtoupper($client['Client']['addition']) . '<br />' . $client['Client']['zipcode'] . ' ' . $client['Client']['city'];
				$return['addresss_count']++;
			}
		}
	}
	
	print(json_encode($return));
}

function check_address()
{
	global $controller, $db;
	
	$return = array(
		'succes' => false,
		'check' => false
	);
	
	if(post())
	{
		$zipcode = si($controller['post']['zipcode']);
		$homenumber = si($controller['post']['homenumber']);
		$addition = si($controller['post']['addition']);
		
		if(!empty($zipcode) && !empty($homenumber))
		{
			$return['succes'] = true;
			$return['zipcode'] = $zipcode;
			$return['homenumber'] = $homenumber;
			$return['addition'] = $addition;
		}
		
		$params = array(
			'clients' => array(
				'conditions' => array(
					'zipcode' => $zipcode,
					'homenumber' => $homenumber,
					'addition' => $addition,
					'(project_list_id IN (SELECT id FROM project_lists WHERE `archived`="0000-00-00 00:00:00"))',
					'archived' => '0000-00-00 00:00:00'
				)
			)
		);
		$clients = $db->select($params);
		
		foreach($clients as $client)
		{
			$return['check'] = true;
		}
	}
	
	print(json_encode($return));
}

function post_contact_details()
{
	global $controller, $db;
	
	$return = array(
		'succes' => false,
		'address_check' => false,
		'contact_check' => false,
		'error' => false,
		'saved_succes' => false
	);
	
	if(post())
	{
		$return['succes'] = true;
		
		$zipcode = si($controller['post']['zipcode']);
		$homenumber = si($controller['post']['homenumber']);
		$addition = si($controller['post']['addition']);
		
		if(!empty($zipcode) && !empty($homenumber))
		{
			$return['zipcode'] = $zipcode;
			$return['homenumber'] = $homenumber;
			$return['addition'] = $addition;
			
			$params = array(
				'clients' => array(
					'conditions' => array(
						'zipcode' => $zipcode,
						'homenumber' => $homenumber,
						'addition' => $addition,
						'(project_list_id IN (SELECT id FROM project_lists WHERE `archived`="0000-00-00 00:00:00"))',
						'archived' => '0000-00-00 00:00:00'
					)
				)
			);
			$clients = $db->select($params);
			
			foreach($clients as $client)
			{
				$return['address_check'] = true;
			}
			
			if($return['address_check'])
			{
				$email = si($controller['post']['email']);
				$phone = si($controller['post']['phone']);
				
				if(_is_phone($phone))
				{
					$return['contact_check'] = true;
					$return['saved_succes'] = true;
					
					foreach($clients as $client)
					{
						$client['Client']['email'] = $email;
						$client['Client']['phone'] = $phone;
						$client['Client']['phone2'] = $phone2;
						
						unset($client['Client']['city']);
						unset($client['Client']['street']);
						
						$update = $db->update($client);
						
						if(!$update)
							$return['saved_succes'] = false;
					}
					
					$contactdetails_post = array(
						'Contactdetails_post' => array(
							'ip' => $_SERVER['REMOTE_ADDR'],
							'zipcode' => $zipcode,
							'homenumber' => $homenumber,
							'addition' => $addition,
							'email' => $email,
							'phone' => $phone,
							'created' => date('Y-m-d H:i:s')
						)
					);
					$db->insert($contactdetails_post);
					
					$params = array(
						'mail_templates' => array(
							'conditions' => array(
								'type' => 'contact_details',
								'archived' => '0000-00-00 00:00:00'
							),
							'order' => '`default` DESC',
							'select' => 'first'
						)
					);
					$mail_template = $db->select($params);
					if($mail_template)
					{
						$return['mail_template'] = $mail_template;
						
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
							$mailer->AddAddress($client['Client']['email'], '');
							//$mailer->addAddress($client_contact['Client_contact']['email'], $client_contact['Client_contact']['name']);
							
							$mailer->isHTML(true);
							$mailer->Subject = $mail_template['Mail_template']['subject'];
							$mailer->msgHTML(nl2br($mail_template['Mail_template']['content']));
							
							$mailer->send();
							
							$mailer->ClearAllRecipients();
							$mailer->SmtpClose();
							
						}catch(phpmailerException $e)
						{
							
						}
						
					}
					
				}else
				{
					$return['error'] = 'Controleer uw contactgegevens';
					if(!_is_email($email) && !_is_phone($phone))
						$return['error'] = 'Controleer uw email adres en uw telefoonnummer';
					elseif(!_is_email($email))
						$return['error'] = 'Controleer uw email adres';
					elseif(!_is_phone($phone))
						$return['error'] = 'Controleer uw telefoonnummer';
				}
				
			}
		}
	}
	
	print(json_encode($return));
}

function _is_email($subject)
{
	return filter_var($subject, FILTER_VALIDATE_EMAIL);
}
function _is_phone($subject)
{
	return (strlen($subject) >= 5 && is_numeric($subject));
}

?>