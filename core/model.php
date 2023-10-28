<?php
global $db;
class Model {
	var $db;
	var $table;
	var $columns = [];

	public function __construct() {
		global $db;
		$this->db = $db;
	}

	function instantiate() {
		$this->initializeColumns();
		$object = new self;

		foreach($this->columns as $column){
			if($object->has_attribute($column['field'])) {
				$object->$column = $column['field'];
			}
		} 
		return $object;
	}

	function insert($payload) {
		if (isset($payload->id)) {
			unset($payload->id);
		}
		if (isset($payload->created_at)) {
			unset($payload->created_at);
		}

		$payload = $this->parsePayload($payload);

		$this->validateObject($payload, 'insert');

		$response = $this->db->insert($this->table, $payload);
		return $response;
	}

	function update($payload, $condition) {
		$where = array();
		if (is_numeric($condition)) {
			$where['id'] = $condition;
		} else {
			$where = $condition;
		}

		$payload = $this->parsePayload($payload);


		if (!empty($payload->id)) {
			unset($payload->id);
		}

		$this->validateObject($payload, 'update');
		$this->validateObject($where);


		$response = $this->db->update($this->table, $payload, $where);
		return $response;
	}

	function save($payload) {
		if (empty($payload->id)) {
			$response = $this->insert($payload);
		} else {
			$response = $this->update($payload, $payload->id);
		}
		return $response;
	}

	function findOne($condition) {
		$where = array();
		if (is_numeric($condition)) {
			$where['id'] = $condition;	
		}

		$response = $this->db->get_where_row($this->table, $where);
		return (object) $response;
	}

	function find($where = array()) {
		$response = $this->db->get_where($this->table, $where);
		return $response;
	}

	function delete($condition) {
		$where = array();
		if (is_numeric($condition)) {
			$where['id'] = $condition;
		}

		$response = $this->db->delete($this->table, $where);
		return $response;
	}

	function softDelete($condition) {
		$where = array();
		if (is_numeric($condition)) {
			$where['id'] = $condition;
		}

		$attr = $this->attributes();
		if (!empty($attr['deleted_at'])) {
			$this->db->update($this->table, array('deleted_at' => date('Y-m-d H:i:s')), $where);
		}
	}

	function query($sql, $exclude = false) {
		$response = $this->db->query($sql, $exclude);
		return $response;
	}

	private function parsePayload($payload) {
		if (!is_object($payload)) {
			$payload = (object) $payload;
		}

		$parse = new stdClass();
		foreach($this->attributes() as $key => $nullable) {
			if (!empty($payload->$key)) {
				$parse->$key = $payload->$key;
			}
		}
		return $parse;
	}

	private function validateObject($payload, $options = '') {
		if (empty($payload)) {
			throw new Error("Payload/condition is not defined");
		}

		if (empty($options)) {
			return;
		}

		$required = array_filter($this->columns, function($value) {
			return $value['required'] && $value['field'] !== 'id';
		});
		$mapped_required = array_map(function($value) {
			return $value['field'];
		}, $required);
		$invalid = [];

		if ($options = 'insert') {
			foreach($mapped_required as $require) {
				if (empty($payload->$require)) {
					$invalid[] = $require;
				}
			}
		} else if ($options === 'update') {
			$keys = array_keys($payload);
			foreach($keys as $key) {
				if (array_includes($mapped_required, $key) && empty($payload[$key])) {
					$invalid[] = $key;
				}
			}
		}

		if (!empty($invalid)) {
			throw new Exception("Values are required for " . json_encode($invalid));
		}
	}

	protected function initializeColumns() {
		$result = $this->db->query("DESC {$this->table}", true);

		$this->columns = array_map(function($attr) {
			return array('field' => $attr['Field'], 'required' => $attr['Null'] === 'NO');
		}, $result);
	}

	protected function has_attribute($attribute) {
		return array_key_exists($attribute, $this->attributes());
	}

	protected function attributes() { 
		$attributes = array();
		foreach($this->columns as $column) {
			$attributes[$column['field']] = $column['required'];
		}
		return $attributes;
	}
}