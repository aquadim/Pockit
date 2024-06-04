<?php

define("COLOR_DEFAULT", "");
define("COLOR_YELLOW", "\033[93m");
define("COLOR_RED", "\033[91m");
define("COLOR_GREEN", "\033[92m");
define("COLOR_TERMINATOR", "\033[0m");

// Выводит строку информации и окрашивает её в определённый цвет
function displayMessage($string, $color = COLOR_DEFAULT) : void {
	echo $color.$string.COLOR_TERMINATOR;
}

// Создаёт БД
function databaseUp($file_path) {
	$db = new SQLite3($file_path);

	$db->query('CREATE TABLE "regen_reports" (
	"id"			INTEGER,
	"subject_id"	INTEGER,
	"work_type"		INTEGER,
	"work_number"	TEXT,
	"notice"		TEXT,
	"date_create"	DATETIME DEFAULT CURRENT_TIMESTAMP,
	"markup"		TEXT,
	"date_for" 		DATETIME,
	"hidden"		INTEGER DEFAULT 0,
	PRIMARY KEY("id"))');

	$db->query('CREATE TABLE "regen_subjects" (
	"id"			INTEGER,
	"name"			TEXT,
	"code"			TEXT,
	"teacher_id"	INTEGER,
	"my_name"		TEXT,
	"hidden"		INTEGER DEFAULT 0,
	PRIMARY KEY("id"))');

	$db->query('CREATE TABLE "regen_teachers" (
	"id" INTEGER PRIMARY KEY,
	"surname" TEXT,
	"name" TEXT,
	"patronymic" TEXT)');

	$db->query('CREATE TABLE "regen_worktypes"(
	"id" INTEGER PRIMARY KEY,
	"name_nom" TEXT,
	"name_gen" TEXT,
	"name_titlepage" TEXT)');

	$db->query('CREATE TABLE "passwords" (
	"id"		INTEGER PRIMARY KEY,
	"name"		TEXT NOT NULL,
	"value"		TEXT NOT NULL,
	"iv"		BLOB NOT NULL,
	"hidden"	INTEGER DEFAULT 0)');
	
	$db->query('CREATE TABLE "links" (
	"id"		INTEGER,
	"name"		TEXT NOT NULL,
	"href"		TEXT NOT NULL,
	"hidden"	INTEGER DEFAULT 0,
	PRIMARY KEY("id" AUTOINCREMENT))');

	$db->query("INSERT INTO 'regen_teachers' VALUES (1,'Пивоваров','Сергей','Александрович')");
	$db->query("INSERT INTO 'regen_teachers' VALUES (2,'Ильина','Светлана','Анатольевна')");
	$db->query("INSERT INTO 'regen_teachers' VALUES (3,'Галимова','Екатерина','Валерьевна')");
	$db->query("INSERT INTO 'regen_teachers' VALUES (4,'Немтинова','Елена','Александровна')");

	$db->query("INSERT INTO 'regen_worktypes' VALUES (1,'ЛАБОРАТОРНАЯ РАБОТА','ЛАБОРАТОРНОЙ РАБОТЫ','лабораторной работе')");
	$db->query("INSERT INTO 'regen_worktypes' VALUES (2,'ПРАКТИЧЕСКАЯ РАБОТА','ПРАКТИЧЕСКОЙ РАБОТЫ','практической работе')");

	$db->query("INSERT INTO 'regen_subjects' VALUES (1,'Тестовый предмет','МДК.00.01',1,'Тест',0)");
}

// Возвращает ввод пользователя
function userInput($prompt) : string {
	displayMessage($prompt, COLOR_DEFAULT);
	return rtrim(fgets(STDIN));
}

// 1. Создание БД
$db_path = __DIR__."/db.sqlite3";
if (file_exists($db_path)) {
	displayMessage("Файл базы данных уже существует -- пропускаем создание\n", COLOR_YELLOW);
} else {
	displayMessage("Создаём базу данных...\n", COLOR_YELLOW);
	databaseUp($db_path);
	displayMessage("База данных успешно создана!\n", COLOR_GREEN);
}

// 2. Заполнение .env файла
$env_path = __DIR__."/.env";
if (file_exists($env_path)) {

	displayMessage("Файл env уже существует -- пропускаем создание\n", COLOR_YELLOW);

} else {

	$user_name = userInput("Введи твоё имя\n");
	$user_surname = userInput("Введи твою фамилию\n");
	$user_patronymic = userInput("Введи твоё отчество\n");
	$user_group = userInput("Введи группу в которой ты учишься. Например 3ИС\n");
	$user_code = userInput("Введи конец шифра в твоих отчётах. Например .012.09.02.07.000\n");
	$journal_login = userInput("Введи логин от электронного дневника\n");
	$journal_password = userInput("Введи пароль от электронного дневника\n");
	$period_id = userInput("Введи текущий period_id\n");

	$full = $user_surname.' '.mb_substr($user_name, 0, 1).'. '.mb_substr($user_patronymic, 0, 1).'.';

	$fp = fopen($env_path, 'w');
	fwrite($fp, "user_name=".$user_name."\n");
	fwrite($fp, "journal_login=".$journal_login."\n");
	fwrite($fp, "journal_password=".$journal_password."\n");
	fwrite($fp, "period_id=".$period_id."\n");
	fwrite($fp, "autogost_surname=".$user_surname."\n");
	fwrite($fp, "autogost_full=".$full."\n");
	fwrite($fp, "autogost_group=".$user_group."\n");
	fwrite($fp, "autogost_code=".$user_code."\n");
	
	fclose($fp);

	displayMessage("Файл настроек успешно создан!\n", COLOR_GREEN);

}
