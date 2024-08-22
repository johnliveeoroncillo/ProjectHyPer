<?php


class Guard {
	var $guard = array('guest', true, false);
	var $url;
	protected $redirect = REDIRECT;
	public function __construct() {
		$this->url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	function add($type = false) {
		$session = session();
		if (empty(session()) && is_bool($type) && $type == true) {
			return redirect($this->redirect);
		}
		else if (!empty(session()) && $type === 'guest') {
			return redirect($this->redirect);
		}
		else if (
			is_string($type) &&
			((is_object($session) && empty($session->$type))
			|| (is_array($session) && empty($session[$type])))
			&& str_replace(array('/'), '', $_SERVER['PATH_INFO']) !== $this->redirect
			) {
			return redirect($this->redirect);
		}
	}

	function redirect($dir = '/') {
		$this->redirect = $dir;
	}
}
