<?php
namespace Pockit\Models;

// Модель отчёта

use Pockit\Common\Database;

class LinkModel extends Model {
	protected static $table_name = "links";
	
	// Создаёт запись в таблице
	public static function create($name, $href) {
		$db = Database::getConnection();
		$stm = $db->prepare("INSERT INTO ".static::$table_name." (name, href) VALUES (:name, :href)");
		$stm->bindValue(":name", $name);
		$stm->bindValue(":href", $href);
		$stm->execute();
		return $db->lastInsertRowID();
	}

	// Обновляет запись в таблице
	public static function update($link) {
		$db = Database::getConnection();
		$stm = $db->prepare("UPDATE ".static::$table_name." SET name=:name,href=:href WHERE id=:id");
		$stm->bindValue(":id", $link['id']);
		$stm->bindValue(":name", $link['name']);
		$stm->bindValue(":href", $link['href']);
		$stm->execute();
	}
}
