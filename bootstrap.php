<?php

require_once "vendor/autoload.php";

define("pockit_version", 1);
define('index_dir', __DIR__);

define("COLOR_DEFAULT", "");
define("COLOR_YELLOW", "\033[93m");
define("COLOR_RED", "\033[91m");
define("COLOR_GREEN", "\033[92m");
define("COLOR_TERMINATOR", "\033[0m");

// Выводит строку информации и окрашивает её в определённый цвет
function displayMessage($string, $color = COLOR_DEFAULT) : void {
	echo $color.$string.COLOR_TERMINATOR;
}

// Возвращает ввод пользователя
function userInput($prompt) : string {
	displayMessage($prompt, COLOR_DEFAULT);
	return rtrim(fgets(STDIN));
}

$dotenv = \Dotenv\Dotenv::createImmutable(index_dir);
$dotenv->load();