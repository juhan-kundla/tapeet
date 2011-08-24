<?php
namespace tapeet\web\util;


use Exception;
use tapeet\web\Filter;
use mysqli;


class ConnectionFilter implements Filter {


	/** @Configuration('data_mysql_database') */
	public $database;
	/** @Configuration('data_mysql_host') */
	public $host;
	/** @Service */
	public $logger;
	/** @Configuration('data_mysql_password') */
	public $password;
	/** @ServiceLocator */
	public $serviceLocator;
	/** @Configuration('data_mysql_username') */
	public $username;


	public function doFilter($chain) {
		$connection = new mysqli(
				$this->host,
				$this->username,
				$this->password,
				$this->database
			);
		try {
			$connection->set_charset("utf8");
			$connection->autocommit(false);

			$this->serviceLocator->addService('connection', $connection);

			$chain->doFilter();

			$connection->commit();
			$connection->close();
		} catch (Exception $e) {
			try {
				$connection->rollback();
				$this->logger->warning('Exception caught, rolling back transaction');
			} catch (Exception $rollbackException) {
				try {
					$this->logger->err('Rollback failed: ' . $rollbackException);
				} catch (Exception $ignored) {}
			}
			$connection->close();
			throw $e;
		}
	}


}
?>