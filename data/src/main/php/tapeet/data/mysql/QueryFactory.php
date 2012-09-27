<?php
namespace tapeet\data\mysql;


class QueryFactory {


	public $alias;
	public $count = false;
	private $criteria;
	public $distinct = false;
	public $fields;
	private $joins;
	public $limit;
	public $offset;
	public $orders;
	private $tables;


	function __construct() {
		$this->criteria = array();
		$this->fields = array();
		$this->joins = array();
		$this->orders = array();
		$this->tables = array();
	}


	function addCriterion($criterion) {
		array_push($this->criteria, $criterion);
	}


	function addField($field) {
		array_push($this->fields, $field);
	}


	function addJoin(Join $join) {
		$this->joins[] = $join;
	}


	function addOrder($order) {
		array_push($this->orders, $order);
	}


	function addTable($table) {
		array_push($this->tables, $table);
	}


	function createQuery() {
		$query = 'SELECT';
		$parameters = array();

		if ($this->count) {
			if ($this->distinct) {
				$query .= ' COUNT(DISTINCT ' . $this->table . '.id)'; // OK, that's a hack, but should work OK
			} else {
				$query .= ' COUNT(*)';
			}
		} else {
			if ($this->distinct) {
				$query .= ' DISTINCT';
			}
			$fields = array();
			foreach ($this->fields as $field) {
				if (is_string($field)) {
					$fields[] = $field;
				} else {
					// It's a subquery
					$fieldQuery = $field->createQuery();
					$fields[] = '(' . $fieldQuery->query . ') ' . $field->alias;
					$parameters = array_merge($parameters, $fieldQuery->parameters);
				}
			}
			$query .= ' ' . implode(', ', $fields);
		}

		$tables = array();
		foreach ($this->tables as $table) {
			if (is_string($table)) {
				$tables[] = $table;
			} else {
				$tableQuery = $table->createQuery();
				$tables[] = '(' . $tableQuery->query . ') ' . $table->alias;
				$parameters = array_merge($parameters, $tableQuery->parameters);
			}
		}
		$query .= ' FROM ' . join(', ', $tables);

		foreach ($this->joins as $join) {
			$query .= ' ' . $join->type;
			if (is_string($join->table)) {
				$query .= ' ' . $join->table;
			} else {
				$joinQuery = $join->table->createQuery();
				$query .= ' (' . $joinQuery->query . ') ' . $join->table->alias;
				$parameters = array_merge($parameters, $joinQuery->parameters);
			}
			$query .= ' ON (' . $join->condition . ')';
		}

		$criteria = array();
		foreach ($this->criteria as $criterion) {
			if (is_string($criterion)) {
				$criteria[] = $criterion;
			} else {
				$criteria[] = $criterion->criterion;
				$parameters = array_merge($parameters, $criterion->parameters);
			}
		}
		if (! empty($criteria)) {
			$query .= ' WHERE ' . implode (' AND ', $criteria);
		}

		if (! $this->count && ! empty($this->orders)) {
			$query .= ' ORDER BY ' . implode (', ', $this->orders);
		}

		if (! $this->count && $this->limit !== null) {
			$query .= ' LIMIT ?';
			$parameters[] = $this->limit;
		}

		if (! $this->count && $this->offset !== null) {
			$query .= ' OFFSET ?';
			$parameters[] = $this->offset;
		}

		$result = new Query();
		if ($this->count) {
			$result->fieldCount = 1;
		} else {
			$result->fieldCount = count($this->fields);
		}
		$result->query = $query;
		foreach ($parameters as $parameter) {
			$result->addParameter($parameter);
		}
		return $result;
	}

}
