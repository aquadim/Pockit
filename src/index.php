<?php
// Главный файл

require_once "config.php";
require_once "pockit.php";

// Автозагрузка
spl_autoload_register("Pockit::autoload");

// Определение маршрутов
$router = new Router();

// Главная
$router->register('', 'HomeController::index');

// Оценки
$router->register('/grades', 'GradesController::index');
$router->register('/grades/get', 'GradesController::collect');

// Автогост
$router->register('/autogost/new', 'AutoGostController::newReport');
$router->register('/autogost/upload-image', 'AutoGostController::uploadImage');
$router->register('/autogost/edit/{report_id}', 'AutoGostController::edit');
$router->register("/autogost/gethtml", 'AutoGostController::getHtml');
$router->register("/autogost/archive", 'AutoGostController::archive');
$router->register("/autogost/archive/{subject_id}", 'AutoGostController::listReports');

// API
$router->register('/subjects/create', 'ApiController::createSubject');
$router->register('/subjects/update', 'ApiController::updateSubject');
$router->register('/subjects/delete', 'ApiController::deleteSubject');
$router->register('/reports/update', 'ApiController::updateReport');
$router->register('/reports/delete', 'ApiController::deleteReport');
$router->register('/teachers/read', 'ApiController::getTeachers');
$router->register('/work_types/read', 'ApiController::getWorkTypes');

$router->register404('NotFoundController::index');

return $router->handle($_SERVER['REQUEST_URI']);
