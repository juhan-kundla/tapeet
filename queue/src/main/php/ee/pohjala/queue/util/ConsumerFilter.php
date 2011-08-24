<?php
namespace tapeet\queue\util;


use tapeet\web\Filter;
use tapeet\web\ioc\IOCProxy;


class ConsumerFilter implements Filter {


	/** @Configuration('queue_consumer_id') */
	public $consumerId;


	function doFilter($chain) {
		$consumer = new IOCProxy('tapeet\queue\Consumer');
		$consumer->id = $this->consumerId;
		$consumer->run();
		$chain->doFilter();
	}

}
?>