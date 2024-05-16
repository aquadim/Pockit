<?php
namespace Pockit\Controllers;

// Контроллер домашней страницы

use Pockit\Views\HomeView;

class HomeController {

	// Главная домашняя страница
	public static function index() {
		// Определяем текст приветствия
		date_default_timezone_set('Europe/Kirov');
		$now_hour = localtime(time(), true)['tm_hour'];

		$background_image = intval($now_hour / 2).'.jpg';
		$colors = [
			"#14282b",
			"#904147",
			"#d0645f",
			"#d7553f",
			"#b2e5fa",
			"#4e9a3a",
			"#8bbde2",
			"#e9af6b",
			"#1e1c2a",
			"#2175e9",
			"#223c55",
			"#051f36"
		];
		$background_color = $colors[intval($now_hour / 2)];

		if ($now_hour < 6 || $now_hour >= 21) {
			$welcome_text = 'С возвращением';

		} else if ($now_hour < 9) {
			$welcome_text = 'Доброе утро';

		} else if ($now_hour < 16) {
			$welcome_text = 'Добрый день';

		} else {
			$welcome_text = 'Добрый вечер';
		}
		$welcome_text .= ", ".$_ENV['user_name'];

		$view = new HomeView([
			"welcome_text" => $welcome_text,
			"background_image" => "/img/home/".$background_image,
			"background_color"=> $background_color
		]);
		$view->view();
	}
}
