<?php
// Контроллер настроек

namespace Pockit\Controllers;

use Pockit\Views\Settings\HomeView;
use Pockit\Views\Settings\ThemeView;
use Pockit\Models\ThemeModel;

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
        $all_themes = ThemeModel::all();
		$view = new ThemeView([
            'page_title' => 'Настройки',
			'crumbs' => [
                'Главная' => '/',
                'Настройки' => "/settings",
                'Темы' => ''
            ],
            'themes' => $all_themes
		]);
		$view->view();
	}
}
