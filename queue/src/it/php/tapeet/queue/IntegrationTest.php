<?php
namespace tapeet\queue;


require_once 'tapeet/annotation/Configuration.php';
require_once 'tapeet/annotation/ServiceLocator.php';
require_once 'tapeet/annotation/Service.php';

require_once 'tapeet/queue/annotation/Event.php';


use \mysqli;

use \PHPUnit_Framework_TestCase;

use \tapeet\FilterChain;

use \tapeet\ioc\IOCProxy;
use \tapeet\ioc\ServiceLocator;

use \tapeet\queue\Consumer;
use \tapeet\queue\Event;
use \tapeet\queue\Queue;


class IntegrationTest extends PHPUnit_Framework_TestCase {


	const CONSUMER_ID = 'test';


	private $connection;
	private $queue;


	function setUp() {
		$services = ServiceLocator::getServiceLocator();
		$services->addServiceClass('handlerFactory', '\tapeet\queue\util\HandlerFactory');
		$services->addServiceClass('logger', '\tapeet\queue\MockLogger');
		$services->addServiceClass('sleeper', '\tapeet\queue\MockSleeper');

		$services->addService(
				'configuration'
				, array(
						'application_package' => '\tapeet\queue'
						, 'queue_consumer_id' => self::CONSUMER_ID
					)
			);

		$connection = new mysqli(
				'mysql'
				, 'queue_test'
				, 'foo'
				, 'queue_test'
			);
		$services->addService('connection', $connection);
		$this->connection = $connection;

		$services->addService('handlerChainFilters', array('\tapeet\queue\util\HandlerFilter'));

		$queue = new IOCProxy('\tapeet\queue\Queue');
		$queue->id = 'test';
		$queue->schema = 'queue_test';
		$services->addService('queue', $queue);
		$this->queue = $queue;
	}


	function tearDown() {
		$this->connection->query('delete from queue_test.event');
		$this->connection->close();
	}


	function testRun() {
		$this->assertNull($this->queue->poll(self::CONSUMER_ID));

		$this->queue->add(new Event('MockHandler', json_encode(array('test' => 'hello'))));

		$consumerFilter = new IOCProxy('\tapeet\queue\util\ConsumerFilter');
		try {
			$consumerFilter->doFilter(new FilterChain());
		} catch (InterruptedException $ignored) {}

		$services = ServiceLocator::getServiceLocator();
		$handler = $services->getService('handler');
		$this->assertEquals($handler->test, 'hello');
		$this->assertTrue($handler->eventTriggered);
	}

}
