<?php
namespace tapeet\web\service;


use tapeet\Filter;


class Submitter implements Filter {


	/** @Service */
	public $controllerState;
	/** @Service */
	public $response;
	/** @Service */
	public $url;


	function doFilter($chain) {
		if ($this->controllerState->isRenderRequest()) {
			$chain->doFilter();
			return;
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

		$chain->doFilter();
	}


	function submit($components) {
		$redirect = null;

		$component = array_shift($components);

		if (! empty($components)) {
			$redirect = $this->submit($components);

			$subComponent = $components[0];
			$submitFromChildMethod = 'onSubmitFrom' . ucfirst(str_replace('/', '_', $subComponent->_name));
			if (method_exists($component->object, $submitFromChildMethod)) {
				$redirect = $component->$submitFromChildMethod();
			}
		}

		if (method_exists($component->object, 'onSubmit')) {
			$redirect = $component->onSubmit();
		}

		return $redirect;
	}

}
