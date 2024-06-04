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
$router->register("/autogost/jshtml/{report_id}", 'Pockit\Controllers\AutoGostController::jsHTML');

// Пароли
$router->register("/passwords", "Pockit\Controllers\PasswordController::index");
$router->register("/passwords/decrypt", "Pockit\Controllers\PasswordController::getPassword");

// Закладки
$router->register("/links", "Pockit\Controllers\LinksController::index");

// API
$router->register('/subjects/create', 'Pockit\Controllers\ApiController::createSubject');
$router->register('/subjects/update', 'Pockit\Controllers\ApiController::updateSubject');
$router->register('/subjects/delete', 'Pockit\Controllers\ApiController::deleteSubject');

$router->register('/reports/get', 'Pockit\Controllers\ApiController::getReport');
$router->register('/reports/update', 'Pockit\Controllers\ApiController::updateReport');
$router->register('/reports/delete', 'Pockit\Controllers\ApiController::deleteReport');
$router->register('/reports/updateMarkup', 'Pockit\Controllers\ApiController::updateReportMarkup');

$router->register('/teachers/read', 'Pockit\Controllers\ApiController::getTeachers');
$router->register('/work_types/read', 'Pockit\Controllers\ApiController::getWorkTypes');
$router->register('/passwords/create', 'Pockit\Controllers\ApiController::createPassword');
$router->register('/passwords/delete', 'Pockit\Controllers\ApiController::deletePassword');
$router->register('/links/create', 'Pockit\Controllers\ApiController::createLink');
$router->register('/links/update', 'Pockit\Controllers\ApiController::updateLink');
$router->register('/links/delete', 'Pockit\Controllers\ApiController::deleteLink');

$router->register404('Pockit\Controllers\NotFoundController::index');

return $router->handle($_SERVER['REQUEST_URI']);
