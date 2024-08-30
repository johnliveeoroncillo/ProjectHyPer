<?php
class Repository {
    var $error;
    private $db;
    private $table;
	private $dbprefix;

	private $query_join_tables = array();
	private $query_where = array();
	private $query_string;

	private $query_order;
	private $query_limit;
	private $query_offset;

    function __construct($table, $db, $config = array()) {
        $this->dbprefix = $config['prefix'];
        $this->table = $table;
        $this->db = $db;
    }

	function parse_table($table) {
		return $this->dbprefix.$table;
	}

	private function parse_where($where) {
		if (is_string($where)) {
			return "WHERE ".str_replace(array('where', 'WHERE'), '', $where);
		}
		if (is_numeric($where)
			|| !empty($where) && is_object($where) && !empty($where->id)) {
			return "WHERE id = ".(is_object($where) && !empty($where->id) ? $where->id : $where);
		}
		if(!empty($where) && is_array($where)) {
			$temp = array();
			foreach ($where as $key => $value) {
				$temp[] = " {$key} = '{$value}' ";
			}
		}
		return (is_array($where) ?
					(!empty($temp)
						? "WHERE ".implode(' and ', $temp)
						: '') 
				: (empty($where)
					? ''
					: "WHERE ".$where));
	}

	private function parse_insert($insert_values = array()) {
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

		return array(
			'columns' => $columns,
			'values' => $values,
			'dummy' => $dummy,
		);
	}

	function query($query = '') {
		$data = array();
		$this->query_string = $query." {$this->query_order} {$this->query_limit} {$this->query_offset}";
		try {
			$query = $this->db->query($this->query_string);
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
		$this->reset();
		return array_map(function($value){
				return (object)$value;
		}, $data);
	}

	function find($where = array(), $single = false) {
		$data = array();

		$where_condition = $this->parse_where($where);
		$this->query_string = "SELECT * FROM {$this->parse_table($this->table)} {$where_condition}";

        if ($single) {
			$this->limit(1);
        }
		try {
	    	$data = $this->query($this->query_string);
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

	function findOne($where = array()) {
        if (is_numeric($where)) {
            $where = array('id' => $where);
        }

		$result = $this->find($where, true);
		if(!empty($result)) return $result[0];

		return array();
	}

	function count($where = array()) {
		$data = array();

		$where_condition = $this->parse_where($where);
		$this->query_string = "SELECT count(*) as count FROM {$this->parse_table($this->table)} {$where_condition}";
		try {
	    	$query = $this->query($this->query_string);
			if (count($query) > 0) {
				return $query[0]->count;
			}
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

	function insert($insert_values = array()) {
		try {
			$cols = $this->parse_insert($insert_values);
			$this->query_string = "INSERT INTO {$this->parse_table($this->table)} (".implode(',', $cols['columns']).") VALUES (".implode(',', $cols['dummy']).")";
			$sql = $this->db->prepare($this->query_string);
			$response = $sql->execute($cols['values']);
			
            $lastId = $this->lastInsertedId();
            return (object) array_merge((array)$insert_values, array('id' => $lastId));
		}
		catch (Exception $e) {
			$this->error = $e->getMessage();
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
	    }
	}

	function update($update_values = array(), $where = array()) {
		try {
			$cols = $this->parse_insert($update_values);
			$columns = array_map(function($val) {
				return $val.' = ? ';
			}, $cols['columns']);
			$where_condition = $this->parse_where($where);

			if (empty($where)) {
				throw new Error('No where condition on update');
			}

			$this->query_string = "UPDATE {$this->parse_table($this->table)}
									SET ".implode(',', $columns)."
									{$where_condition} ";

			$sql = $this->db->prepare($this->query_string);
			$response = $sql->execute($cols['values']);

			return $this->findOne($where_condition);
		}
		catch (Exception $e) {
			$this->error = $e->getMessage();
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
	    }
	}

    function save($payload) {
        if (!empty($payload->id)) {
			$newPayload = json_decode(json_encode($payload), true);
			unset($newPayload['id']);
            $response = $this->update($newPayload, array('id' => $payload->id));
        } else {
            $response = $this->insert($payload);
        }

        return $response;
    }

	function delete($where = array()) {
		try {
			$where_condition = $this->parse_where($where);

			if (empty($where)) {
				throw new Error('No where condition on update');
			}

			$this->query_string = "DELETE from {$this->parse_table($this->table)} {$where_condition}";
			
			$sql = $this->db->prepare($this->query_string);
			$response = $sql->execute($values);
			return $response;
		}
		catch (Exception $e) {
			$this->error = $e->getMessage();
			return false;
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
			return false;
		}
	}

	function limit($limit = 0) {
		$this->query_limit = 'LIMIT '.$limit;
	}

	function order_by($order_by = '', $order_by_value = '') {
		$order_string = '';
		if (is_string($order_by) && is_string($order_by_value) && !empty($order_by) && !empty($order_by_value)) {
			$order_string = $order_by.' '.$order_by_value;
		} else if (is_array($order_by)) {
			$order_string = implode(', ', array_map(function($val, $key) {
				return $key.' '.$val;
			}, $order_by, array_keys($order_by)));
		}
		$this->query_order = 'ORDER BY '.$order_string;
	}

	function offset($offset = 0) {
		$this->query_offset = 'OFFSET '.$offset;
	}

	private function reset() {
		$this->query_limit = '';
		$this->query_order = '';
		$this->query_offset = '';
	}

	function last_query() {
		return $this->query_string;
	}

	function error() {
		return $this->error;
	}

	function lastInsertedId() {
		return $this->db->lastInsertId();
	}
}