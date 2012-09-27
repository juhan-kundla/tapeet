<?php
namespace tapeet\queue;


use Exception;


class Queue {


	public $connection;
	public $id;
	public $logger;
	public $schema;


	function add(Event $event) {
		$query = sprintf('
				insert into %s.event (
						consumer_id
						,payload
						,queue_id
						,type
					)
					select
							consumer_id
							,?
							,queue_id
							,?
						from %s.queue_consumer
						where queue_id = ?
			', $this->schema, $this->schema);

		$statement = $this->connection->prepare($query);
		if (! $statement) {
			throw new Exception($this->connection->error);
		}

		try {
			$statement->bind_param('sss', $event->payload, $event->type, $this->id);
			$statement->execute();
			if ($statement->affected_rows == 0) {
				$this->logger->warn(sprintf('The event was triggered for 0 consumers: %s -> %s', $this->id, $event->type));
			} else {
				$this->logger->debug(sprintf('The event was triggered for %d consumers: %s -> %s', $statement->affected_rows, $this->id, $event->type));
			}
			$statement->close();
		} catch (Exception $e) {
			try {
				$statement->close();
			} catch (Exception $ignored) {}
			throw $e;
		}
	}


	function poll($consumer) {
		$event = null;

		$query = sprintf('
				UPDATE %s.event
						SET in_process = NOW()
						WHERE
								in_process IS NULL
								AND queue_id = ?
								AND consumer_id = ?
						ORDER BY id
						LIMIT 1
			', $this->schema);
		$statement = $this->connection->prepare($query);
		if (! $statement) {
			throw new Exception($this->connection->error);
		}

		try {
			$statement->bind_param('ss', $this->id, $consumer);
			$statement->execute();
			$statement->close();
		} catch (Exception $e) {
			try {
				$statement->close();
			} catch (Exception $ignored) {}
			throw $e;
		}

		$query = sprintf('
				SELECT id, payload, type
						FROM %s.event
						WHERE
								in_process IS NOT NULL
								AND processed IS NULL
								AND queue_id = ?
								AND consumer_id = ?
						LIMIT 1
			', $this->schema);
		$statement = $this->connection->prepare($query);
		if (! $statement) {
			throw new Exception($this->connection->error);
		}

		try {
			$statement->bind_param('ss', $this->id, $consumer);
			$statement->execute();

			$statement->bind_result($id, $payload, $type);
			if ($statement->fetch()) {
				$event = new Event($type, $payload);
				$event->id = $id;
			}
			$statement->close();
		} catch (Exception $e) {
			try {
				$statement->close();
			} catch (Exception $ignored) {}
			throw $e;
		}

		return $event;
	}


	function setError(Event $event, $error) {
		$query = sprintf('
				UPDATE %s.event SET
						 in_process = NULL
						,processed = NULL
						,errors = errors + 1
						,last_error = ?
						WHERE id = ?
			', $this->schema);
		$statement = $this->connection->prepare($query);
		if (! $statement) {
			throw new Exception($this->connection->error);
		}

		try {
			$statement->bind_param('ss', $error, $event->id);
			$statement->execute();
			$statement->close();
		} catch (Exception $e) {
			try {
				$statement->close();
			} catch (Exception $ignored) {}
			throw $e;
		}
	}


	function setProcessed(Event $event) {
		$query = sprintf('
				UPDATE %s.event
						SET processed = NOW()
						WHERE id = ?
			', $this->schema);
		$statement = $this->connection->prepare($query);
		if (! $statement) {
			throw new Exception($this->connection->error);
		}

		try {
			$statement->bind_param('s', $event->id);
			$statement->execute();
			$statement->close();
		} catch (Exception $e) {
			try {
				$statement->close();
			} catch (Exception $ignored) {}
			throw $e;
		}
	}

}
