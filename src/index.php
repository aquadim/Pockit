<?php
// Главный файл

require_once "config.php";
require_once "pockit.php";

// Автозагрузка
spl_autoload_register("Pockit::autoload");

// Определение маршрутов
$router = new Router();

// Главная
$router->register('', ['HomeController', 'index']);

// Оценки
$router->register('/grades', ['GradesController', 'index']);
$router->register('/grades/get', ['GradesController', 'collect']);

// Regen
$router->register('/regen/new', ['RegenController', 'newReport']);
$router->register('/regen/edit/\d+', ['RegenController', 'edit']);
$router->register("/regen/gethtml", ['RegenController', 'getHtml']);
$router->register("/regen/archive", ['RegenController', 'archive']);

// API
$router->register('/subjects/create', ['ApiController', 'createSubject']);
$router->register('/subjects/update', ['ApiController', 'updateSubject']);
$router->register('/subjects/delete', ['ApiController', 'deleteSubject']);
$router->register('/teachers/read', ['ApiController', 'getTeachers']);

$router->register404(['NotFoundController', 'index']);

return $router->handle($_SERVER['REQUEST_URI']);
