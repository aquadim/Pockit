<?php
// Главный файл

require_once "config.php";
require_once "pockit.php";

// Автозагрузка
spl_autoload_register("Pockit::autoload");

// Определение маршрутов
$router = new Router();
$router->register('', ['HomeController', 'index']);
$router->register('/grades', ['GradesController', 'index']);
$router->register('/grades/get', ['GradesController', 'collect']);
$router->register404(['NotFoundController', 'index']);

return $router->handle($_SERVER['REQUEST_URI']);
