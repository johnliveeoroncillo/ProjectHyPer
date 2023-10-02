<?php


class Guard {
	var $guard = array('guest', true, false);
	var $url;
	protected $redirect = '/';
	public function __construct() {
		$this->url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	function add($type = false) {
		$session = session();
		if(empty(session()) && is_bool($type) && $type == true) {
			return redirect($this->redirect);
		}
		else if(!empty(session()) && $type === 'guest') {
			return redirect($this->redirect);
		}
		else if(empty($session['admin']) && $type === 'admin') {
			return redirect($this->redirect);
		}
	}

	function redirect($dir = '/') {
		$this->redirect = $dir;
	}
}
