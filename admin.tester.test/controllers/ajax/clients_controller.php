<?php

	require('lib/classes/phpmailer/PHPMailer.php');
	require('lib/classes/phpmailer/SMTP.php');
	require('lib/classes/phpmailer/Exception.php');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	function get_client()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
			'error' => false
		);

		$return['post'] = $controller['post'];

		$client_id = $controller['post']['client_id'];
		if($client_id > 0)
		{
			$client = $db->first('clients', $client_id);
			if($client['Client']['id'] > 0)
			{
				$return['succes'] = true;

				$client['Client']['send_letter_1_readable'] = date('d M Y', strtotime($client['Client']['send_letter_1']));
				$return['Client'] = $client['Client'];

				ob_start();

				?>
				<div class="client_window">
					<input type="hidden" class="client_id" name="client_id" value="<?=$client['Client']['id']?>" />
					<div class="block">
						<h4>Brieven</h4>
						Eerste brief verstuurd op: <?=(strtotime($client['Client']['send_letter_1']) > 0 ? date('d M Y', strtotime($client['Client']['send_letter_1'])) : '&nbsp;')?><br />
						Tweede brief verstuurd op: <?=(strtotime($client['Client']['send_letter_2']) > 0 ? date('d M Y', strtotime($client['Client']['send_letter_2'])) : '&nbsp;')?><br />
						Derde brief verstuurd op: <?=(strtotime($client['Client']['send_letter_3']) > 0 ? date('d M Y', strtotime($client['Client']['send_letter_3'])) : '&nbsp;')?><br />

						<br />
						<h4>Contactgegevens</h4>

						Emailadres: <?=(!empty($client['Client']['email']) ? $client['Client']['email'] : '&nbsp;')?><br />
						Telefoonnummer: <span class="phonenumber_preview"><?=(!empty($client['Client']['phone']) ? $client['Client']['phone'] : '&nbsp;')?></span><br />
						Telefoonnummer: <?=(!empty($client['Client']['phone2']) ? $client['Client']['phone2'] : '&nbsp;')?>
					</div>
					<!--
					<div class="block">
						<h4>Afspraak Schouwen</h4>
<?php							if($client['Client']['watch'] == 'no')
							{
						?>
						n.v.t.
<?php							}else
							{
						?>
						<?=(strtotime($client['Client']['watch_appointment']) > 0 ? date('d M Y', strtotime($client['Client']['watch_appointment'])) : '&nbsp;')?><br />
						<?=(strtotime($client['Client']['watch_appointment']) > 0 ? date('H:i', strtotime($client['Client']['watch_appointment'])) : '&nbsp;')?><br />
						<br />
						
						<select id="mail_template_id" <?=(strtotime($client['Client']['watch_appointment']) > 0 ? '' : 'disabled')?>>
	<?php
								$params = array(
									'mail_templates' => array(
										'conditions' => array(
											'archived' => '0000-00-00 00:00:00'
										),
										'order' => 'watch_default DESC'
									)
								);
								$mail_templates = $db->select($params);

								foreach($mail_templates as $mail_template)
								{
							?>
							<option value="<?=$mail_template['Mail_template']['if']?>"><?=$mail_template['Mail_template']['name']?></option>
							<?php								}
							?>
						</select>
						<br /><br />
						<a href="/" class="btn btn-accept <?=(strtotime($client['Client']['watch_appointment']) > 0 ? '' : 'disabled')?>"><span class="fas fa-chevron-circle-right"></span>Verstuur bevestigingsmail</a>
						<br />
						Mail verstuurd op: <?=(strtotime($client['Client']['watch_appointment_mail']) > 0 ? date('d M Y', strtotime($client['Client']['watch_appointment_mail'])) : '&nbsp;')?>
						<?php							}
						?>
					</div>
					-->
					<div class="block">
						<h4>Afspraak</h4>
						<?=(strtotime($client['Client']['appointment']) > 0 ? date('d M Y', strtotime($client['Client']['appointment'])) : '&nbsp;')?>
						<!--<?=(strtotime($client['Client']['appointment']) > 0 ? date('H:i', strtotime($client['Client']['appointment'])) : '&nbsp;')?><br />-->

						<?php
							$timeframe = $db->first('timeframes', $client['Client']['timeframe_id']);
							if(strtotime($client['Client']['appointment']) > 0 && $timeframe)
							{
						?>
						&nbsp;&nbsp;&nbsp;<?=$timeframe['Timeframe']['timeframe']?>
						<?php
							}
						?>

						<br />

						<a href="/calendar/overview?client_id=<?=$client['Client']['id']?>" class="btn btn-accept"><span class="fas fa-play"></span><?=(strtotime($client['Client']['appointment']) > 0 ? 'Wijzig' : 'Maak')?> afspraak</a>
						<br /><br />

						<select id="mail_template_id" <?=(strtotime($client['Client']['appointment']) > 0 ? '' : 'disabled')?>>
							<?php
								$params = array(
									'mail_templates' => array(
										'conditions' => array(
											'archived' => '0000-00-00 00:00:00',
											'(`type` = "appointment" OR `type` = "cancel_appointment")'
										),
										'order' => '`type` ASC, `default` DESC'
									)
								);
								$mail_templates = $db->select($params);

								foreach($mail_templates as $mail_template)
								{
							?>
							<option value="<?=$mail_template['Mail_template']['id']?>"><?=$mail_template['Mail_template']['name']?></option>
							<?php								}
							?>
						</select>
						<br /><br />
						<a href="/" class="btn btn-accept <?=(strtotime($client['Client']['appointment']) > 0 ? '' : 'disabled')?>"><span class="fas fa-chevron-circle-right"></span>Verstuur bevestigingsmail</a>
						<br />
						Mail verstuurd op: <?=(strtotime($client['Client']['appointment_mail']) > 0 ? date('d M Y', strtotime($client['Client']['appointment_mail'])) : '&nbsp;')?>

					</div>
					<div class="block">
						<h4>Bijzonderheden</h4>
						<textarea class="client_remarks"><?=$client['Client']['remarks']?></textarea><br />
						<b>Peko: </b><?=$client['Client']['peko']?><br />
						<b>Zadel: </b><?=$client['Client']['zadel']?>
					</div>
					<div class="block">
						<h4>Interne opmerkingen</h4>
						<textarea class="client_internal_remarks"><?=$client['Client']['internal_remarks']?></textarea><br />
						<br />
						<a style="line-height: 20px; " href="/clients/details/<?=$client['Client']['id']?>" class="btn btn-accept"><span class="fas fa-chevron-circle-right"></span>Naar detailpagina</a>
					</div>
				</div>

				<?php
				$return['content'] = ob_get_clean();
			}
		}

		print(json_encode($return));
	}

	function save_data()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
			'error' => false
		);

		$client_id = $controller['post']['client_id'];

		$client = $db->first('clients', $client_id);
		if($client)
		{
			$return['succes'] = true;

			$remarks = $controller['post']['client_remarks'];
			$remarks = str_replace('\'', '', $remarks);
			$remarks = str_replace('"', '', $remarks);
			$remarks = str_replace('\\', '', $remarks);
			$client['Client']['remarks'] = $remarks;

			$internal_remarks = $controller['post']['client_internal_remarks'];
			$internal_remarks = str_replace('\'', '', $internal_remarks);
			$internal_remarks = str_replace('"', '', $internal_remarks);
			$internal_remarks = str_replace('\\', '', $internal_remarks);
			$client['Client']['internal_remarks'] = $internal_remarks;

			$return['remarks'] = $remarks;
			$return['internal_remarks'] = $internal_remarks;

			$db->update($client);
		}

		print(json_encode($return));
	}

	function export()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
			'error' => false
		);

		$client_ids = $controller['post']['client_ids'];

		$return['rows'][0] = '"Straat",';
		$return['rows'][0] .= '"Huisnummer",';
		$return['rows'][0] .= '"Toevoeging",';
		$return['rows'][0] .= '"Postcode",';
		$return['rows'][0] .= '"Plaatsnaam"';

		$i = 1;
		foreach($client_ids as $client_id)
		{
			$client = $db->first('clients', $client_id);
			if($client)
			{
				$return['succes'] = true;

				$return['rows'][$i] = '"' . $client['Client']['street'] . '",';
				$return['rows'][$i] .= '"' . $client['Client']['homenumber'] . '",';
				$return['rows'][$i] .= '"' . $client['Client']['addition'] . '",';
				$return['rows'][$i] .= '"' . $client['Client']['zipcode'] . '",';
				$return['rows'][$i] .= '"' . $client['Client']['city'] . '"';

				$i++;
			}
		}

		print(json_encode($return));
	}

	function remove()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
			'error' => false
		);

		$client_ids = $controller['post']['client_ids'];

		foreach($client_ids as $client_id)
		{
			$client = $db->first('clients', $client_id);
			if($client)
			{
				$return['succes'] = true;

				$client['Client']['archived'] = date('Y-m-d H:i:s');
				$db->update($client);
			}
		}

		print(json_encode($return));
	}

	function replace()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
			'error' => false
		);

		$client_ids = $controller['post']['client_ids'];
		if($controller['post']['project_list_id'] > 0)
		{
			foreach($client_ids as $client_id)
			{
				$client = $db->first('clients', $client_id);
				if($client)
				{
					$return['succes'] = true;

					$client['Client']['project_list_id'] = $controller['post']['project_list_id'];
					$db->update($client);
				}
			}
		}

		print(json_encode($return));
	}

	function set_send_letter()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
			'error' => false
		);

		$client_ids = $controller['post']['client_ids'];

		foreach($client_ids as $client_id)
		{
			$client = $db->first('clients', $client_id);
			if($client)
			{
				$return['succes'] = true;

				if($client['Client']['send_letter_1'] == '0000-00-00 00:00:00')
					$client['Client']['send_letter_1'] = date('Y-m-d H:i:s');
				elseif($client['Client']['send_letter_2'] == '0000-00-00 00:00:00')
					$client['Client']['send_letter_2'] = date('Y-m-d H:i:s');
				elseif($client['Client']['send_letter_3'] == '0000-00-00 00:00:00')
					$client['Client']['send_letter_3'] = date('Y-m-d H:i:s');
				else
					$return['error'] = 'Klant heeft al 3 brieven gehad';

				$db->update($client);
			}
		}

		print(json_encode($return));
	}

	function check_address()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
			'error' => false,
			'additions' => false,
			'addresses' => false
		);

		$zipcode = $controller['post']['zipcode'];
		$homenumber = $controller['post']['homenumber'];

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
				$return['addresses'][] = $client['Client']['street'] . ' ' . $client['Client']['homenumber'] . strtoupper($client['Client']['addition']) . ', ' . $client['Client']['zipcode'] . ' ' . $client['Client']['city'];
			}
		}

		print(json_encode($return));
	}

	function update_remarks()
	{
		global $controller, $db;

		$return = array(
			'succes' => false
		);

		$client_id = (int)$controller['post']['client_id'];
		if($client_id > 0)
		{
			$client = $db->first('clients', $client_id);
			if($client)
			{
				$client['Client']['remarks'] = $controller['post']['new_val'];

				$db->update($client);

				if(mysqli_errno($db->handle) === 0)
					$return['succes'] = true;
			}
		}

		print(json_encode($return));
	}

	function update_internal_remarks()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
			'type' => 'internal_remarks'
		);

		$client_id = (int)$controller['post']['client_id'];
		if($client_id > 0)
		{
			$client = $db->first('clients', $client_id);
			if($client)
			{
				$client['Client']['internal_remarks'] = $controller['post']['new_val'];

				$db->update($client);

				if(mysqli_errno($db->handle) === 0)
					$return['succes'] = true;
			}
		}

		print(json_encode($return));
	}

	function update_peko_zadel()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
		);

		$client_id = (int)$controller['post']['client_id'];
		$peko = $controller['post']['peko'];
		$zadel = $controller['post']['zadel'];

		if($client_id > 0)
		{
			$client = $db->first('clients', $client_id);
			if($client)
			{
				$client['Client']['peko'] = $peko;
				$client['Client']['zadel'] = $zadel;

				$return['client'] = $client;

				$db->update($client);

				if(mysqli_errno($db->handle) === 0)
					$return['succes'] = true;
			}
		}

		print(json_encode($return));
	}
	function update_remarks_internal_remarks()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
		);

		$client_id = (int)$controller['post']['client_id'];
		$remarks = $controller['post']['remarks'];
		$internal_remarks = $controller['post']['internal_remarks'];

		if($client_id > 0)
		{
			$client = $db->first('clients', $client_id);
			if($client)
			{
				$client['Client']['remarks'] = $remarks;
				$client['Client']['internal_remarks'] = $internal_remarks;

				$return['client'] = $client;

				$db->update($client);

				if(mysqli_errno($db->handle) === 0)
					$return['succes'] = true;
			}
		}

		print(json_encode($return));
	}
	function update_email_phone()
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
		);

		$client_id = (int)$controller['post']['client_id'];
		$email = $controller['post']['email'];
		$phone = $controller['post']['phone'];
		$phone2 = $controller['post']['phone2'];

		if($client_id > 0)
		{
			$client = $db->first('clients', $client_id);
			if($client)
			{
				$client['Client']['email'] = $email;
				$client['Client']['phone'] = $phone;
				$client['Client']['phone2'] = $phone2;

				$return['client'] = $client;

				$db->update($client);

				if(mysqli_errno($db->handle) === 0)
					$return['succes'] = true;
			}
		}

		print(json_encode($return));
	}

	function set_not_remediated($client_id = 0, $not_remediated = 0)
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
		);

		$client_id = (int)$client_id;
		$not_remediated = (int)$not_remediated;

		if($client_id > 0)
		{
			if($not_remediated != 1)
				$not_remediated = 0;

			$return['client_id'] = $client_id;
			$return['not_remediated'] = $not_remediated;

			$client = $db->first('clients', $client_id);
			if($client)
			{
				$client['Client']['not_remediated'] = $not_remediated;

				$return['client'] = $client;

				$db->update($client);

				if(mysqli_errno($db->handle) === 0)
					$return['succes'] = true;
			}
		}

		print(json_encode($return));
	}

	function get_client_info($client_id = 0)
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
		);

		$client_id = (int)$client_id;

		if($client_id > 0)
		{
			$client = $db->first('clients', $client_id);
			if($client)
			{
				$return['client'] = $client;
				$return['succes'] = true;

				$params = array(
					'mail_templates' => array(
						'conditions' => array(
							'archived' => '0000-00-00 00:00:00',
							'(`type` = "appointment")'
						),
						'order' => '`type` ASC, `default` DESC'
					)
				);
				$mail_templates = $db->select($params);

				$return['mail_templates'] = $mail_templates;
			}
		}

		print(json_encode($return));
	}

	function sent_appointment_mail($client_id = 0, $mail_template_id = 0)
	{
		global $controller, $db;

		$return = array(
			'succes' => false,
			'client_id' => $client_id,
			'mail_template_id' => $mail_template_id
		);

		$client_id = (int)$client_id;
		$mail_template_id = (int)$mail_template_id;

		if($client_id > 0 && $mail_template_id > 0)
		{
			$client = $db->first('clients', $client_id);
			if($client)
			{
				$return['client'] = $client;

				$client_appointment_time = strtotime($client['Client']['appointment']);
				if($client_appointment_time > 0)
				{
					if($client['Client']['timeframe_id'] > 0)
					{
						$timeframe = $db->first('timeframes', $client['Client']['timeframe_id']);

						if($timeframe)
						{
							$mail_template = $db->first('mail_templates', $mail_template_id);
							if($mail_template)
							{
								$return['body'] = $mail_template['Mail_template']['content'];
								$return['body'] = str_replace('[datum]', date('d-m-Y', $client_appointment_time), $return['body']);
								$return['body'] = str_replace('[tijd]', $timeframe['Timeframe']['email_text'], $return['body']);

								$return['subject'] = $mail_template['Mail_template']['subject'];


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
							    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
							    $mailer->Port = 587;
									$mailer->setFrom('planning@drs-infra.nl', 'Planning');
									$mailer->AddAddress($client['Client']['email'], '');
									$mailer->isHTML(true);
									$mailer->Subject = $return['subject'];
									$mailer->msgHTML(nl2br($return['body']));
									$mailer->send();
									$mailer->ClearAllRecipients();
									$mailer->SmtpClose();

								}catch(phpmailerException $e)
								{

								}

								$client['Client']['appointment_mail'] = date('Y-m-d H:i:s');
								$db->update($client);
								$return['succes'] = true;
							}
						}
					}
				}
			}
		}

		print(json_encode($return));
	}

	function remove_client($client_id = 0)
	{
		$return = array(
			'succes' => false,
			'client_id' => $client_id
		);

		$client_id = (int)$client_id;
		if($client_id > 0)
		{
			global $db;

			$client = $db->first('clients', $client_id);
			if($client)
			{
				$params = array(
					'documents' => array(
						'conditions' => array(
							'client_id' => $client['Client']['id']
						)
					)
				);
				$documents = $db->select($params);
				if($documents)
				{
					$return['documents'] = $documents;
					foreach($documents as $document)
					{
						$document['Document']['archived'] = date('Y-m-d H:i:s');
						$db->update($document);
					}
				}

				$params = array(
					'photos' => array(
						'conditions' => array(
							'client_id' => $client['Client']['id']
						)
					)
				);
				$photos = $db->select($params);
				if($photos)
				{
					$return['photos'] = $photos;
					foreach($photos as $photo)
					{
						$photo['Photo']['archived'] = date('Y-m-d H:i:s');
						$db->update($photo);
					}
				}

				$client['Client']['archived'] = date('Y-m-d H:i:s');
				$db->update($client);
				$return['succes'] = true;
			}
		}

		print(json_encode($return));
	}
	function remove_appointment($client_id = 0)
	{
		$return = array(
			'succes' => false,
			'client_id' => $client_id
		);

		$client_id = (int)$client_id;
		if($client_id > 0)
		{
			global $db;

			$client = $db->first('clients', $client_id);
			if($client)
			{
				$client['Client']['appointment'] = '0000-00-00';
				$client['Client']['appointment_mail'] = '0000-00-00 00:00:00';
				$return['client'] = $client;
				$db->update($client);
				$return['succes'] = true;
			}
		}

		print(json_encode($return));
	}
	function sent_cancel_mail($client_id = 0)
	{
		$return = array(
			'succes' => false,
			'client_id' => $client_id
		);

		$client_id = (int)$client_id;
		if($client_id > 0)
		{
			global $db;

			$client = $db->first('clients', $client_id);
			if($client)
			{
				if(!empty($client['Client']['email']))
				{
					$params = array(
						'mail_templates' => array(
							'conditions' => array(
								'type' => 'cancel_appointment',
								'archived' => '0000-00-00 00:00:00'
							),
							'order' => '`default` DESC',
							'select' => 'first'
						)
					);
					$mail_template = $db->select($params);

					if($mail_template)
					{
						$return['subject'] = $mail_template['Mail_template']['subject'];
						$return['body'] = $mail_template['Mail_template']['content'];

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
					    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
					    $mailer->Port = 587;
							$mailer->setFrom('planning@drs-infra.nl', 'Planning');
							$mailer->AddAddress($client['Client']['email'], '');
							$mailer->isHTML(true);
							$mailer->Subject = $return['subject'];
							$mailer->msgHTML(nl2br($return['body']));
							$mailer->send();
							$mailer->ClearAllRecipients();
							$mailer->SmtpClose();

						}catch(phpmailerException $e)
						{

						}
					}
				}
			}
		}

		print(json_encode($return));
	}

	function set_force_finished($client_id = 0, $setting = 0)
	{
		$return = array(
			'succes' => false,
			'client_id' => $client_id
		);

		$client_id = (int)$client_id;
		$setting = (int)$setting;

		if($client_id > 0)
		{
			global $db;

			$client = $db->first('clients', $client_id);
			if($client)
			{
				$client['Client']['force_finished'] = $setting;
				$db->update($client);
				$return['succes'] = true;
			}
		}

		print(json_encode($return));
	}
?>