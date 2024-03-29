<?php

namespace Pockit;

// Файл, на который поступают запросы
require_once "vendor/autoload.php";
use Pockit\Common\Router;

define('index_dir', __DIR__);

$dotenv = \Dotenv\Dotenv::createImmutable(index_dir);
$dotenv->load();

// Определение маршрутов
$router = new Router();

// Главная
$router->register('', 'Pockit\Controllers\HomeController::index');

// Оценки
$router->register('/grades', 'Pockit\Controllers\GradesController::index');
$router->register('/grades/get', 'Pockit\Controllers\GradesController::collect');

// Автогост
$router->register('/autogost/new', 'Pockit\Controllers\AutoGostController::newReport');
$router->register('/autogost/upload-image', 'Pockit\Controllers\AutoGostController::uploadImage');
$router->register('/autogost/edit/{report_id}', 'Pockit\Controllers\AutoGostController::edit');
$router->register("/autogost/gethtml", 'Pockit\Controllers\AutoGostController::getHtml');
$router->register("/autogost/archive", 'Pockit\Controllers\AutoGostController::archive');
$router->register("/autogost/archive/{subject_id}", 'Pockit\Controllers\AutoGostController::listReports');

// API
$router->register('/subjects/create', 'Pockit\Controllers\ApiController::createSubject');
$router->register('/subjects/update', 'Pockit\Controllers\ApiController::updateSubject');
$router->register('/subjects/delete', 'Pockit\Controllers\ApiController::deleteSubject');
$router->register('/reports/update', 'Pockit\Controllers\ApiController::updateReport');
$router->register('/reports/delete', 'Pockit\Controllers\ApiController::deleteReport');
$router->register('/teachers/read', 'Pockit\Controllers\ApiController::getTeachers');
$router->register('/work_types/read', 'Pockit\Controllers\ApiController::getWorkTypes');

$router->register404('Pockit\Controllers\NotFoundController::index');

return $router->handle($_SERVER['REQUEST_URI']);
