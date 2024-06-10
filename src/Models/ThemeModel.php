<?php
// Модель отчёта

namespace Pockit\Models;

use Pockit\Common\Database;

class ThemeModel extends Model {
	protected static $table_name = "themes";
	
	// Создаёт запись в таблице
	public static function create($name, $author, $col_bg, $col_fg, $col_accent) {
		$db = Database::getConnection();
		$stm = $db->prepare(
        "INSERT INTO ".static::$table_name.
        " (name, author, col_bg, col_fg, col_accent)".
        " VALUES (:name, :author, :col_bg, :col_fg, :col_accent)");

        $stm->bindValue(":name", $name);
		$stm->bindValue(":author", $author);
		$stm->bindValue(":col_bg", $col_bg);
		$stm->bindValue(":col_fg", $col_fg);
		$stm->bindValue(":col_accent", $col_accent);
		$stm->execute();
		return $db->lastInsertRowID();
	}

	// Обновляет запись в таблице
	public static function update($theme) {
		$db = Database::getConnection();
        $stm = $db->prepare(
        "UPDATE ".static::$table_name.
        " SET name=:name, author=:author, col_bg=:col_bg, col_fg=:col_fg, col_accent=:col_accent".
        " WHERE id=:id");

        $stm->bindValue(":id", $theme['id']);
        $stm->bindValue(":name", $theme['name']);
		$stm->bindValue(":author", $theme['author']);
		$stm->bindValue(":col_bg", $theme['col_bg']);
		$stm->bindValue(":col_fg", $theme['col_fg']);
		$stm->bindValue(":col_accent", $theme['col_accent']);
		$stm->execute();
	}
}
