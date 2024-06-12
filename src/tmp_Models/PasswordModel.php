<?php
namespace Pockit\Models;

// Модель отчёта

use Pockit\Common\Database;

class PasswordModel extends Model {
	protected static $table_name = "passwords";
	
	// Создаёт запись в таблице
	public static function create($name, $password, $secret) {
		list($encrypted, $iv) = self::encryptPassword($password, $secret);
		
		$db = Database::getConnection();
		$stm = $db->prepare("INSERT INTO ".static::$table_name." (name, value, iv) VALUES (:name, :value, :iv)");
		$stm->bindValue(":name", $name);
		$stm->bindValue(":value", $encrypted);
		$stm->bindValue(":iv", $iv, SQLITE3_BLOB);
		$stm->execute();
		return $db->lastInsertRowID();
	}

	public static function getById($id) {
		$db = Database::getConnection();
		$stm = $db->prepare("SELECT id,name,value FROM ".static::$table_name." WHERE id=:id");
		$stm->bindValue(":id", $id, SQLITE3_INTEGER);
		return $stm->execute()->fetchArray();
	}

	// Получает пароль и строку шифрования, возвращает строку, зашифрованную через aes-128-cbc
	private static function encryptPassword($password, $secret_key) {
		$ivlen = openssl_cipher_iv_length("aes-128-cbc");
		$iv = openssl_random_pseudo_bytes($ivlen);
		$encrypted = openssl_encrypt($password, 'aes-128-cbc', $secret_key, $options=0, $iv);
		return array($encrypted, $iv);
	}

	// Декодирует пароль, используя полученный ключ
	public static function decryptPassword($id, $secret) {
		$db = Database::getConnection();
		$stm = $db->prepare("SELECT * FROM ".static::$table_name." WHERE id=:id");
		$stm->bindValue(":id", $id, SQLITE3_INTEGER);
		$password = $stm->execute()->fetchArray();

		$decrypted = openssl_decrypt(
			$password["value"],
			'aes-128-cbc',
			$secret,
			$options=0,
			$password['iv']
		);

		return $decrypted;
	}
}
