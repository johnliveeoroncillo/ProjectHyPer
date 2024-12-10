<?php
include 'model.php';

class Repository {
    var $error;
    var $table;
    private $db;
	private $config;
	private $context;

	protected $query_string;
	protected $query_limit;
	protected $query_order;
	protected $query_group;
	protected $query_having;
	protected $query_offset;

	// protected $add_where;

    public function __construct($table, $config, $context) {
		$this->config = $config;
		$this->context = $context;
        $this->db = $context->db;
        $this->table = $context->parse_table($table);
		$this->initModel();
    }

	/**
	 * Set query string
	 */
	private function setQueryString($query) {
		$this->query_string = $query;
	}

	/**
	 * Get last inserted ID (AI)
	 */
	function lastInsertedId() {
		return $this->db->lastInsertId();
	}

	/**
	 * Return repository error
	 */
	function error() {
		return $this->error;
	}

	/**
	 * Insert
	 * @param Object $payload
	 * @return Object
	 */
	function insert($payload) {
		try {
			$builder = $this->insertBuilder($payload);
			$response = $this->executeQuery($builder->query, $builder->values);
            $payload->id = $this->lastInsertedId();
            return $payload;
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
	    }
		return false;
	}

	/**
	 * Update
	 * @param Object|String|Number $payload
	 * @param Object $payload
	 * @return Object
	 * 
	 */
	function update($where, $update) {
		try {
			$builder = $this->updateBuilder($where, $update);
			$response = $this->executeQuery($builder->query, $builder->values);
            
			return $this->findOne($where);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
	    }
		return false;
	}

	/**
	 * Save
	 * @param Object $payload
	 * @return Object
	 */
	function save($payload = array()) {
		if (empty($payload->id)) {
			return $this->insert($payload);
		} else {
			return $this->update($payload->id, $payload);
		}
	}

	/**
	 * Find Many
	 * @param Object|String|Number $payload
	 * @return Array|Object
	 */
	function find($payload = array()) {
		$data = array();
		$where = $this->whereBuilder($payload);
		$select = $this->selectBuilder($payload['select'] ?? [], $where);
		try {
	    	$data = $this->executeQuery($select, $where->values, true);
			$data = $this->includeBuilder($data ?? [], $payload);
			return $data;
	    }
	    catch (Exception $e) {
	    	$this->error = $e->getMessage();
				throw new \Exception($e->getMessage(), 1);
		}
	    catch (PDOException $e) {
	    	$this->error = $e->getMessage();
				throw new \Exception($e->getMessage(), 1);
	    }

	    return $data;
	}

	/**
	 * Include builder first step
	 * @param Object[] $data
	 * @return Object[]|Object
	 */
	private function includeBuilder($data = array(), $includes = array()) {
		if (!empty($includes['include'])) {
			foreach($data as $key => $value) {
				$data_include = $this->retrieveIncludes($includes['include'], array("{$this->table}_id" => $value->id));
				$data[$key] = (object) array_merge((array) $data[$key], (array) $data_include);
			}
		}

		return $data;
	}


	/**
	 * Check if there's include in payload
	 * @param Object $includes
	 * @param Object $option
	 * @return Object[]|Object 
	 */
	private function retrieveIncludes($includes = array(), $option = array()) {
		$data_include = array();
		foreach ($includes as $key => $value) {
			$table = $key;

			if ($value === false) {
				return $data_include;
			}

			/**
			 * If boolean
			 */
			$condition = array();
			$single = $value['single'] ?? false;
			$function = $single ? 'findOne' : 'find';


			if (is_bool($value)) {
				$condition['where'] = $option;
			} else {
				$condition['where'] = array_merge(($value['where'] ?? array()), $option ?? array());
				$condition = array_merge($value, $condition);

			}

			$data_include[$table] = $this->context->$table->$function($condition);
		}
		return $data_include;
	}

