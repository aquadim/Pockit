<?php
// Скрипт миграции

namespace Pockit;
require_once "bootstrap.php";

use Pockit\Common\Database;

// Возвращает версию схемы прошлой БД
function getOldDatabaseVersion() : int {
    $db = Database::getConnection();
    $result = $db->query('SELECT value FROM "pockit" WHERE id=1');
    if ($result === false) {
	// Такой таблицы вообще нет -- версия 0
	return 0;
    }
    $data = $result->fetchArray();
    return intval($data[0]);
}

// Существующая версия:
$existing = getOldDatabaseVersion();

if ($existing == pockit_version) {
    displayMessage('База данных уже самой последней версии', COLOR_GREEN);
    exit(0);
}

$db = Database::getConnection();
// Для каждой новой версии выполнить команды
for ($version = $existing + 1; $version <= pockit_version; $version++) {
    switch ($version) {
	case 1:
	    // В версии 1 были добавлены поля hidden, а так же таблица pockit
	    $db->query('CREATE TABLE "pockit" (
		"id"	INTEGER,
		"value"	TEXT,
		PRIMARY KEY("id"))');
	    $db->query("INSERT INTO 'pockit' VALUES(1, '".pockit_version."')");

	    $db->query('ALTER TABLE regen_reports ADD COLUMN "hidden" INTEGER DEFAULT 0');
	    $db->query('ALTER TABLE passwords ADD COLUMN "hidden" INTEGER DEFAULT 0');
	    $db->query('ALTER TABLE links ADD COLUMN "hidden" INTEGER DEFAULT 0');
	    $db->query('ALTER TABLE regen_subjects ADD COLUMN "hidden" INTEGER DEFAULT 0');
	    break;

	case 2:
	    // Добавлены таблицы тем
	    $db->query('
        CREATE TABLE "themes" (
        "id"	INTEGER,
        "name"	TEXT,
        "author"TEXT,
        "css"	TEXT
        PRIMARY KEY("id")');

	    $db->query('
	    CREATE TABLE "home_images" (
	    "id"	INTEGER,
	    "theme_id"	INTEGER,
	    "ord_num"	INTEGER,
	    "image_b64"	BLOB,
	    "color"	TEXT,
	    FOREIGN KEY("theme_id") REFERENCES "themes"("id"),
	    PRIMARY KEY("id"))');

	    // Тема по умолчанию
	    // TODO

        // переименованы таблицы regen
	    break;
	    
	default:
	    displayMessage('Неизвестная версия: '.$version, COLOR_YELLOW);
    }

    displayMessage("Версия #".$version.": готово", COLOR_GREEN);
}