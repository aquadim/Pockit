<?php
// Файл настроек

// Установка временного пояса по умолчанию (GMT+3)
date_default_timezone_set("Europe/Kirov");

define('rootdir', __DIR__);

require_once rootdir."/vendor/autoload.php";

if (strtoupper(substr(php_uname("s"), 0, 3)) === 'WIN') {
    define("server_os", "windows");
} else {
    define("server_os", "linux");
}

$dotenv = Dotenv\Dotenv::createImmutable(rootdir);
$dotenv->load();

// regen
define("regen_surname", "Королёв");
define("regen_full", "Королёв В.С.");
define("regen_group", "3ИС");
define("regen_code", ".012.09.02.07.000");