<?php


class Guard {
	var $guard = array('guest', true, false);
	var $url;
	protected $protected_redirect;
	protected $login_redirect;
	public function __construct($config) {
		$this->url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$this->protected_redirect = $config['guard']['AUTH_REDIRECT'];
		$this->login_redirect = $config['guard']['LOGIN_URL'];
	}

	function add($type = false) {
		$session = session();
		if (empty(session()) && is_bool($type) && $type == true) {
			return redirect($this->protected_redirect);
		}
		else if (!empty(session()) && $type === 'guest') {
			return redirect($this->protected_redirect);
		}
		else if (
			is_string($type) &&
			((is_object($session) && empty($session->$type))
			|| (is_array($session) && empty($session[$type])))
			&& str_replace(array('/'), '', $_SERVER['PATH_INFO']) !== $this->protected_redirect
			) {
			return redirect($this->protected_redirect);
		}
	}

	function redirect($dir = '/') {
		echo $dir; die();
		$this->redirect = $dir;
	}
}
