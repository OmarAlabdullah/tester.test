<?php
	date_default_timezone_set('Europe/Amsterdam');

	define('WEBROOT', 'https://' . $_SERVER['HTTP_HOST'] . '/');
	define('SELF', '/' . substr($_SERVER['REDIRECT_URL'], 1));
	define('LAYOUTS_FOLDER', 'layouts/');
	define('IMAGES_FOLDER', 'images/');
	define('CSS_ROOT', 'assets/css/');
	define('JS_ROOT', 'assets/js/');
	define('VIEWS_FOLDER', 'views/');
	define('CONTROLLERS_FOLDER', 'controllers/');

	$title_for_layout = preg_replace('%(http://|www\.)%i', '', $_SERVER['HTTP_HOST']);
	$controller['css'] = array();
	$css_for_layout = '';
	$controller['js'] = array();
	$js_for_layout = '';

	@include('app_controller.php');

	$controller['controller'] = (isset($controller['params'][0]) ? $controller['params'][0] : null);

	$controller_params = $controller['params'];
	$params_string = routes($controller_params);
	$set_vars = array();

	$rerouted = false;
	if(isset($controller['routes'][$params_string]))
	{
		$controller_params = routes($controller['routes'][$params_string]);
		$params_string = routes($controller_params);
		$rerouted = true;
	}

	if(!$rerouted)
	{
		foreach($controller['routes'] as $route => $reroute)
		{
			$is_it = false;
			$route_array = explode('/', $route);
			if(count($route_array) == count($controller_params))
			{
				$is_it = true;
				$i = 0;
				foreach($route_array as $route_condition)
				{
					if(substr($route_condition, 0, 1) == '$')
					{
						$set_vars[substr($route_condition, 1)] = $controller_params[$i];
					}else
					{
						if($route_condition != $controller_params[$i])
							$is_it = false;
					}
					$i++;
				}
			}
			if($is_it)
			{
				$params_string = $reroute;
				foreach($set_vars as $set_var => $set_value)
					$params_string = $params_string . '/' . $set_value;
				$controller_params = routes($params_string);
			}
		}
	}


	if(isset($controller['restricted']))
	if(function_exists('userLoggedIn') && count($controller['restricted']) > 0)
	{
		if(userLoggedIn() === false || userLoggedIn() === null)
		{
			$temp_params_string = null;
			foreach($controller['restricted'] as $restricted => $redirect)
			{
				if(($restricted == '/' || substr($params_string, 0, strlen($restricted)) == $restricted) && $restricted != '!')
				{
					$temp_params_string = $redirect;
				}
			}
			if(isset($controller['restricted']['!']))
			{
				if(!is_array($controller['restricted']['!']))
					$controller['restricted']['!'] = array(
						$controller['restricted']['!']
					);
				foreach($controller['restricted']['!'] as $retricted)
				{
					if((substr($params_string, 0, strlen($retricted)) == $retricted))
					{
						$temp_params_string = null;
					}
				}
			}
			if(!is_null($temp_params_string))
				$params_string = $temp_params_string;
			$controller_params = routes($params_string);
		}
	}


	$controller['render'] = $controller_params;
	$controller['controller'] = $controller_params[0];


	if(!isset($controller_params[0]))
		$controller_params[0] = '';
	if(!isset($controller_params[1]))
		$controller_params[1] = '';
	if(!isset($controller_params[2]))
		$controller_params[2] = '';


	if(is_file(VIEWS_FOLDER . $controller_params[0] . '/' . $controller_params[1] . '/' . $controller_params[2] . '.php'))
		$controller['view'] = $controller_params[0] . '/' . $controller_params[1] . '/' . $controller_params[2];
	elseif(is_file(VIEWS_FOLDER . $controller_params[0] . '/' . $controller_params[1] . '.php'))
		$controller['view'] = $controller_params[0] . '/' . $controller_params[1];
	elseif(is_file(VIEWS_FOLDER . $controller_params[0] . '.php'))
		$controller['view'] = $controller_params[0];


	foreach($set_vars as $set_var => $set_value)
	{
		eval('$' . $set_var . ' = \'' . $set_value . '\';');
	}


	@include(CONTROLLERS_FOLDER . $controller['controller'] . '_controller.php');


	if(is_file(CONTROLLERS_FOLDER . $controller_params[0] . '/' . $controller_params[1] . '_controller.php'))
	{
		@include(CONTROLLERS_FOLDER . $controller_params[0] . '/' . $controller_params[1] . '_controller.php');
		if(!empty($controller_params[2]) && !is_numeric($controller_params[2]))
		{
			$eval_params = '';
			$continue = true;
			for($i = 3; $continue; $i++)
			{
				if(!empty($controller_params[$i]))
					$eval_params .= '\'' . $controller_params[$i] . '\', ';
				else
					$continue = false;
			}
			$eval_params = substr($eval_params, 0, -2);
			eval("
			if(function_exists('" . $controller_params[2] . "'))
			{
				" . $controller_params[2] . "(" . $eval_params . ");
			}
			");
		}
	}


	if(!empty($controller_params[1]) && !is_numeric($controller_params[1]))
	{
		$eval_params = '';
		$continue = true;
		for($i = 2; $continue; $i++)
		{
			if(!empty($controller_params[$i]))
				$eval_params .= '\'' . $controller_params[$i] . '\', ';
			else
				$continue = false;
		}
		$eval_params = substr($eval_params, 0, -2);
		eval("
		if(function_exists('" . $controller_params[1] . "'))
		{
			" . $controller_params[1] . "(" . $eval_params . ");
		}
		");
	}


	if($controller['developer'] >= 2)
	{
		error_reporting(E_ALL);
		ini_set('error_reporting', E_ALL);
	}
	elseif($controller['developer'] >= 1)
		error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));
	else
	{
		ini_set('error_reporting', 0);
		error_reporting(0);
	}


	if(isset($stift_set_values))
		foreach($stift_set_values as $set_var => $set_value)
			$$set_var = $set_value;


	if(!empty($controller['view']))
	{
		ob_start();
		$view_url = VIEWS_FOLDER . $controller['view'] . '.php';
		if(!@include($view_url))
		{
			if(isset($content_for_layout_subsitude))
				print($content_for_layout_subsitude);
		}
		$content_for_layout = ob_get_contents();
		ob_end_clean();
	}else
		$content_for_layout = '';


	if(!empty($keywords_for_layout))
		$keywords_for_layout = '<meta name="keywords" content="' . $keywords_for_layout . '"/>';
	if(!empty($description_for_layout))
		$description_for_layout = '<meta name="description" content="' . $description_for_layout . '"/>';

	if(!is_null($controller['layout']) && !empty($controller['layout']))
	{
		foreach($controller['css'] as $css)
			if(is_file(CSS_ROOT . $css))
				$css_for_layout .= '<link href="' . WEBROOT . CSS_ROOT . $css . '" rel="stylesheet" type="text/css">';
		foreach($controller['js'] as $js)
			if(is_file(JS_ROOT . $js))
				$js_for_layout .= '<script src="' . WEBROOT . JS_ROOT . $js . '" type="text/javascript"></script>';

		if(!is_null($controller['sublayout']) && !empty($controller['sublayout']))
			if(is_file(LAYOUTS_FOLDER . $controller['sublayout'] . '.php'))
			{
				ob_start();
				if(@include(LAYOUTS_FOLDER . $controller['sublayout'] . '.php'))
					$content_for_layout = ob_get_contents();
				ob_end_clean();
			}

		if(!is_null($controller['layout']) && !empty($controller['layout']))
			if(!@include(LAYOUTS_FOLDER . $controller['layout'] . '.php'))
				error('Error 2.2: Layout \'' . $controller['layout'] . '\' not found');
	}else
		print($content_for_layout);

	unset($content_for_layout);
	unset($origional_controller);
	unset($view_url);
	unset($css);
	unset($js);
	unset($developer);
?>
