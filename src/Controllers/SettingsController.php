<?php
// Контроллер настроек

namespace Pockit\Controllers;

use Pockit\Views\Settings\HomeView;
use Pockit\Views\Settings\ThemeView;

class SettingsController {

	// Главная страница настроек
	public static function index() {
		$view = new HomeView([
            'page_title' => 'Настройки',
			"crumbs" => ["Главная" => "/", "Настройки" => ""]
		]);
		$view->view();
	}

    // Настройки тем
	public static function themes() {
		$view = new ThemeView([
            'page_title' => 'Настройки',
			'crumbs' => [
                'Главная' => '/',
                'Настройки' => "/settings",
                'Темы' => ''
            ]
		]);
		$view->view();
	}
}
