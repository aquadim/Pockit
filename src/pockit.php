<?php

// Функции, необходимые везде
class Pockit {
	public static function autoload($classname) {
		if (preg_match('/Controller$/', $classname)) {
			require_once rootdir.'/controllers/'.$classname.'.php';
		} else if (preg_match('/View$/', $classname)) {
			require_once rootdir.'/views/'.$classname.'.php';
		} else if (preg_match('/Model$/', $classname)) {
			require_once rootdir.'/models/'.$classname.'.php';
		}
	}
}

class Router {
	private $routes = array();
	private $not_found_handler;

	// Регистрирует маршрут
	public function register(string $uri, callable $handler) {
		$this->routes[$uri] = $handler;
	}

	// Регистрирует маршрут для 404
	public function register404(callable $handler) {
		$this->not_found_handler = $handler;
	}

	// Выполняет маршрутизацию
	public function handle(string $request_uri) {
		if (preg_match('/^\/(?:css|fonts|img|jquery|js)\//', $request_uri)) {
			// Подача как есть
			header("Cache-Control: public, max-age=3600");
			return false;
		}

		// Ищем подходящий маршрут
		foreach ($this->routes as $route => $callback) {
			$pattern = '/^'.str_replace('/', '\/', $route).'\/?((?:\?|\&)\w+=\w*)*$/';
			if (preg_match($pattern, $request_uri)) {
				$this->invoke($callback, $request_uri);
			}
		}

		// Маршрут не найден, вызываем 404!
		header("HTTP/1.1 404 Not Found");
		$this->invoke($this->not_found_handler, $request_uri);
	}

	// Вызывает функцию, которую определил маршрут
	private function invoke(callable $callback, string $request_uri) {
		list($handler, $handle_method) = $callback;
		$h = new $handler($request_uri);
		$h->$handle_method();
		exit();
	}
}

// Класс работы с БД
class Database {
    private static $db;
    private $connection;
    
    private function __construct() {
        $this->connection = new SQLite3(rootdir.'/db.sqlite3');
    }

    function __destruct() {
        $this->connection->close();
    }

    public static function getConnection() {
        if (self::$db == null) {
            self::$db = new Database();
        }
        return self::$db->connection;
    }
}
