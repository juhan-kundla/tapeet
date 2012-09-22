<?php
namespace tapeet\web\util;


use \ErrorException;
use \Exception;
use \tapeet\Filter;
use \tapeet\FilterChain;
use \tapeet\web\NotFoundException;
use \tapeet\web\Response;
use \tapeet\web\security\AccessDeniedException;


class ErrorHandlerFilter implements Filter {


	/** @Configuration */
	public $debug;
	/** @Service */
	public $logger;
	/** @Service */
	public $response;


	function __construct() {
		set_error_handler(array($this, 'onError'));
	}


	function execute(FilterChain $chain) {
		try {
			return $chain->execute();
		} catch (AccessDeniedException $e) {
			$this->response->sendError(Response::$SC_FORBIDDEN);
			$this->response->write("<h1>Access denied</h1>");
		} catch (NotFoundException $e) {
			$this->response->sendError(Response::$SC_NOT_FOUND);
			$this->response->write("<h1>Not found</h1>");
		} catch (Exception $e) {
			$this->logger->err('' . $e);
			$this->response->write("<h1>Internal server error</h1>");
			if ($this->debug) {
				$this->response->write("<pre>" . $e . "</pre>");
			}
		}
	}


	function onError($errno, $errstr, $errfile, $errline) {
		$exception = new ErrorException($errstr, 0, $errno, $errfile, $errline);
		switch ($errno) {
			case E_NOTICE:
			case E_USER_NOTICE:
			case E_WARNING:
			case E_CORE_WARNING:
			case E_COMPILE_WARNING:
			case E_USER_WARNING:
			case E_STRICT:
			case E_RECOVERABLE_ERROR:
				$this->logger->warning('' . $exception);
				return false;

			case E_DEPRECATED:
			case E_USER_DEPRECATED:
				//$this->logger->debug('' . $exception);
				return false;
		}
		throw $exception;
	}

}
