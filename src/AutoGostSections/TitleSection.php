<?php
namespace Pockit\AutoGostSections;

// Секция титульного листа

use Pockit\Views\AutoGostPages\AutoGostTitlePage;
use Pockit\Common\AgstException;

class TitleSection extends Section {

    public function __construct() {
        $this->pages[] = new AutoGostTitlePage();
    }

    public function pageBreak($current_line) {
        throw new AgstException(
            "Разрывы страниц в титульных листах запрещены",
            $current_line);
    }

    public function addHTML($HTML, $current_line) {
        throw new AgstException(
            "Добавлять что-либо кроме маркеров секций в титульных листах ".
            "запрещено",
            $current_line);
    }

    protected function beforeOutput() {
        echo "<section class='titlepage'>";
    }
    
}