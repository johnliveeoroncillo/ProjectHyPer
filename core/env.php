<?php

class Env {
    protected $env_file = __DIR__ . '/../.env';
    public function __construct() {

    }

    private function isExisting() {
        return file_exists($this->env_file);
    }

    function init() {
        if ($this->isExisting()) {
            $data = file_get_contents($this->env_file);
            $env = htmlentities($data);
            $explode = explode(PHP_EOL, $env);
            foreach($explode as $value) {
                [$key, $val] = explode('=', $value);
                $this->add($key, str_replace(array('"', "'"), '', $val));
            }
        }
    }

    private function add($key, $value) {
        $_ENV[$key] = $value;
    }

    function get($key = '', $fallback = '') {
        return (empty($_ENV[$key]) ? $fallback : $_ENV[$key]);
    }
}
