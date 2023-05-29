<?php
	function pr($array)
	{
		if(is_array($array))
		{
			print('<pre>');
			print_r($array);
			print('</pre>');
		}else
		{
			var_dump($array);
			print('<br />');
		}
	}

	function redirect($url)
	{
		if(!headers_sent())
		{
		   header('Location: '.$url); 
		   exit;
		}
		else
		{ 
		   print( '<script type="text/javascript">');
		   print('window.location.href="'.$url.'";');
		   print('</script>');
		   print('<noscript>');
		   print('<meta http-equiv="refresh" content="0;url='.$url.'" />');
		   print('</noscript>'); 
		   exit;
		}
	}
	
	function post($key = null, $value = null)
	{
		global $controller;
		
		if(is_null($key) && is_null($value))
			return (count($controller['post']) > 0);
		
		if(is_null($value))
			return $controller['post'][$key];
	}
	
	function session($key = null, $value = null)
	{
		global $controller;
		
		if(is_null($key))
			return false;
		if(is_null($value))
		{
			return $controller['session'][$key];
		}else
		{
			$_SESSION[$key] = $value;
			$controller['session'][$key] = $value;
			return $value;
		}
		return false;
	}
	
	function destroy_session($key = null)
	{
		global $controller;
		
		$session = $controller['session'][$key];
		
		if(is_null($key))
			return false;
		$_SESSION[$key] = '';
		unset($_SESSION[$key]);
		unset($controller['cookie'][$key]);
		
		return $session;
	}
	
	function cookie($key = null, $value = null, $time_end = null)
	{
		global $controller;
		
		if(!headers_sent())
		{
			$stift_array = json_decode($controller['cookie']['STIFT'], true);
			
			if(is_null($time_end))
				$time_end = (time() + (31 * 24 * 60 * 60));
			
			if(is_null($key))
				return false;
			if(is_null($value))
			{
				
				$return_value = $controller['cookie'][$key];
				
				$stift_array = json_decode($controller['cookie']['STIFT'], true);
				
				if($stift_array[$key] == 'int')
					$return_value = (int) $return_value;
				
				if($stift_array[$key] == 'bool')
					$return_value = ($return_value == '1');
				
				if($stift_array[$key] == 'array')
					$return_value = json_decode($return_value, true);
				
				//pr('get ' . $key);
				
				return $return_value;
			}else
			{
				destroy_cookie('STIFT');
				$datatype = 'String';
				if(is_int($value))
					$datatype = 'int';
				if(is_bool($value))
				{
					$datatype = 'bool';
					$value = ($value ? '1' : '0');
				}
				if(is_array($value))
				{
					$datatype = 'array';
					$value = json_encode($value);
				}
				$stift_array[$key] = $datatype;
				@setcookie('STIFT', json_encode($stift_array), $time_end, '/');
				$controller['cookie']['STIFT'] = json_encode($stift_array);
				
				destroy_cookie($key);
				@setcookie($key, $value, $time_end, '/');
				$controller['cookie'][$key] = $value;
				
				//pr('set ' . $key);
				
				return $value;
			}
		}
		return false;
	}
	
	function destroy_cookie($key = null)
	{
		global $controller;
		
		$cookie = $controller['cookie'][$key];
		
		if(is_null($key))
			return false;
		@setcookie($key, false, time(), '/');
		unset($controller['cookie'][$key]);
		
		return $cookie;
	}
	
	function routes($routes)
	{
		if(is_array($routes))
		{
			$params_string = '';
			foreach($routes as $param)
				$params_string .= $param . '/';
			if(strlen($params_string) > 0)
				$params_string = substr($params_string, 0, -1);
			else
				$params_string = '/';
			return $params_string;
		}elseif(is_string($routes))
		{
			return explode('/', $routes);
		}
	}
	
	function set($var, $value = null)
	{
		global $stift_set_values;
		$stift_set_values[$var] = $value;
		return ($stift_set_values[$var] == $value);
	}
	
	function runClass($className = false, $params = false, $param2 = false, $param3 = false)
	{
		require_once('lib/classes/' . $className . '.class.php');
		$params_string = '';
		if(is_array($params) && count($params) > 0)
		{
			foreach($params as $param)
				$params_string .= $param . ',';
			$params_string = substr($params_string, 0, -1);	
		}
		if(is_string($params))
			$params_string = $params;
		if(is_string($param2))
			$params_string .= ',' . $param2;
		if(is_string($param3))
			$params_string .= ',' . $param3;
		eval('$ret = new ' . $className . '(' . $params_string . ');');
		return $ret;
	}
	
	function si($subject)
	{
		$subject = str_replace('\'', '', $subject);
		$subject = str_replace('"', '', $subject);
		
		return $subject;
	}
?>
