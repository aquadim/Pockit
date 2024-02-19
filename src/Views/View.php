<?php
namespace Pockit\Views;

// Родительский класс View

class View {
	public function __construct($data=array()) {
		extract($data, EXTR_REFS);
		foreach ($data as $key=>$value) {
			$this->$key = $value;
		}
	}

	// Выводит в браузер HTML
	public function view() : void {}

	// Возвращает контент как HTML, используя буферизацию вывода
	public function render() : string {
		ob_start();
		$this->view();
		return ob_get_clean();
	}
}
