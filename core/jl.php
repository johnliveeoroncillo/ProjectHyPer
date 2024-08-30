<?php
// CONFIG \\
include(__DIR__ . '/../config/config.php'); // $config
include(__DIR__ . '/../config/database.php'); // $database
include(__DIR__ . '/../config/email.php'); // $smtp
include(__DIR__ . '/../config/guard.php'); // $guard

// CORE FUNCTIONS \\
include(__DIR__ . '/../core/database.php');
include(__DIR__ . '/../core/model.php');
include(__DIR__ . '/../core/utils.php');
include(__DIR__ . '/../core/guard.php');
include(__DIR__ . '/../core/render.php');

include(__DIR__ . '/../services/cloudinary.php');
include(__DIR__ . '/../core/upload.php');

class Jl {
    var $config = array();

    function __construct() {
        // CONFIG
        global $config;
        global $database;
        global $email;
        global $guard;

        $this->config = array(
            'config' => $config,
            'database' => $database,
            'email' => $email,
            'guard' => $guard,
            'base_url' => '//'.$_SERVER['HTTP_HOST'].$config['FOLDER'],
            'is_develop' => strpos($_SERVER['HTTP_HOST'], 'local') !== false,
            'current_url' => $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
            'home_file' => 'index',
            'root' => $_SERVER['DOCUMENT_ROOT'].(empty($config['FOLDER']) ? '' : '/'.$config['FOLDER']),
        );
	}

    function init() {
        // SET TIMEZONE ||
        if (!empty($this->general_config['config']['TIMEZONE'])) {
            date_default_timezone_set($this->config['config']['TIMEZONE']);
        }

        // DATABASE \\
        $this->database = new Database($this->config['database']['default']);
        $database_config = $this->database->config();
        $this->database->connect();
	    $this->database->instantiate();
        $tables = $this->database->getAllTables();
        // MODEL \\
        add_global('db', $this->database->db);
        foreach ($tables as $table) {
            $table_name = str_replace($database_config['prefix'], '', $table['TABLE_NAME']);
            $classString = 'class '.pascalCase($table_name).' extends Model {
                function __construct() {
                    parent::__construct("'.$table_name.'");
                    $this->instantiate();
                }
            }';
            eval($classString);
        }

        $protocol = (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://');
        $requestUrl = strtok('//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '?');
        $requestString = substr($requestUrl, strlen( '//'.$_SERVER['HTTP_HOST'].'/'.$this->config['config']['FOLDER']));
        // define('uri_string', $requestString);

        $guard = new Guard($this->config);
        $this->guard = $guard;

        $this->upload = new Upload($this->config);

        $urlParams = explode('/', $requestString);
        if(strtolower(current($urlParams)) === 'api') {
        	$php_file = end($urlParams);
        	$explode = explode('.', $php_file);
        	$ext = '';
        	if(count($explode) <= 1) $ext = '.php';
        	array_shift($urlParams);

        	$params = implode('/', $urlParams);
        	$current_method = (!empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET') === 'GET' ? '' : '_post';
        	include(__DIR__ .'/../api/'.$params.$current_method.$ext);
        	return;
        }
        $php_file = strtolower($requestString);
        $render = new Render();
        $render->load_page($php_file);
    }

    // private function load($type = '') {
    //     $this->addMethod($type, new Database());
    // }

    // private function addMethod($name, $method) {
	// 	$this->{$name} = $method;
	// }

	// public function __call($name, $arguments) {
    //     if (is_array($arguments)) {
    //         $arguments = array_shift($arguments);
    //     }
	// 	return call_user_func($this->{$name}, $this->config[$name][$arguments]);
	// }
}