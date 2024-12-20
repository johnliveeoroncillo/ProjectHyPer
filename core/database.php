<?php
include('repository.php');
include(__DIR__ . '/../config/database.php');

class Database {
	var $db;
	var $error;
	protected $config;

	public function __construct($config) {
		$this->config = $config;
	}

	function parse_table($table) {
		return $this->config['DB_PREFIX'].$table;
	}

	function connect() {
		try {
			$hostname = $this->config['DB_HOST'];
			$dbname = $this->config['DB_NAME'];
			$username = $this->config['DB_USERNAME'];
			$password = $this->config['DB_PASSWORD'];
			$options = array(PDO::ATTR_PERSISTENT => true);
			$db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password, $options);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->db = $db;
		} catch (Exception $e) {
			http_response_code(500);
			throw new Error($e->getMessage());
		}
	}

	function getAllTables() {
		$query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA = '".$this->config['DB_NAME']."' AND TABLE_NAME like '".$this->config['DB_PREFIX']."%'");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	function instantiate() {
		$query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA = '".$this->config['DB_NAME']."' AND TABLE_NAME like '".$this->config['DB_PREFIX']."%'");
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		if (!empty($results)) {
			foreach ($results as $row) {
				$table_name = str_replace($this->config['DB_PREFIX'], '', $row['TABLE_NAME']);
				$this->addMethod($table_name, new Repository($table_name, $this->config, $this));
			}
		}
	}

	private function addMethod($name, $method) {
		$this->{$name} = $method;
	}
}
