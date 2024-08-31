<?php


class Guard {
	var $guard = array('guest', true, false);
	var $url;
	protected $login_url = '/'.LOGIN_URL;
	protected $protected_url = '/'.PROTECTED_URL;
	public function __construct() {
		$this->url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	function add($type = false) {
		$session = session('', true);

		if (empty($session) 
			&& (
				(is_bool($type) && $type == true) ||
				(is_string($type) && $type !== 'guest'))
		) {
			return redirect($this->login_url);
		}
		else if (!empty($session) && $type === 'guest') {
			return redirect($this->protected_url);
		}
		else if (
			!empty($session) &&
			is_string($type) &&
			((is_object($session) && empty($session->$type))
			|| (is_array($session) && empty($session[$type])))
			// && str_replace(array('/'), '', $_SERVER['PATH_INFO']) !== $this->redirect
			) {
			return redirect($this->protected_url);
		}
	}
}
