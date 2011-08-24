<?php
namespace tapeet\web\util;


require_once 'pear/Log/Log.php';
use Log;


/** @Contributor('logger') */
class LoggerContributor {


	/** @Configuration('log_file_path') */
	public $filePath;
	/** @Configuration('log_handler') */
	public $handler;
	/** @Configuration('application') */
	public $ident;
	/** @Configuration('log_level') */
	public $level;


	function contribute() {
		$name = '';
		if ($this->handler == 'file') {
			$name = $this->filePath;
		}

		$lineFormat = '%{timestamp} [%{priority}] %{file}(%{line}) %{message}';
		$timeFormat = '%Y-%m-%d %H:%M:%S';

		$log = new Log('');
		$logger = $log->factory(
				$this->handler,
				$name,
				$this->ident,
				array(
						'lineFormat' => $lineFormat,
						'timeFormat' => $timeFormat
					),
				$this->level
			);
		return $logger;
	}

}
?>