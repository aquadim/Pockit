<?php
namespace Pockit\AutoGostSections;

// Секция титульного листа для практики

use Pockit\Views\AutoGostPages\AutoGostPracticeTitlePage;

class PracticeTitleSection extends TitleSection {

    protected function addFirstPage($current_page) {
        $this->pages[] = new AutoGostPracticeTitlePage();
    }

    protected function beforeOutput() {
        echo "<section class='practiceTitlePage titlepage'>";
    }
    
}