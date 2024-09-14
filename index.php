<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	session_start();
	date_default_timezone_set('Asia/Manila');
	include(__DIR__ . '/core/env.php');
	$env = new Env();
	$env->init();

	include(__DIR__ . '/config/constant.php');
	include(__DIR__ . '/core/render.php');
	include(__DIR__ . '/core/database.php');
	include(__DIR__ . '/core/utils.php');
	include(__DIR__ . '/core/guard.php');
	include(__DIR__ .'/services/cloudinary.php');
	include(__DIR__ .'/core/upload.php');

	add_global('env', $env);

	$protocol = (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://');
	$requestUrl = strtok('//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '?');

	$requestString = substr($requestUrl, strlen(BASE_URL));
	define('uri_string', $requestString);

	//IMPORT CORE DEPENDENCIES
	$db = new Database();
	$guard = new Guard();

	$db->connect();
	$db->instantiate();

	//IMPORT GLOBALS
	add_global('db', $db);
	add_global('guard', $guard);

	$urlParams = explode('/', $requestString);
	$find_api = array_search('api', $urlParams);
	if($find_api !== false) {
		$php_file = end($urlParams);
		$explode = explode('.', $php_file);
		$ext = '';
		if(count($explode) <= 1) $ext = '.php';
		array_shift($urlParams);


		$params = implode('/', $urlParams);

		$current_method = (!empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET') === 'GET' ? '' : '_post';

		$api_url = $params.$current_method.$ext;
		if (strpos($api_url, 'api') === false) {
			$api_url = 'api/'.$api_url;
		}
		include($api_url);
		return;
	}
	$php_file = strtolower($requestString);
	$render = new Render();

	$render->load_page($php_file);
?>
