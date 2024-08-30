<?php
include('repository.php');

class Database {
	var $db;
	var $error;
	private $config = array();
	protected $query_string;
	protected $query_order;
	protected $query_limit;

	public function __construct($config = array()) {
		$this->config = $config;
	}

	function config() {
		return $this->config;
	}

	function parse_table($table) {
		return $this->config['prefix'].$table;
	}

	function connect() {
		$hostname = $this->config['host'];
		$dbname =  $this->config['db'];
		$username = $this->config['username'];
		$password = $this->config['password'];

		$options = array(PDO::ATTR_PERSISTENT => true);
		if (isset($this->config['ssl']) && $this->config['ssl'] === true) {
			$options = array(
				PDO::MYSQL_ATTR_SSL_KEY    => $this->config['ssl_config']['key'],
				PDO::MYSQL_ATTR_SSL_CERT=> $this->config['ssl_config']['cert'],
				PDO::MYSQL_ATTR_SSL_CA    => $this->config['ssl_config']['ca'],
				PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => $this->config['ssl_config']['verify_cert']
			);
		}
		$db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password, $options);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db = $db;
	}

	function instantiate() {
		$query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA = '".$this->config['db']."' AND TABLE_NAME like '".$this->config['prefix']."%'");
	    $results = $query->fetchAll(PDO::FETCH_ASSOC);
		if (!empty($results)) {
			foreach ($results as $row) {
				$table_name = str_replace($this->config['prefix'], '', $row['TABLE_NAME']);
				$this->addMethod($table_name, new Repository($table_name, $this->db, $this->config));
			}
		}
	}

	private function addMethod($name, $method) {
		$this->{$name} = $method;
	}

	public function __call($name, $arguments) {
		return call_user_func($this->{$name}, $arguments);
	}

	function getAllTables() {
		$query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA = '".$this->config['db']."' AND TABLE_NAME like '".$this->config['prefix']."%'");
	    $results = $query->fetchAll(PDO::FETCH_ASSOC);
		return $results;
	}
	

	// function query($query = '', $exclude = false) {
	// 	$data = array();
	// 	$this->query_string = $query;

	// 	try {
	// 		if (!$exclude) {
	// 			$extract = $this->extractTable($this->query_string);
	// 			$sql = $extract['sql'];
	// 		} else {
	// 			$sql = $this->query_string;
	// 		}
	// 		$query = $this->db->query($sql." {$this->query_order} {$this->query_limit}");
	//     	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	//     	$data = $result;
	//     }
	//     catch (Exception $e) {
	//     	$this->error = $e->getMessage();
	// 		throw new Exception($e->getMessage());
	// 	}
	// 	catch (PDOException $e) {
	// 		$this->error = $e->getMessage();
	// 		throw new PDOException($e->getMessage());
	// 	}

	//   return $data;
	// }

	// function insert($table, $insert_values = array()) {
	// 	try {
	// 		$columns = array();
	// 		$values = array();
	// 		$dummy = array();


	// 		if(!empty($insert_values)) {
	// 			foreach($insert_values as $key => $value) {
	// 				$columns[] = "`{$key}`";
	// 				$dummy[] = '?';
	// 				$values[] = $value;
	// 			}
	// 		}

	// 		$this->query_string = "INSERT INTO {$this->parse_table($table)} (".implode(',', $columns).") VALUES (".implode(',', $dummy).")";
	// 		$sql = $this->db->prepare($this->query_string);
	// 		$response = $sql->execute($values);
			
    //         return $response;
	// 	}
	// 	catch (Exception $e) {
	// 		$this->error = $e->getMessage();
	// 	}
	// 	catch (PDOException $e) {
	// 		$this->error = $e->getMessage();
	//     }
	// }

	
	// private function extractTable($sql) {
	// 	$modifiedTableName = '';
	// 	$modifiedSql = '';
	// 	// Regular expressions to match different types of statements
	// 	$selectPattern = "/\bFROM\s+`?([a-zA-Z_][a-zA-Z0-9_]*)`?\b/i";
	// 	$updatePattern = "/\bUPDATE\s+`?([a-zA-Z_][a-zA-Z0-9_]*)`?\b/i";
	// 	$deletePattern = "/\bDELETE\s+FROM\s+`?([a-zA-Z_][a-zA-Z0-9_]*)`?\b/i";
	
	// 	if (preg_match($selectPattern, $sql, $matches)) {
	// 		$originalTableName = $matches[1];
	// 		$modifiedTableName = $this->parse_table($originalTableName);
	// 		$modifiedSql = preg_replace($selectPattern, "FROM $modifiedTableName", $sql);
	// 		$tableName = $modifiedTableName;
	// 	} elseif (preg_match($updatePattern, $sql, $matches)) {
	// 		$originalTableName = $matches[1];
	// 		$modifiedTableName = $this->parse_table($originalTableName);
	// 		$modifiedSql = preg_replace($updatePattern, "UPDATE $modifiedTableName", $sql);
	// 		$tableName = $modifiedTableName;
	// 	} elseif (preg_match($deletePattern, $sql, $matches)) {
	// 		$originalTableName = $matches[1];
	// 		$modifiedTableName = $this->parse_table($originalTableName);
	// 		$modifiedSql = preg_replace($deletePattern, "DELETE FROM $modifiedTableName", $sql);
	// 		$tableName = $modifiedTableName;
	// 	}

	// 	return array('table' => $modifiedTableName, 'sql' => $modifiedSql);
	// }
}
