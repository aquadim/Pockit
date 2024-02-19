<?php
namespace Pockit\Controllers;

// Отображает сообщения "не найдено"

use Pockit\Views\NotFoundView;

class NotFoundController {
	// Главная страница
	public static function index() {
		$view = new NotFoundView();
		$view->view();
	}
}
