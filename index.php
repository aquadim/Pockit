<?php
// Файл, на который поступают запросы

namespace Pockit;

require_once "src/bootstrap.php";
use Pockit\Common\Router;
use Pockit\Controllers;

// Определение маршрутов
$router = new Router();

// Главная
$router->register('', 'Pockit\Controllers\HomeController::index');
$router->register('/about', 'Pockit\Controllers\HomeController::about');

// Настройка
$router->register('/settings', 'Pockit\Controllers\SettingsController::index');
$router->register('/settings/themes', 'Pockit\Controllers\SettingsController::themes');

// Оценки
$router->register('/grades', 'Pockit\Controllers\GradesController::index');
$router->register('/grades/get', 'Pockit\Controllers\GradesController::collect');

// Автогост
$router->register('/autogost/new', 'Pockit\Controllers\AutoGostController::newReport');
$router->register('/autogost/upload-image', 'Pockit\Controllers\AutoGostController::uploadImage');
$router->register('/autogost/edit/{report_id}', 'Pockit\Controllers\AutoGostController::edit');
$router->register("/autogost/getHtml/{report_id}", 'Pockit\Controllers\AutoGostController::getHtml');
$router->register("/autogost/archive", 'Pockit\Controllers\AutoGostController::archive');
$router->register("/autogost/archive/{subject_id}", 'Pockit\Controllers\AutoGostController::listReports');
$router->register("/autogost/jshtml/{report_id}", 'Pockit\Controllers\AutoGostController::jsHTML');
$router->register("/autogost/help", "Pockit\Controllers\AutoGostController::help");

// Пароли
$router->register("/passwords", "Pockit\Controllers\PasswordController::index");
$router->register("/passwords/decrypt/{password_id}", "Pockit\Controllers\PasswordController::getPassword");

// Закладки
$router->register("/links", "Pockit\Controllers\LinksController::index");

// API
$router->register('/subjects/create', 'Pockit\Controllers\ApiController::createSubject');
$router->register('/subjects/read', 'Pockit\Controllers\ApiController::readSubject');
$router->register('/subjects/update', 'Pockit\Controllers\ApiController::updateSubject');
$router->register('/subjects/delete', 'Pockit\Controllers\ApiController::deleteSubject');

$router->register('/workTypes/read', 'Pockit\Controllers\ApiController::readWorkType');

$router->register('/reports/read/{subject_id}', 'Pockit\Controllers\ApiController::readReport');
$router->register('/reports/update', 'Pockit\Controllers\ApiController::updateReport');
$router->register('/reports/delete', 'Pockit\Controllers\ApiController::deleteReport');
$router->register('/reports/getMarkup/{report_id}', 'Pockit\Controllers\ApiController::getReportMarkup');
$router->register('/reports/updateMarkup/{report_id}', 'Pockit\Controllers\ApiController::updateReportMarkup');

$router->register('/teachers/read', 'Pockit\Controllers\ApiController::getTeachers');
$router->register('/work_types/read', 'Pockit\Controllers\ApiController::getWorkTypes');

$router->register('/passwords/create', 'Pockit\Controllers\ApiController::createPassword');
$router->register('/passwords/read', 'Pockit\Controllers\ApiController::readPassword');
$router->register('/passwords/delete', 'Pockit\Controllers\ApiController::deletePassword');

$router->register('/links/create', 'Pockit\Controllers\ApiController::createLink');
$router->register('/links/read', 'Pockit\Controllers\ApiController::readLink');
$router->register('/links/update', 'Pockit\Controllers\ApiController::updateLink');
$router->register('/links/delete', 'Pockit\Controllers\ApiController::deleteLink');

$router->register('/themes/create', 'Pockit\Controllers\ApiController::addThemeFromZip');
$router->register('/themes/delete', 'Pockit\Controllers\ApiController::deleteTheme');
$router->register('/themes/activate/{theme_id}', 'Pockit\Controllers\ApiController::activateTheme');

$router->register404('Pockit\Controllers\NotFoundController::index');

return $router->handle($_SERVER['REQUEST_URI']);
