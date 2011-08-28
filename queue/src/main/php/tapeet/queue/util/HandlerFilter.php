<?php
namespace tapeet\queue\util;


use Exception;
use tapeet\Filter;


class HandlerFilter implements Filter {


	/** @Service */
	public $event;
	/** @Service */
	public $handlerFactory;
	/** @ServiceLocator */
	public $serviceLocator;


	function doFilter($chain) {
		$handler = $this->handlerFactory->getHandler($this->event->type);
		if ($handler === null) {
			throw new Exception('Failed to get handler for event type: ' . $this->event->type);
		}

		$this->serviceLocator->addService('handler', $handler);
		$handler->onEvent();

		$chain->doFilter();
	}

}
