<?php
namespace Pockit\Controllers;

// Контроллер домашней страницы

use Pockit\Views\HomeView;
use Pockit\Views\AboutView;

class HomeController {

	// Главная домашняя страница
	public static function index() {
		// Определяем текст приветствия
		date_default_timezone_set('Europe/Kirov');
		$now_hour = localtime(time(), true)['tm_hour'];

		$background_image = intval($now_hour / 2).'.jpg';
		$colors = [
			"#1e2744",	// 0,1
			"#232f58",	// 2,3
			"#d0645f",	// 4,5
			"#3b4049",  // 6,7
			"#d38482",  // 8,9
			"#587a81",  // 10,11
			"#787c80",  // 12,13
			"#e9af6b",  // 14,15
			"#79748d",  // 16,17
			"#2175e9",  // 18,19
			"#223c55",  // 20,21
			"#051f36"   // 22,23
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

	// "О программе"
	public static function about() {
		$view = new AboutView([
			"crumbs" => ["Главная" => "/", "О карманном сервере" => ""]
		]);
		$view->view();
	}
}
