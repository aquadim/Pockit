<?php
namespace Pockit\Common;

// Класс для роутинга

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
			return false;
		}

		// Ищем подходящий маршрут
		// TODO: кэшировать regex-выражения если приложение не в режиме разработки
		foreach ($this->routes as $route => $callback) {
			// Преобразование маршрута в regex выражение
			$pattern = preg_replace(
				['/\//', '/{(\w+)}/'],
				['\\\/', '(?<$1>\\w+)'],
				$route
			);
			$pattern = '/^'.$pattern.'\/?((?:\?|\&)\w+=\w*)*$/';
			if (preg_match($pattern, $request_uri, $named_groups)) {
				// Поиск именованных групп в $named_groups
				$named_groups = array_filter($named_groups, function($key) {
					return !is_numeric($key);
				}, ARRAY_FILTER_USE_KEY);
				$this->invoke($callback, $named_groups);
			}
		}

		// Маршрут не найден, вызываем 404!
		header("HTTP/1.1 404 Not Found");
		$this->invoke($this->not_found_handler, []);
	}

	// Вызывает функцию, которую определил маршрут
	private function invoke(callable $callback, array $parameters) {
		call_user_func_array($callback, $parameters);
		exit();
	}
}
