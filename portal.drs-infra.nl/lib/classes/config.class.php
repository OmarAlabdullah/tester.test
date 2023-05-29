<?php
	
	class Config
	{
		
		function &getInstance()
		{
			static $instance = array();
			if (!$instance) {
				$instance[0] =& new Config();
			}
			return $instance[0];
		}
		
		function file($file = '')
		{
			@include('config/' . $file . '.php');
		}
		
		function write($key, $value)
		{
			$_this =& Config::getInstance();
			
			$parts = explode('.', $key);
			
			if(count($parts) == 1)
			{
				$_this->{$parts[0]} = $value;
			}
			if(count($parts) == 2)
			{
				$_this->{$parts[0]}[$parts[1]] = $value;
			}
			if(count($parts) == 3)
			{
				$_this->{$parts[0]}[$parts[1]][$parts[2]] = $value;
			}
		}
		
		function read($key)
		{
			$_this =& Config::getInstance();
			
			$parts = explode('.', $key);
			
			if(count($parts) == 1)
			{
				return $_this->{$parts[0]};
			}
			if(count($parts) == 2)
			{
				return $_this->{$parts[0]}[$parts[1]];
			}
			if(count($parts) == 3)
			{
				return $_this->{$parts[0]}[$parts[1]][$parts[2]];
			}
		}
		
	}
	
?>
