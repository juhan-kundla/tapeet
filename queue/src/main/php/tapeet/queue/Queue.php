<?php
namespace tapeet\queue;


use Exception;


class Queue {


	/** @Service */
	public $connection;
	public $id;
	/** @Service */
	public $logger;


	function add($event) {
		$query = '
				insert into queue.event (
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
						from queue.queue_consumer
						where queue_id = ?
			';

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

		$query = '
				UPDATE queue.event
						SET in_process = NOW()
						WHERE
								in_process IS NULL
								AND queue_id = ?
								AND consumer_id = ?
						ORDER BY id
						LIMIT 1
			';
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

		$query = '
				SELECT id, payload, type
						FROM queue.event
						WHERE
								in_process IS NOT NULL
								AND processed IS NULL
								AND queue_id = ?
								AND consumer_id = ?
						LIMIT 1
			';
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


	function setError($event, $error) {
		$query = '
				UPDATE queue.event SET
						 in_process = NULL
						,processed = NULL
						,errors = errors + 1
						,last_error = ?
						WHERE id = ?
			';
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


	function setProcessed($event) {
		$query = '
				UPDATE queue.event
						SET processed = NOW()
						WHERE id = ?
			';
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
?>