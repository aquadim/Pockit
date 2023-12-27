<?php
class ReportModel extends Model {
	protected static $table_name = "regen_reports";
	
	// Создаёт запись в таблице
	public static function create($subject_id, $work_type, $number, $notice, $start_markup) {
		$db = Database::getConnection();
		$stm = $db->prepare("INSERT INTO ".static::$table_name." (subject_id, work_type, work_number, notice, date_create, markup) VALUES (:subject_id, :work_type, :work_number, :notice, datetime('now', 'localtime'), :markup)");
		$stm->bindValue(":subject_id", $subject_id);
		$stm->bindValue(":work_type", $work_type);
		$stm->bindValue(":work_number", $number);
		$stm->bindValue(":notice", $notice);
		$stm->bindValue(":markup", $start_markup);
		$stm->execute();
		return $db->lastInsertRowID();
	}
}
