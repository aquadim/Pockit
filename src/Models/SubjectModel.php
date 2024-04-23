<?php
namespace Pockit\Models;

// Модель предмета

use Pockit\Common\Database;

class SubjectModel extends Model {
	protected static $table_name = "regen_subjects";

	// Создаёт запись в таблице
	public static function create($name, $code, $teacher_id, $my_name) : int {
		$db = Database::getConnection();
		$stm = $db->prepare("INSERT INTO ".static::$table_name." (name, code, teacher_id, my_name) VALUES (:name, :code, :teacher_id, :my_name)");
		$stm->bindValue(":name", $name);
		$stm->bindValue(":code", $code);
		$stm->bindValue(":teacher_id", $teacher_id);
		$stm->bindValue(":my_name", $my_name);
		$stm->execute();
		return $db->lastInsertRowID();
	}

	// Обновляет запись в таблице
	public static function update($subject) {
		$db = Database::getConnection();
		$stm = $db->prepare("UPDATE ".static::$table_name." SET name=:name,code=:code,teacher_id=:teacher_id,my_name=:my_name WHERE id=:id");
		$stm->bindValue(":id", $subject['id']);
		$stm->bindValue(":name", $subject['name']);
		$stm->bindValue(":code", $subject['code']);
		$stm->bindValue(":teacher_id", $subject['teacher_id']);
		$stm->bindValue(":my_name", $subject['my_name']);
		$stm->execute();
	}
}
