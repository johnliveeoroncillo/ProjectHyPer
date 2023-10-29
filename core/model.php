<?php
class Model {
	protected $db;
	protected $table;
	protected $columns = [];

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