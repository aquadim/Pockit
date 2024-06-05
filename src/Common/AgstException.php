<?php
namespace Pockit\Common;

// Ошибка в разметке отчёта

class AgstException extends \Exception {

    protected string $messageForUser;
    protected int $line_num;
	
	public function __construct(string $message, int $line_num) {
        $this->messageForUser = $message;
        $this->line_num = $line_num;
		parent::__construct($message, 0, null);
	}

	public function __toString() {
        return 'строка '.$this->line_num.': '.$this->message;
    }

    public function getErrorLine() {
        return $this->line_num;
    }

    public function getUserMessage() {
        return $this->messageForUser;
    }

    
}