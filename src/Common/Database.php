<?php
namespace Pockit\Common;

// Класс работы с БД

class Database {
	private static $db;
	private $connection;
	
	private function __construct() {
		$this->connection = new \SQLite3(index_dir.'/db.sqlite3');
	}

	function __destruct() {
		$this->connection->close();
	}

	public static function getConnection() {
		if (self::$db == null) {
			self::$db = new Database();
		}
		return self::$db->connection;
	}
}