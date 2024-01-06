<?php
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
	public static function all() {
		$db = Database::getConnection();
		return $db->query("SELECT * FROM ".static::$table_name);
	}

	// Собирает записи по условию
	public static function where($field, $value) {
		$db = Database::getConnection();
		$stm = $db->prepare("SELECT * FROM ".static::$table_name." WHERE $field=:$field");
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
}