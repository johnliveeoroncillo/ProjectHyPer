<?php
class Model {
	private $db;
	private $table;
	private $columns = [];

	public function __construct($table) {
		global $db;
		$this->table = $table;
		$this->db = $db;
	}

	function instantiate() {
		$this->initializeColumns();

		foreach($this->columns as $column){
			$this->columns[] = $column;
		} 
		return $this;
	}

	function __call($name, $arguments) {
		return call_user_func($this->{$name}, $this->config[$name][$arguments]);
	}

	function __set($name, $arguments) {
		if (!in_array($name, $this->columns)) {
			throw new Error('Column not found: '.$name);
		}

		$this->{$name} = $arguments;
	}

	protected function initializeColumns() {
		$query = $this->db->query("DESC {$this->table}");
	    $results = $query->fetchAll(PDO::FETCH_ASSOC);
		$this->columns = array_map(function($attr) {
			return $attr['Field'];
		}, $results);
	}
}