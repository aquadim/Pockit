<?php
// Контроллер настроек

namespace Pockit\Controllers;

use Pockit\Views\Settings\HomeView;
use Pockit\Views\Settings\ThemeView;
use Pockit\Views\Settings\AutogostView;
use Pockit\Models\ThemeModel;
use Pockit\Common\SettingType;

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
            'page_title' => 'Настройки тем',
			'crumbs' => [
                'Главная' => '/',
                'Настройки' => "/settings",
                'Темы' => ''
            ]
		]);
		$view->view();
	}

    // Настройка автогоста
    public static function autogost() {
        $use_gost_type_b = getSettingValue(SettingType::AgstUseGostTypeB);
        $view = new AutogostView([
            'use_gost_type_b' => $use_gost_type_b
        ]);
        $view->view();
    }
}
