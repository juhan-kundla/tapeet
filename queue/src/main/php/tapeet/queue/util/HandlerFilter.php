<?php
namespace tapeet\queue\util;


use \Exception;
use \tapeet\Filter;
use \tapeet\FilterChain;
use \tapeet\annotation\Context;


class HandlerFilter implements Filter {


	/** @Context */
	public $context;
	public $event;
	public $handlerFactory;


	function execute(FilterChain $chain) {
		$handler = $this->handlerFactory->getHandler($this->event->type);
		if ($handler === null) {
			throw new Exception('Failed to get handler for event type: ' . $this->event->type);
		}

		$this->context->add('handler', $handler);
		$handler->onEvent();

		return $chain->execute();
	}

}
