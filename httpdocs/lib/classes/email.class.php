<?php
	
	class email
	{
		
		var $subject = 'no subject';
		var $layout = null;
		var $view = null;
		var $from = '';
		var $replyTo = '';
		var $returnPath = '';
		var $params = array();
		
		function email()
		{
			
		}
		
		function send($params)
		{
			if(!is_array($params))
				$params = array($params);
			
			$succes = true;
			
			if(empty($this->returnPath))
				$this->returnPath = $this->replyTo;
			
			foreach($params as $adres)
			{
				$body = $this->_build_email();
				
				$headers  = "From: " . $this->from . "\r\n";
		    $headers .= "Reply-To: " . $this->replyTo . "\r\n";
		    $headers .= "Return-Path: " . $this->returnPath . "\r\n";
		    $headers .= 'MIME-Version: 1.0' . "\n";
		    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	    	$headers .= 'X-Mailer: PHP/' . phpversion();
		    
				if(!@mail($adres, $this->subject, $body, $headers))
					$succes = false;
			}
			return $succes;
		}
		
		function _build_email()
		{
			
			foreach($this->params as $var => $value)
			{
				$$var = $value;
			}
			
			ob_start();
			$view_url = VIEWS_FOLDER . 'email/' . $this->view . '.php';
			@include($view_url);
			$content_for_layout = ob_get_contents();
			ob_end_clean();
			
			ob_start();
			$layout_url = LAYOUTS_FOLDER . $this->layout . '.php';
			@include($layout_url);
			$content_for_email = ob_get_contents();
			ob_end_clean();
			
			return $content_for_email;
		}
		
	}
	
?>
