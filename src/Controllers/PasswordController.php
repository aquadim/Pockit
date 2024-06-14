<?php
// Контроллер паролей

namespace Pockit\Controllers;

use Pockit\Views\PasswordView;
use Pockit\Models\Password;
use Pockit\Common\Database;

class PasswordController {

	// Главная домашняя страница
	public static function index() {
		$view = new PasswordView([
			"page_title"=>"Менеджер паролей",
			"crumbs" => ["Главная" => "/", "Менеджер паролей" => ""]
		]);
		$view->view();
	}

	public static function getPassword($password_id) {
        $em = Database::getEm();
        $password = $em->find(Password::class, $password_id);
        $decrypted = openssl_decrypt(
			$password->getValue(),
			'aes-128-cbc',
            $_POST['secretKey'],
			$options=0,
			$password->getIv()
		);

		if ($decrypted == false) {
            $ok = false;
			$output = "Неверный секретный ключ";
		} else {
            $ok = true;
			$output = $decrypted;
		}
		echo json_encode(['ok'=>$ok, 'output' => $output]);
	}
}
