<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	session_start();
	include(__DIR__ . '/core/jl.php');
	$jl = new Jl();
	$jl->init();
	add_global('jl', $jl);
	

	// $protocol = (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://');
	// $requestUrl = strtok('//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '?');
	// $requestString = substr($requestUrl, strlen(BASE_URL));
	// define('uri_string', $requestString);

	// //IMPORT CORE DEPENDENCIES
	// $db = new Database();
	// $guard = new Guard();

	// $db->connect();
	// $db->instantiate();

	// //IMPORT GLOBALS
	// add_global('db', $db);
	// add_global('guard', $guard);

	// include_once(__DIR__ . '/core/initialize.php');
	
	// $urlParams = explode('/', $requestString);
	// if(strtolower(current($urlParams)) === 'api') {
	// 	$php_file = end($urlParams);
	// 	$explode = explode('.', $php_file);
	// 	$ext = '';
	// 	if(count($explode) <= 1) $ext = '.php';
	// 	array_shift($urlParams);

	// 	$params = implode('/', $urlParams);
	// 	$current_method = (!empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET') === 'GET' ? '' : '_post';
	// 	include('api/'.$params.$current_method.$ext);
	// 	return;
	// }
	// $php_file = strtolower($requestString);
	// $render = new Render();

	// $render->load_page($php_file);
?>
