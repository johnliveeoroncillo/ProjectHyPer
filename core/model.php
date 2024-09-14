<?php
class Model {
	protected $repository;
	protected $table;
	protected $columns = [];

	public function __construct($repository) {
		$this->repository = $repository;
		$this->instantiate(); 
	}

	function instantiate() {
		$this->initializeColumns();
		foreach($this->columns as $column){
			$this->{$column} = '';
		} 
	}

	protected function initializeColumns() {
		$result = $this->repository->query("DESC {$this->repository->parse_table($this->table)}", true, true);
		$result = array_filter($result, function($value) {
			return !in_array($value->Field, ['created_at', 'deleted_at', 'updated_at']);
		});
		$this->columns = array_map(function($attr) {
			return $attr->Field;
		}, $result);
	}

	public function __set($name, $value) {
		if (!in_array($name, $this->columns)) {
			throw new Error("Column {$name} is not existing");
		}
		$this->{$name} = $value;
	}
}
