<?php
include('repository.php');

class Database {
	var $db;
	var $error;
	protected $query_string;
	protected $query_order;
	protected $query_limit;
	var $dbprefix;

	public function __construct() {
		$this->dbprefix = DB_PREFIX;
	}

	function parse_table($table) {
		return $this->dbprefix.$table;
	}

	function connect() {
		$hostname = DB_HOST;
		$dbname = DB_NAME;
		$username = DB_USERNAME;
		$password = DB_PASSWORD;

		$options = array(PDO::ATTR_PERSISTENT => true);
		// // if (!IS_DEVELOP) {
		// 	$options = array(
		// 		// PDO::MYSQL_ATTR_SSL_KEY    => getcwd() . '/certs/client-key.pem',
		// 		// PDO::MYSQL_ATTR_SSL_CERT=> getcwd() . '/certs/client-cert.pem',
		// 		// PDO::MYSQL_ATTR_SSL_CA    => getcwd() . '/certs/server-ca.pem',
		// 		PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
		// 	);
		// // }
		$db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password, $options);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db = $db;
	}

	function query($query = '', $exclude = false) {
		$data = array();
		$this->query_string = $query;

		try {
			if (!$exclude) {
				$extract = $this->extractTable($this->query_string);
				$sql = $extract['sql'];
			} else {
				$sql = $this->query_string;
			}
			$query = $this->db->query($sql." {$this->query_order} {$this->query_limit}");
	    	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	    	$data = $result;
	    }
	    catch (Exception $e) {
	    	$this->error = $e->getMessage();
			throw new Exception($e->getMessage());
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
			throw new PDOException($e->getMessage());
		}

	  return $data;
	}

	function insert($table, $insert_values = array()) {
		try {
			$columns = array();
			$values = array();
			$dummy = array();


			if(!empty($insert_values)) {
				foreach($insert_values as $key => $value) {
					$columns[] = "`{$key}`";
					$dummy[] = '?';
					$values[] = $value;
				}
			}

			$this->query_string = "INSERT INTO {$this->parse_table($table)} (".implode(',', $columns).") VALUES (".implode(',', $dummy).")";
			$sql = $this->db->prepare($this->query_string);
			$response = $sql->execute($values);
			
            return $response;
		}
		catch (Exception $e) {
			$this->error = $e->getMessage();
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
	    }
	}

	function getAllTables() {
		$results = $this->query("SELECT * FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA = '".DB_NAME."' AND TABLE_NAME like '".DB_PREFIX."%'", true);
		return $results;
	}

	function instantiate() {
		$results = $this->query("SELECT * FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA = '".DB_NAME."' AND TABLE_NAME like '".DB_PREFIX."%'", true);
		if (!empty($results)) {
			foreach ($results as $row) {
				$table_name = str_replace(DB_PREFIX, '', $row['TABLE_NAME']);
				$this->addMethod($table_name, new Repository($table_name, $this->db));
			}
		}
	}

	private function addMethod($name, $method) {
		$this->{$name} = $method;
	}

	public function __call($name, $arguments) {
		return call_user_func($this->{$name}, $arguments);
	}
	
	private function extractTable($sql) {
		$modifiedTableName = '';
		$modifiedSql = '';
		// Regular expressions to match different types of statements
		$selectPattern = "/\bFROM\s+`?([a-zA-Z_][a-zA-Z0-9_]*)`?\b/i";
		$updatePattern = "/\bUPDATE\s+`?([a-zA-Z_][a-zA-Z0-9_]*)`?\b/i";
		$deletePattern = "/\bDELETE\s+FROM\s+`?([a-zA-Z_][a-zA-Z0-9_]*)`?\b/i";
	
		if (preg_match($selectPattern, $sql, $matches)) {
			$originalTableName = $matches[1];
			$modifiedTableName = $this->parse_table($originalTableName);
			$modifiedSql = preg_replace($selectPattern, "FROM $modifiedTableName", $sql);
			$tableName = $modifiedTableName;
		} elseif (preg_match($updatePattern, $sql, $matches)) {
			$originalTableName = $matches[1];
			$modifiedTableName = $this->parse_table($originalTableName);
			$modifiedSql = preg_replace($updatePattern, "UPDATE $modifiedTableName", $sql);
			$tableName = $modifiedTableName;
		} elseif (preg_match($deletePattern, $sql, $matches)) {
			$originalTableName = $matches[1];
			$modifiedTableName = $this->parse_table($originalTableName);
			$modifiedSql = preg_replace($deletePattern, "DELETE FROM $modifiedTableName", $sql);
			$tableName = $modifiedTableName;
		}

		return array('table' => $modifiedTableName, 'sql' => $modifiedSql);
	}
}
