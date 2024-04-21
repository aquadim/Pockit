<?php
namespace Pockit\Controllers;

// Контроллер страницы паролей

use Pockit\Views\LinkView;
use Pockit\Models\LinkModel;

class LinksController {

	// Главная домашняя страница
	public static function index() {
		$links = LinkModel::all();
		$json_links = [];
		while ($row = $links->fetchArray()) {
			$json_links[] = [
				"name" => $row['name'],
				"href" => $row['href'],
				"id" => $row['id']
			];
		}
		
		$view = new LinkView([
			'passwords' => $json_links,
			"page_title"=>"Полезные ссылки",
			"crumbs" => ["Главная" => "/", "Полезные ссылки" => "/links"]
		]);
		$view->view();
	}
}
