<?php
namespace tapeet\data\mysql;


use \Exception;


abstract class MySQLDAO {


	abstract function createData($object);
	abstract function createObject($data);


	function createQueryFactory() {
		$factory = new QueryFactory();
		$factory->addTable($this->table);
		foreach ($this->getFields() as $field) {
			$factory->addField($field);
		}
		return $factory;
	}


	function delete($id) {
		$query = 'DELETE FROM ' . $this->table . ' WHERE id = ?';
		$this->logger->debug($query);
		$this->logger->debug($id);

		$statement = $this->connection->prepare($query);
		if (! $statement) {
			throw new Exception($this->connection->error);
		}

		try {
			$statement->bind_param('s', $id);
			if (! $statement->execute()) {
				throw new Exception($statement->error);
			}
			$statement->close();
		} catch (Exception $e) {
			try {
				$statement->close();
			} catch (Exception $ignored) {}
			throw $e;
		}
	}


	function getFields() {
		$fields = array();
		foreach (array_keys($this->fields) as $field) {
			$fields[] = $this->table . '.' . $field;
		}
		return $fields;
	}


	function insert($object) {
		$placeholders = array();
		foreach (array_keys($this->fields) as $field) {
			array_push($placeholders, '?');
		}

		$query = 'insert into '
				. $this->table . ' ('
				. implode(', ', array_keys($this->fields))
				. ') values ('
				. implode(', ', $placeholders)
				. ')'
			;
		if (isset($this->logger)) {
			$this->logger->debug($query);
		}

		$statement = $this->connection->stmt_init();
		try {
			if (! $statement->prepare($query)) {
				throw new Exception($statement->error);
			}
			$bindArgs = $this->createData($object);
			if (! empty($bindArgs)) {
				array_unshift($bindArgs, implode('', array_values($this->fields)));
				if (isset($this->logger)) {
					$this->logger->debug($bindArgs);
				}
				call_user_func_array(array($statement, 'bind_param'), $bindArgs);
			}
			if (! $statement->execute()) {
				throw new Exception($statement->error);
			}
			if ($object->id == null) {
				$object->id = $statement->insert_id;
			}
			$statement->close();
		} catch (Exception $e) {
			try {
				$statement->close();
			} catch (Exception $ignored) {}
			throw $e;
		}
	}


	function load($id) {
		$factory = $this->createQueryFactory();
		$factory->addCriterion(new Criterion($this->table . '.id = ?', array($id)));
		$query = $factory->createQuery();
		$query->connection = $this->connection;
		$query->logger = $this->logger;
		$row = $query->getRow();
		return $row === NULL ? NULL : $this->createObject($row);
	}


	function loadAll() {
		$factory = $this->createQueryFactory();
		$query = $factory->createQuery();
		$query->connection = $this->connection;
		$query->logger = $this->logger;
		$objects = array();
		foreach ($query->getRows() as $row) {
			array_push($objects, $this->createObject($row));
		}
		return $objects;
	}


	function save($object) {
		$this->logger->debug(__METHOD__ . '(' . get_class($object) . ')');
		if ($object->id === NULL) {
			$this->insert($object);
		} else {
			$this->update($object);
		}
	}


	function update($object) {
		$placeholders = array();
		foreach (array_keys($this->fields) as $field) {
			array_push($placeholders, $field  . ' = ?');
		}

		$query = 'update '
				. $this->table . ' set '
				. implode(', ', $placeholders)
				. ' where id = ' . $object->id
			;
		if (isset($this->logger)) {
			$this->logger->debug($query);
		}

		$statement = $this->connection->stmt_init();
		try {
			if (! $statement->prepare($query)) {
				throw new Exception($statement->error);
			}
			$bindArgs = $this->createData($object);
			array_unshift($bindArgs, implode('', array_values($this->fields)));
			call_user_func_array(array($statement, 'bind_param'), $bindArgs);
			if (! $statement->execute()) {
				throw new Exception($statement->error);
			}
			$statement->close();
		} catch (Exception $e) {
			try {
				$statement->close();
			} catch (Exception $ignored) {}
			throw $e;
		}
	}

}
