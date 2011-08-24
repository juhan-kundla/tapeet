<?php
namespace tapeet\queue\util;


use tapeet\web\Filter;
use tapeet\web\ioc\IOCProxy;


class QueueFilter implements Filter {


	/** @Configuration('queue_id') */
	public $queueId;
	/** @ServiceLocator */
	public $serviceLocator;


	public function doFilter($chain) {
		$queue = new IOCProxy('tapeet\queue\Queue');
		$queue->id = $this->queueId;
		$this->serviceLocator->addService('queue', $queue);

		$chain->doFilter();
	}


}
?>