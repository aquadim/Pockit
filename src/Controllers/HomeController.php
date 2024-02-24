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

		if ($now_hour < 6 || $now_hour >= 21) {
			$welcome_text = 'С возвращением';
			$background_image = 'night.jpg';
			$background_color = '#152848';

		} else if ($now_hour < 9) {
			$welcome_text = 'Доброе утро';
			$background_image = "morning.jpg";
			$background_color = "#b6665a";

		} else if ($now_hour < 16) {
			$welcome_text = 'Добрый день';
			$background_image = "day.jpg";
			$background_color = "#3896fa";

		} else {
			$welcome_text = 'Добрый вечер';
			$background_image = "evening.jpg";
			$background_color = "#6690c0";
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