	/**
	 * Find One
	 * @param Object|String|Number $payload
	 * @return Object
	 */
	function findOne($payload = array()) {
		if (empty($payload)) {
			return null;
		}

		$response = $this->find(array(
			'where' => $payload['where'] ?? $payload,
			'limit' => 1,
			'delete' => $payload['delete'] ?? false,
			'include' => $payload['include'] ?? array()
		));

		if (!empty($response)) {
			return $response[0];
		}

		return null;
	}

	/**
	 * Soft delete
	 * @param Object|String|Number $payload
	 * @return Object
	 */
	function softDelete($payload = array()) {
		return $this->update($payload, (object) array('deleted_at' => date('Y-m-d H:i:s')));
	}

	/**
	 * Delete
	 * @param Object $where
	 * @return Object
	 */
	function delete($where = array()) {
		try {
			$delete = $this->deleteBuilder($where);
			$response = $this->executeQuery($delete->query, $delete->values);
			return $response;
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
	    }
		return false;
	}
	
	/**
	 * Count
	 * @param Object|String|Number $where
	 * @return Number
	 */
	function count($where = array()) {
		try {
			$count_query = "count(*) as count";
			$where = $this->whereBuilder($where);
			$select = $this->selectBuilder($count_query, $where);
			$response = $this->executeQuery($select, $where->values, true);
			return $response[0]->count;
	    }
	    catch (Exception $e) {
	    	$this->error = $e->getMessage();
				throw new \Exception($e->getMessage(), 1);
		}
	    catch (PDOException $e) {
	    	$this->error = $e->getMessage();
				throw new \Exception($e->getMessage(), 1);
	    }

	    return 0;
	}

	/**
	 * Paginate
	 * @param Object $pagination
	 * @return Object
	 */
	function paginate($pagination = array()) {
		$paginate = array();
		$page = $pagination['page'] ?? 1;
		$limit = $pagination['limit'] ?? 10;

		unset($pagination['page']);
		unset($pagination['limit']);

		$condition = $pagination;
		$condition['limit'] = $limit;
		$condition['offset'] = $page == 1 ? 0 : ($page - 1) * $limit;
		$data = $this->find($condition);
		return $data;
	}

	/**
	 * Execute Query
	 * @param String $query
	 * @param Object $values
	 */
	function executeQuery($query, $values = array(), $select = false) {
		try {
			$sql = $this->db->prepare($query);
			$response = $sql->execute($values);
			if ($select) {
				return array_map(function($value) {
					return (object) $value;
				}, $sql->fetchAll(PDO::FETCH_ASSOC));
			}
			return $response;
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
	    }
	}

