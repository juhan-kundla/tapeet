<?php
namespace tapeet\queue;


use \Exception;
use \tapeet\FilterChain;
use \tapeet\annotation\Context;


class Consumer {


	public $id;
	public $connection;
	/** @Context */
	public $context;
	public $filters;
	public $logger;
	public $queue;
	public $sleeper;


	function run() {
		while (true) {
			$event = null;

			$this->connection->query('START TRANSACTION');
			try {
				$event = $this->queue->poll($this->id);

				if ($event !== null) {
					$this->logger->debug(sprintf('Consumer %s is processing event type: %s -> %s (%s)', $this->id, $this->queue->id, $event->type, $event->id));
					$this->context->add('event', $event);
					$handlerChain = new FilterChain();
					$handlerChain->filters = $this->filters;
					$handlerChain->doFilter();
					$this->queue->setProcessed($event);
				}

				$this->connection->commit();
			} catch (Exception $e) {
				try {
					$this->logger->err(sprintf('Consumer %s failed to process event %s -> %s (%s): %s', $this->id, $this->queue->id, $event->type, $event->id, $e));
				} catch (Exception $ignored) {}

				try {
					$this->connection->rollback();
				} catch (Exception $rollbackException) {
					try {
						$this->logger->err(sprintf('Additionally caught an exception, while tried to rollback: %s', $rollbackException));
					} catch (Exception $ignored) {}
				}

				try {
					if ($event !== null) {
						$this->queue->setError($event, $e);
						$this->connection->commit();
					}
				} catch (Exception $eventUpdateException) {
					try {
						$this->logger->err(sprintf('Additionally caught an exception, while tried save event errors: %s', $eventUpdateException));
					} catch (Exception $ignored) {}
				}

				throw $e;
			}

			$this->sleeper->sleep();
		}
	}

}
