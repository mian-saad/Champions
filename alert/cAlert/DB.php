<?php

namespace Cover;

class DB {


	private
		$DBhost = 'localhost',
		$DBName = '',
		$DBUser = '',
		$DBPassword = '',
		$connection;

	private static $instance = null;

	public function __construct() {

		$dsn = "mysql:host=$this->DBhost;dbname=$this->DBName";

		try {
			$this->connection = new \PDO( $dsn, $this->DBUser, $this->DBPassword );
		} catch ( \PDOException $e ) {
			die( 'Connection failed: ' . $e->getMessage() );
		}

	}


	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new DB();
		}

		return self::$instance;
	}

	// Magic method clone is empty to prevent duplication of connection
	private function __clone() {
	}

	/**
	 * @return \PDO
	 */
	public function getConnection() {
		return $this->connection;
	}

}