<?php
namespace Pockit\AutoGostSections;

// Секция титульного листа для практики

use Pockit\Views\AutoGostPages\AutoGostPracticeTitlePage;

class PracticeTitleSection extends TitleSection {

    public function __construct() {
        $this->pages[] = new AutoGostPracticeTitlePage();
    }

    protected function beforeOutput() {
        echo "<section class='practiceTitlePage titlepage'>";
    }
    
}