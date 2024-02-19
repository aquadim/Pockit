<?php
namespace Pockit\Controllers;

// Контроллер оценок

use Pockit\Views\GradesView;

class GradesController {

	// Оценки
	public static function index() {
		$view = new GradesView([
			"page_title"=>"Оценки",
			"crumbs" => ["Главная" => "/", "Оценки" => "/grades"]
		]);
		$view->view();
	}

	// Сбор оценок
	public static function collect() {
		
		// Создаём разделяемый обработчик
		$sh = curl_share_init();
		curl_share_setopt($sh, CURLSHOPT_SHARE, CURL_LOCK_DATA_COOKIE); // Делимся куками

		// Подаём запрос в электронный дневник на авторизацию
		$auth = curl_init('http://vpmt.ru:8081/region_pou/region.cgi/login');
		curl_setopt($auth, CURLOPT_COOKIEFILE, "");
		curl_setopt($auth, CURLOPT_SHARE, $sh);
		curl_setopt($auth, CURLOPT_POST, 1);
		curl_setopt($auth, CURLOPT_POSTFIELDS, 'username='.$_ENV['journal_login'].'&userpass='.sha1($_ENV['journal_password']));
		curl_setopt($auth, CURLOPT_ENCODING, 'windows-1251');
		curl_setopt($auth, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($auth);

		// Запрос на экспорт оценок
		$grades = curl_init('http://vpmt.ru:8081/region_pou/region.cgi/journal_och?page=1&marks=1&period_id='.$_ENV['period_id'].'&export=1');
		curl_setopt($grades, CURLOPT_COOKIEFILE, "");
		curl_setopt($grades, CURLOPT_SHARE, $sh);
		curl_setopt($grades, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($grades);

		// Разрыв сессии с журналом
		$logout = curl_init('http://vpmt.ru:8081/region_pou/region.cgi/logout');
		curl_setopt($logout, CURLOPT_COOKIEFILE, "");
		curl_setopt($logout, CURLOPT_SHARE, $sh);
		curl_setopt($logout, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($logout);

		// Парсинг экспортного XML
		// Данные хранятся в строках с тэгом Row
		// Первые 3 не содержат оценок, их пропускаем
		// Последний ряд тоже не содержит оценок, его не обрабатываем
		// Если XML загрузить не удаётся, то **скорее всего** логин и пароль неверны
		$doc = new \DOMDocument();
		$doc->loadXML($data);
		$rows = $doc->getElementsByTagName("Row");

		$output = [];
		for ($y = 4; $y < count($rows) - 1; $y++) {
			$output_row = [];
			$children = $rows[$y]->childNodes;
			// Children - дочерние узлы тэга Row
			// Переносы строк в документе считаются узлом текста, поэтому
			// [0] - текстовый узел
			// [1] - содержит название дисциплины
			// [2] - текстовый узел
			// [3] - содержит оценки
			// [4] - текстовый узел
			// [5] - средний балл
			// [6] - текстовый узел
			$output[] = [
				trim($children[1]->nodeValue),
				trim($children[3]->nodeValue),
				trim($children[5]->nodeValue)
			];
		}

		// Закрываем сессии curl
		curl_share_close($sh);
		curl_close($auth);
		curl_close($grades);

		echo json_encode($output);
	}
}
