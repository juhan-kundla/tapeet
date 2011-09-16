<?php
namespace tapeet\queue;


require_once 'tapeet/annotation/ServiceLocator.php';

require_once 'tapeet/queue/annotation/Event.php';

use \PHPUnit_Framework_TestCase;

use \tapeet\FilterChain;
use \tapeet\ioc\ContextLoader;
use \tapeet\queue\Event;


class IntegrationTest extends PHPUnit_Framework_TestCase {


	const CONSUMER_ID = 'test';


	private $context;


	function setUp() {
		$this->context = ContextLoader::load('tapeet.yaml');
	}


	function tearDown() {
		$connection = $this->context->get('connection');
		$connection->query('delete from queue_test.event');
		$connection->close();
	}


	function testRun() {
		$queue = $this->context->get("queue");
		$this->assertNull($queue->poll(self::CONSUMER_ID));
		$queue->add(new Event('MockHandler', json_encode(array('test' => 'hello'))));

		$consumerFilter = $this->context->get('consumerFilter');
		try {
			$consumerFilter->doFilter(new FilterChain());
		} catch (InterruptedException $ignored) {}

		$handler = $this->context->get('handler');
		$this->assertEquals($handler->test, 'hello');
		$this->assertTrue($handler->eventTriggered);
	}

}
