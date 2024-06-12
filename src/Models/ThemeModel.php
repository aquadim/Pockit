<?php
// Модель отчёта

namespace Pockit\Models;

use Pockit\Common\Database;

class ThemeModel extends Model {
    protected static $table_name = "themes";
    
    // Создаёт запись в таблице
    public static function create($name, $author, $css) {
        $db = Database::getConnection();
        $stm = $db->prepare(
        "INSERT INTO ".static::$table_name.
        " (name, author, css)".
        " VALUES (:name, :author, :css)");

        $stm->bindValue(":name", $name);
        $stm->bindValue(":author", $author);
        $stm->bindValue(":css", $css);
        $stm->execute();
        return $db->lastInsertRowID();
    }

    // Обновляет запись в таблице
    public static function update($theme) {
        $db = Database::getConnection();
        $stm = $db->prepare(
        "UPDATE ".static::$table_name.
        " SET name=:name, author=:author, css=:css".
        " WHERE id=:id");

        $stm->bindValue(":id", 		$theme['id']);
        $stm->bindValue(":name", 	$theme['name']);
        $stm->bindValue(":author", 	$theme['author']);
        $stm->bindValue(":css", 	$theme['css']);
        $stm->execute();
    }
}
