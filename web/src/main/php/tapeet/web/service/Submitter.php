<?php
namespace tapeet\web\service;


use \tapeet\Filter;
use \tapeet\FilterChain;



class Submitter implements Filter {


	public $controllerState;
	public $response;
	public $url;


	function execute(FilterChain $chain) {
		if ($this->controllerState->isRenderRequest()) {
			return $chain->execute();
		}

		$redirect = $this->submit($this->controllerState->components);

		if ($redirect == null) {
			$redirect = $this->controllerState->page;
		}

		$this->response->sendRedirect(
				$this->response->encodeRedirectURL(
						$this->url->getURL($redirect)
					)
			);

		return $chain->execute();
	}


	function submit($components) {
		$redirect = null;

		$component = array_shift($components);

		if (! empty($components)) {
			$redirect = $this->submit($components);

			$subComponent = $components[0];
			$submitFromChildMethod = 'onSubmitFrom' . ucfirst(str_replace('/', '_', $subComponent->_name));
			if (method_exists($component, $submitFromChildMethod)) {
				$redirect = $component->$submitFromChildMethod();
			}
		}

		if (method_exists($component, 'onSubmit')) {
			$redirect = $component->onSubmit();
		}

		return $redirect;
	}

}
