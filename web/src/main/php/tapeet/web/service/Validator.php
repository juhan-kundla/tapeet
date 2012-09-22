<?php
namespace tapeet\web\service;


use \tapeet\Filter;
use \tapeet\FilterChain;


class Validator implements Filter {


	/** @Service */
	public $controllerState;
	/** @Service */
	public $response;
	/** @Service */
	public $url;


	function execute(FilterChain $chain) {
		$this->validate($this->controllerState->components);

		if ($this->controllerState->isSubmitRequest()) {
			$valid = true;
			foreach ($this->controllerState->components as $component) {
				foreach ($component->_parameters as $parameter) {
					if (! empty($parameter->errors)) {
						$valid = false;
						break 2;
					}
				}
			}

			if (! $valid) {
				$parameters = array();
				$first = true;
				foreach ($this->controllerState->components as $component) {
					if ($first) {
						// First is the Page it will get passivated by the URL
						$first = false;
						continue;
					}
					$parameters = array_merge($parameters, $component->_parameters);
				}

				$this->response->sendRedirect(
						$this->response->encodeRedirectURL(
								$this->url->getURL($this->controllerState->page, $parameters)
							)
					);

				return;
			}
		}

		return $chain->execute();
	}


	function validate($components) {
		$component = array_shift($components);

		if (! empty($components)) {
			$this->validate($components);
		}

		foreach ($component->_parameters as $parameter) {
			$parameter->onValidate();
		}

		if (method_exists($component->object, 'onValidate')) {
			$component->onValidate();
		}
	}

}
