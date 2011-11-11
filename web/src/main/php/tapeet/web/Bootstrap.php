<?php
namespace tapeet\web;


use \tapeet\ioc\IOCProxy;
use \tapeet\ioc\ServiceLocator;


class Bootstrap {


	function boot() {
		new IOCProxy('tapeet\web\util\ConfigurationContributor');
		new IOCProxy('tapeet\web\util\LoggerContributor');

		$services = ServiceLocator::getServiceLocator();

		$services->addServiceClass('request', 'tapeet\web\Request');
		$services->addServiceClass('response', 'tapeet\web\Response');
		$services->addServiceClass('controllerState', 'tapeet\web\service\ControllerState');
		$services->addServiceClass('componentFactory', 'tapeet\web\service\ComponentFactory');
		$services->addServiceClass('url', 'tapeet\web\service\URL');

		$services->addService(
				'filters'
				, array(
						 'tapeet\web\service\PageFinder'
						,'tapeet\web\service\Activator'
						,'tapeet\web\service\Validator'
						,'tapeet\web\service\Submitter'
						,'tapeet\web\service\Renderer'
					)
			);

		new IOCProxy('tapeet\web\util\BootstrapContributor');
		$bootstrap = $services->getService('bootstrap');

		if ($bootstrap != null && method_exists($bootstrap->object, 'beginRequest')) {
			$bootstrap->beginRequest();
		}

		$chain = new IOCProxy('tapeet\FilterChain');
		$chain->doFilter();

		if ($bootstrap != null && method_exists($bootstrap->object, 'endRequest')) {
			$bootstrap->endRequest();
		}
	}

}
