<?php
class SubjectModel extends Model {
	protected static $table_name = "regen_subjects";

	// Создаёт запись в таблице
	public static function create($name, $code, $teacher_id) : int {
		$db = Database::getConnection();
		$stm = $db->prepare("INSERT INTO ".static::$table_name." (name, code, teacher_id) VALUES (:name, :code, :teacher_id)");
		$stm->bindValue(":name", $name);
		$stm->bindValue(":code", $code);
		$stm->bindValue(":teacher_id", $teacher_id);
		$stm->execute();
		return $db->lastInsertRowID();
	}

	// Обновляет запись в таблице
	public static function update($subject) {
		$db = Database::getConnection();
		$stm = $db->prepare("UPDATE ".static::$table_name." SET name=:name,code=:code,teacher_id=:teacher_id WHERE id=:id");
		$stm->bindValue(":id", $subject['id']);
		$stm->bindValue(":name", $subject['name']);
		$stm->bindValue(":code", $subject['code']);
		$stm->bindValue(":teacher_id", $subject['teacher_id']);
		$stm->execute();
	}
}
