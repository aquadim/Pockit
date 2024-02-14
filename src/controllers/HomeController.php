<?php
// Контроллер домашней страницы

class HomeController extends Controller {

	// Главная домашняя страница
	public static function index() {
		// Определяем текст приветствия
		$now_hour = localtime(time(), true)['tm_hour'];
		if ($now_hour < 6 || $now_hour > 20) {
			$welcome_text = 'С возвращением';
		} else if ($now_hour < 9) {
			$welcome_text = 'Доброе утро';
		} else if ($now_hour < 16) {
			$welcome_text = 'Добрый день';
		} else {
			$welcome_text = 'Добрый вечер';
		}
		$welcome_text .= ", ".user_name;

		$view = new HomeView([
			"welcome_text" => $welcome_text,
			"page_title" => "Pockit"
		]);
		$view->view();
	}
}
