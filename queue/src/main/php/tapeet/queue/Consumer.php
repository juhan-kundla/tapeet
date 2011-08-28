<?php
namespace tapeet\queue;


use Exception;
use tapeet\web\FilterChain;


class Consumer {


	public $id;
	/** @Service */
	public $connection;
	/** @Service */
	public $handlerChainFilters;
	/** @Service */
	public $logger;
	/** @Service */
	public $queue;
	/** @ServiceLocator */
	public $serviceLocator;
	/** @Service */
	public $sleeper;


	function run() {
		while (true) {
			$event = null;

			$this->connection->query('START TRANSACTION');
			try {
				$event = $this->queue->poll($this->id);

				if ($event !== null) {
					$this->logger->debug(sprintf('Consumer %s is processing event type: %s -> %s (%s)', $this->id, $this->queue->id, $event->type, $event->id));
					$this->serviceLocator->addService('event', $event);
					$handlerChain = new FilterChain();
					$handlerChain->filters = $this->handlerChainFilters;
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
