<?php
// Контроллер

class Controller {
	public string $request_uri;
	
	// Родительский класс контроллеров
	public function __construct($request_uri = null) {
		$this->request_uri = $request_uri;
	}
}
