<?php
// Отображает сообщения "не найдено"

class NotFoundController {
	// Главная страница
	public static function index() {
		$view = new NotFoundView();
		$view->view();
	}
}
