<?php
// Отображает сообщения "не найдено"

class NotFoundController {
	// Главная страница
	public function index() {
		$view = new NotFoundView();
		$view->view();
	}
}
