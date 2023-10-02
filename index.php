<?php
	session_start();
	date_default_timezone_set('Asia/Manila');
	include('./config/constant.php');
	include('./core/render.php');
	include('./core/database.php');
	include('./core/utils.php');
	include('./core/guard.php');


	$protocol = (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://');
	$requestUrl = strtok('//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '?');

	$requestString = substr($requestUrl, strlen(BASE_URL));
	define('uri_string', $requestString);

	//IMPORT CORE DEPENDENCIES
	$db = new Database();
	$db->connect();

	$guard = new Guard();

	//IMPORT GLOBAL
	add_global('db', $db);
	add_global('guard', $guard);
	
	$urlParams = explode('/', $requestString);

	if(strtolower(current($urlParams)) === 'api') {
		$php_file = end($urlParams);
		$explode = explode('.', $php_file);
		$ext = '';
		if(count($explode) <= 1) $ext = '.php';
		array_shift($urlParams);

		$params = implode('/', $urlParams);
		$current_method = (!empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET') === 'GET' ? '' : '_post';

		include('api/'.$params.$current_method.$ext);
		return;
	}
	$php_file = strtolower($requestString);
	$render = new Render();
	$render->load_page($php_file);
?>
