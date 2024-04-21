<?php
namespace Pockit\Controllers;

// Контроллер страницы паролей

use Pockit\Views\PasswordView;
use Pockit\Models\PasswordModel;

class PasswordController {

	// Главная домашняя страница
	public static function index() {
		$passwords = PasswordModel::all();
		$json_passwords = [];
		while ($row = $passwords->fetchArray()) {
			$json_passwords[] = [
				"name" => $row['name'],
				"value" => $row['value'],
				"id" => $row['id']
			];
		}
		
		$view = new PasswordView([
			'passwords' => $json_passwords,
			"page_title"=>"Менеджер паролей",
			"crumbs" => ["Главная" => "/", "Менеджер паролей" => "/passwords"]
		]);
		$view->view();
	}

	public static function getPassword() {
		$password = PasswordModel::decryptPassword($_POST['id'], $_POST['secret']);

		if ($password === false) {
			$output = "Неверный секретный ключ";
		} else {
			$output = $password;
		}
		echo $output;
	}
}
