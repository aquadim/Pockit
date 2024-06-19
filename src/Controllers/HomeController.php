<?php
// Контроллер домашней страницы

namespace Pockit\Controllers;

use Pockit\Common\Database;
use Pockit\Common\SettingType;
use Pockit\Views\HomeView;
use Pockit\Views\AboutView;
use Pockit\Views\WelcomeSetupView;
use Pockit\Models\Theme;

class HomeController {

	// Главная домашняя страница
	public static function index() {

        $completed = getSettingValue(SettingType::WelcomeSetupCompleted);
        if (!$completed) {
            // Первоначальная настройка не выполнена, делаем
            $view = new WelcomeSetupView();
            $view->view();
            return;
        }

        // Получаем задний фон текущей темы
        $em = Database::getEm();
        $current_theme_id = getSettingValue(SettingType::ActiveThemeId);
        $theme = $em->find(Theme::class, $current_theme_id);

        // Получаем имя пользователя
        $user_name = getSettingValue(SettingType::UserName);

        // Определяем текст приветствия
		date_default_timezone_set('Europe/Kirov');
		$now_hour = localtime(time(), true)['tm_hour'];

		if ($now_hour < 6 || $now_hour >= 21) {
			$welcome_text = 'С возвращением';
		} else if ($now_hour < 9) {
			$welcome_text = 'Доброе утро';
		} else if ($now_hour < 16) {
			$welcome_text = 'Добрый день';
		} else {
			$welcome_text = 'Добрый вечер';
		}
		$welcome_text .= ", ".$user_name;        

		$view = new HomeView([
			"welcome_text" => $welcome_text,
            "bg" => $theme->getHomeBgLocation()
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
