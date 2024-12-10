<?php

function post($name = '', $fallback = '') {
	if(empty($name) && !empty($_POST)) return $_POST;

	return (isset($_POST[$name]) ? $_POST[$name] : $fallback);
}

function get($name = '', $fallback = '') {
	if(empty($name) && !empty($_GET)) return $_GET;

	return (!empty($_GET[$name]) ? $_GET[$name] : $fallback);
}

function redirect($url = '') {
	if($url === '/') return header('location: '.BASE_URL);
	else if(!empty($url)) return header('location: '.BASE_URL.$url);
	else if(!empty($_SERVER['HTTP_REFERER'])) return header('location: '.$_SERVER['HTTP_REFERER']);
}

function save_session($data) {
	if(is_array($data) || is_object($data)) $_SESSION['user_session'] = $data;
	else $_SESSION['user_session'][$data] = $data;
}

function session($key = '', $all = false) {
	if (empty($key) && !empty($_SESSION['user_session']) && !$all) {
		$role = array_keys((array)$_SESSION['user_session']);
		$key = $role[0];
	}

	if(empty($key)) return (isset($_SESSION['user_session']) ? $_SESSION['user_session'] : '');
	else return (isset($_SESSION['user_session'][$key]) ? $_SESSION['user_session'][$key] : '');
}

function clear_session() {
	session_destroy();
}

function save_form($data) {
	if(is_array($data)) $_SESSION['form_data'] = $data;
	else $_SESSION['form_data'][$data] = post($data);
}

function clear_form() {
	if(!empty($_SESSION['form_data'])) unset($_SESSION['form_data']);
}

function form($key = '') {
	if (empty($key)) {
		return $_SESSION['form_data'];
	}

	if(!empty($_SESSION['form_data'][$key])) {
		$value = $_SESSION['form_data'][$key];
		unset($_SESSION['form_data'][$key]);
		return $value;
	}
	return '';
}

function uuid() {
	$data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function set_flash_message($message = '', $error = true) {
	$class = $error ? 'danger' : 'success';
	$_SESSION['message'] = "<div class='".$class." px-4 py-3 rounded-lg text-sm text-white mb-2 font-semibold'>".$message."</div>";
}

function flash_message() {
	if(!empty($_SESSION['message'])) {
		echo $_SESSION['message'];
		unset($_SESSION['message']);
	}
}

function add_global($key, $value) {
	$GLOBALS[$key] = $value;
}

function globals() {
	return (empty($GLOBALS) ? array() : $GLOBALS);
}

function validate_contact($contact_number = '') {
	$length = strlen($contact_number);
	if($length !== 11) return 'Contact number must be 11 characters only';
	else if(substr($contact_number, 0, 2) != '09') return 'Invalid contact number format';
	else return true;
}

function random() {
	return str_replace('.', '', microtime(true));
}

function get_primary($images) {
	$image = $images;
	try {
		$image = json_decode($image, true);
		if (empty($image)) {
			throw new Exception("No image");
		}
		$image = $image[0];
	} catch (Exception $e) {
		$image = '';
	}

	return $image;
}

function parse($str) {
	try {
		return json_decode($str);
	} catch (Exception $e){
		return [];
	}
}

function get_status($status) {
	$class = 'danger';
	$success = array('COMPLETED', 'ACTIVE');
	$secondary = array('VERIFIED');
	$warning = array('PENDING');
	if (in_array($status, $success)) $class = 'success';
	else if (in_array($status, $warning)) $class = 'warning';
	else if (in_array($status, $secondary)) $class = 'info';

	echo '<span class="font-semibold text-white '.$class.' rounded-lg px-2">
		'.$status.'
	</span>';
}

function pascalCase($input) {
	$pascalCaseString = preg_replace_callback(
        '/\b\w/',
        function($matches) {
            return strtoupper($matches[0]);
        },
        $input
    );
    return $pascalCaseString;
}

function json($input, $decode = false) {
	if ($decode) return json_decode($input);
	return json_encode($input, JSON_PRETTY_PRINT);
}

function cloneObject($payload = array()) {
	return json_decode(json_encode((array) $payload));
}

function omit($payload = array(), $remove = array()) {
	$remove = !is_array($remove) ? [$remove] : $remove;
	$_payload = cloneObject($payload);
	foreach($remove as $value) {
		unset($_payload[$value]);
	}

	return $_payload;
}
?>
