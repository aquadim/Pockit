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
}
