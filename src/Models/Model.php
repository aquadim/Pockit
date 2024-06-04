<?php
namespace Pockit\Models;

// Класс модели

use Pockit\Common\Database;

class Model {
	protected static $table_name;

	// Возвращает одну запись, найденную по id
	public static function getById($id) {
		$db = Database::getConnection();
		$stm = $db->prepare("SELECT * FROM ".static::$table_name." WHERE id=:id");
		$stm->bindValue(":id", $id, SQLITE3_INTEGER);
		return $stm->execute()->fetchArray();
	}

	// Собирает все записи из таблицы
	public static function all($exclude_hidden=false) {
		$db = Database::getConnection();
		$query = "SELECT * FROM ".static::$table_name;
		if ($exclude_hidden) {
			$query .= " WHERE hidden=0";
		}
		return $db->query($query);
	}

	// Собирает записи по условию
	public static function where($field, $value, $exclude_hidden=false) {
		$db = Database::getConnection();

		$query = "SELECT * FROM ".static::$table_name." WHERE $field=:$field";
		if ($exclude_hidden) {
			$query .= " AND hidden=0";
		}
		$stm = $db->prepare($query);
		if (!$stm) {
			// Скорее всего такого поля нет
			return false;
		}
		$stm->bindValue(":$field", $value);
		return $stm->execute();
	}

	// Удаляет запись по ID
	public static function deleteById($id) {
		$db = Database::getConnection();
		$stm = $db->prepare("DELETE FROM ".static::$table_name." WHERE id=:id");
		$stm->bindValue(":id", $id);
		$stm->execute();
	}

	// "Прячет" запись по ID. В таблице обязательно должно быть поле hidden
	public static function hideById($id) {
		$db = Database::getConnection();
		$stm = $db->prepare("UPDATE ".static::$table_name." SET hidden=1 WHERE id=:id");
		$stm->bindValue(":id", $id);
		$stm->execute();
	}

	// Возвращает количество всех записей в таблице
	public static function countAll() {
		$db = Database::getConnection();
		$stm = $db->prepare("SELECT COUNT(*) FROM ".static::$table_name);
		$result = $stm->execute();
		$set = $result->fetchArray();
		return $set[0];
	}
}