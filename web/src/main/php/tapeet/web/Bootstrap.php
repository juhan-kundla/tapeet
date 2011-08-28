<?php
namespace tapeet\web;


require_once 'tapeet/ClassLoader.php';


// Work-around for an annotation bug. The ServiceLocator annotation must be required
// before Service annotation, otherwise it won't work. Also, for some reason, the annotations
// fail, if they're dynamically loaded later by the PageFactory or similar service :-S
require_once 'tapeet/annotation/ServiceLocator.php';
require_once 'tapeet/annotation/Service.php';
require_once 'tapeet/annotation/Configuration.php';

require_once 'tapeet/web/annotation/Component.php';
require_once 'tapeet/web/annotation/Contributor.php';
require_once 'tapeet/web/annotation/Group.php';
require_once 'tapeet/web/annotation/Length.php';
require_once 'tapeet/web/annotation/Page.php';
require_once 'tapeet/web/annotation/Parameter.php';
require_once 'tapeet/web/annotation/Required.php';
require_once 'tapeet/web/annotation/Secured.php';
require_once 'tapeet/web/annotation/User.php';


use tapeet\ioc\IOCProxy;
use tapeet\ioc\ServiceLocator;


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
