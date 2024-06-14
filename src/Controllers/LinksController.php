<?php
// Контроллер страницы ссылок

namespace Pockit\Controllers;

use Pockit\Views\LinkView;

class LinksController {
	// Главная домашняя страница
	public static function index() {
		$view = new LinkView([
			"page_title"=>"Полезные ссылки",
			"crumbs" => ["Главная" => "/", "Полезные ссылки" => ""]
		]);
		$view->view();
	}
}
