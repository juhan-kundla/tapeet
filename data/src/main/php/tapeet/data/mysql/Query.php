<?php
namespace tapeet\data\mysql;


use \Exception;


class Query {


	public $connection;
	public $fieldCount;
	public $logger;
	public $parameters = array();
	public $query;


	function addParameter($parameter) {
		$name = '_parameter_' . count($this->parameters);
		$this->$name = $parameter;
		$this->parameters[] = &$this->$name;
	}


	function getFields() {
		$fields = array();
		for ($i = 0; $i < $this->fieldCount ; $i++) {
			$field = '_field_' .$i;
			$this->$field = null;
			$fields[] = &$this->$field;
		}
		return $fields;
	}


	function getRow() {
		$rows = $this->getRows();
		if (count($rows) > 1) {
			throw new Exception('Query returned more than one row');
		}
		if (empty($rows)) {
			return null;
		}
		return $rows[0];
	}


	function getRows() {
		$this->logger->debug($this->query);
		$statement = $this->connection->prepare($this->query);
		if (! $statement) {
			throw new Exception($this->connection->error);
		}

		try {
			$parameters = $this->parameters;
			if (! empty($parameters)) {
				$parameterTypes = '';
				foreach ($parameters as $parameter) {
					$parameterTypes .= 's';
				}
				array_unshift($parameters, $parameterTypes);
				$this->logger->debug(print_r($parameters, true));
				call_user_func_array(array($statement, 'bind_param'), $parameters);
			}

			if (! $statement->execute()) {
				throw new Exception($statement->error);
			}

			$fields = $this->getFields();
			call_user_func_array(array($statement, 'bind_result'), $fields);

			$result = array();
			while ($statement->fetch()) {
				$row = array();
				foreach ($fields as $field) {
					array_push($row, $field);
				}
				array_push($result, $row);
			}
			$statement->close();
			return $result;
		} catch (Exception $e) {
			try {
				$statement->close();
			} catch (Exception $ignored) {}
			throw $e;
		}
	}

}