	/**
	 * Utilities
	 */
	function initModel() {
		$GLOBALS['repository'] = $this;
		$classModelName = str_replace('_', '', pascalCase($this->table));
		$classString = "class {$classModelName} extends Model {
			function __construct() {
				global \$repository;
				\$this->table = '{$this->table}';
				parent::__construct(\$repository);
			}
		}";
		eval($classString);
	}

	/**
	 * Select Builder
	 * @param Object $select
	 * @param Object $where
	 * @return String
	 */
	private function selectBuilder($select = array(), $where = array()) {
		if (empty($select)) {
			$select = ['*'];
		}
		if (is_array($select)) {
			$select = implode(',', $select);
		}
		$query = "SELECT {$select} FROM {$this->table} {$where->query} {$this->query_group} {$this->query_having} {$this->query_order} {$this->query_limit} {$this->query_offset}";
		$this->setQueryString($query);
		return $query;
	}

	/**
	 * Where Builder
	 * @param Object $where
	 * @return Object
	 */
	private function whereBuilder($where = array()) {
		// Reset
		unset($where['select']);
		$this->resetBuilder();


		// Start
		$where_payload = $where['where'] ?? $where;

		if (!is_array($where_payload)) {
			$where_payload = array(
				'id' => array('eq' => $where['where'] ?? $where)
			);
		}

		$where_limit = $where['limit'] ?? '';
		$where_order = $where['order'] ?? '';
		$where_having = $where['having'] ?? '';
		$include_delete = $where['delete'] ?? false;
		$where_offset = $where['offset'] ?? '';

		if (!is_array($where_payload)) {
			$where_payload = array(
				'id' => array('eq' => $where_payload)
			);
		}

		if (isset($where['limit'])) {
			unset($where['limit']);
			unset($where_payload['limit']);
			$this->limitBuilder($where_limit);
		}

		if (!empty($where_order)) {
			unset($where['order']);
			unset($where_payload['order']);
			$this->orderBuilder($where_order);
		}

		if (!empty($where_having)) {
			unset($where['having']);
			unset($where_payload['having']);
			$this->havingBuilder($where_having);
		}

		if (isset($where['offset'])) {
			unset($where['offset']);
			unset($where_payload['offset']);
			$this->offsetBuilder($where_offset);
		}

		$operations = array();
		$columns_defined = array_keys($where_payload);

		$where_payload['deleted_at'] = array('null' => true);
		if ($include_delete) {
			unset($where['delete']);
			unset($where_payload['deleted_at']);
		}

		foreach($where_payload as $column => $opt) {
			if (is_array($opt)) {
				$keys = array_keys($opt);
				if (count($keys) > 0) {
					$keys = $keys[0];
				}
			} else {
				$keys = 'eq';
				$opt = array('eq' => $opt);
			}



			switch ($keys) {
				case "or":
				case "and":
					$ors_value = $opt[$keys];
					$or_return = $this->operationBuilder($column, $ors_value);
					$operations[] = array(
						'columns' => [" ( " . implode(" {$keys} ", $or_return['columns'] ) . " ) "],
						'values' => $or_return['values'],
						'dummy' => $or_return['dummy'],
					);
					break;
				default:
					$operations[] = $this->operationBuilder($column, $opt);
					break;
			}
		}



		$columns = array_merge(...array_map(function($val) {
			return $val['columns'];
		}, $operations));

		$values = array_merge(...array_map(function($val) {
			return $val['values'];
		}, $operations));

		$query = '';
		if (!empty($operations)) {
			$query = " WHERE " . implode(' and ', $columns);
		}

		$response = (object) array(
			'query' => $query,
			'columns' => $columns,
			'values' => $values,
		);

		return $response;
	}

	/**
	 * Operation Builder
	 * @param Object $where
	 * @return Object
	 */
	private function operationBuilder($column, $where = array()) {
		$accepted_operation = ['or', 'in', 'eq', 'between', 'gt', 'gte', 'lt', 'lte', 'like', 'and', 'null', 'not_null'];
		$operation = array(
			'eq' => '= ?',
			'gt' => '> ? ',
			'gte' => '>= ? ',
			'lt' => '< ? ',
			'lte' => '<= ?',
			'between' => 'between ? and ?',
			'like' => 'like ?',
			'null' => 'IS NULL',
			'not_null' => 'IS NOT NULL',
		);

		$columns = [];
		$values = [];
		$dummy = [];

		$where_operations = array();

		// Get Keys
		$keys = array_keys($where);
		foreach ($keys as $where_operation) {
			if (in_array($where_operation, $accepted_operation)) {
				$symbol = (!empty($operation[$where_operation]) ? $operation[$where_operation ]: '');
				$where_operations[$where_operation] = $symbol;
			}
		}
		// Check get operations
		foreach ($where_operations as $key => $value) {
			if (is_array($where[$key])) {
				$values = array_merge($values, $where[$key]);
			} else {
				if ($key === 'like') {
					$where[$key] = "%{$where[$key]}%";
				}
				$values[] = $where[$key];
			}

			if ($key === 'in') {
				$repeat = trim(str_repeat('? ', count($where[$key])));
				$value = " in (" . implode(", ", explode(' ',$repeat)) . ") ";
			}

			if (in_array($key, ['null', 'not_null'])) {
				array_shift($values);
			}

			$columns[] = " {$column} {$value}";
		}

		return array(
			'columns' => $columns,
			'values' => $values,
			'dummy' => $dummy,
		);
	}

	/**
	 * Limit Builder
	 * @param Number $limit
	 * @return String
	 */
	private function limitBuilder($limit) {
		// if (!empty($limit)) {
			$this->query_limit = "LIMIT {$limit}";
		// }
	}

	/**
	 * Offset Builder
	 * @param Number $offset
	 * @return String
	 */
	private function offsetBuilder($offset) {
		// if (!empty($offset)) {
			$this->query_offset = "OFFSET {$offset}";
		// }
	}

	/**
	 * Insert builder
	 * @param Object|String|Number $payload
	 * @param Object $update
	 * @return String
	 */
	private function updateBuilder($where, $payload) {
		$columns = [];
		$values = [];
		if (!empty($payload->id)) {
			unset($payload->id);
		}

		$payload->updated_at = date('Y-m-d H:i:s');

		foreach($payload as $column => $value) {
			$columns[] = "`{$column}` = ?";
			$values[] = $value;
		}

		$where = $this->whereBuilder($where);

		$query = "UPDATE " . $this->table . " SET " . implode(", ", $columns) . $where->query;
		$this->setQueryString($query);
		return (object) array(
			'query' => $query,
			'columns' => $columns,
			'values' => array_merge($values, $where->values),
			'dummy' => [],
		);
	}

	/**
	 * Insert builder
	 * @return Object
	 */
	private function insertBuilder($payload) {
		$columns = [];
		$values = [];
		$dummy = [];

		unset($payload->id);

		foreach($payload as $column => $value) {
			$columns[] = "`{$column}`";
			$values[] = $value;
			$dummy[] = "?";
		}

		$query = "INSERT INTO " . $this->table . " ( " . implode(", ", $columns) . " ) VALUES ( " . implode(", ", $dummy) . " ) ";
		$this->setQueryString($query);
		return (object) array(
			'query' => $query,
			'columns' => $columns,
			'values' => $values,
			'dummy' => $dummy,
		);
	}


	/**
	 * Insert builder
	 * @param Object|String|Number $payload
	 * @param Object $update
	 * @return String
	 */
	private function deleteBuilder($where) {
		$where = array('where' => $where['where'] ?? $where, 'delete' => true);
		$where = $this->whereBuilder($where);

		$query = "DELETE FROM " . $this->table . $where->query;
		$this->setQueryString($query);
		return (object) array(
			'query' => $query,
			'columns' => [],
			'values' => array_merge([], $where->values),
			'dummy' => [],
		);
	}

	/**
	 * Order By Builder
	 * @param Object $order
	 * @return String
	 */
	private function orderBuilder($order = array()) {
		if (!empty($order)) {
			$orders = array();
			foreach ($order as $key => $value) {
				$orders[] = "{$key} {$value}";
			}

			$this->query_order = " ORDER BY " . implode(', ', $orders);
		}
	}

	/**
	 * Group Builder
	 * @param Object|String $group
	 * @return String
	 */
	private function groupBuilder($group = array()) {
		if (!empty($group)) {
			$this->query_group = " GROUP BY " . (!is_array($group) ? $group : implode(',' , $group));
		}
	}

	/**
	 * Having Builder
	 * @param Object|String $having
	 * @return String
	 */
	private function havingBuilder($having = array()) {
		if (!empty($having)) {
			$this->query_having = " HAVING " . (!is_array($having) ? $having : implode (' and ', $having));
		}
	}

	// function addWhere($where) {
	// 	if (empty($where)) {
	// 		return;
	// 	}

	// 	if (!empty($where)) {
	// 		$this->add_where[] = $where;
	// 		return;
	// 	}

	// 	$this->add_where = $where;
	// }

	/**
	 * Builder resetter
	 */
	private function resetBuilder() {
		$this->query_limit = '';
		$this->query_order = '';
		$this->query_group = '';
		$this->query_having = '';
		$this->query_offset = '';
	}

	function lastQuery() {
		return $this->query_string;
	}
}
