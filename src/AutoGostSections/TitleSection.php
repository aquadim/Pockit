<?php
namespace Pockit\AutoGostSections;

// Секция титульного листа

use Pockit\Views\AutoGostPages\AutoGostTitlePage;

class TitleSection extends Section {

    public function __construct() {
        $this->pages[] = new AutoGostTitlePage();
    }

    public function pageBreak() {
        throw new \Exception("Page breaks in TitleSection are not allowed.");
    }

    public function addHTML($HTML) {
        throw new \Exception("Adding HTML in TitleSection is not allowed.");
    }

    protected function beforeOutput() {
        echo "<section class='titlepage'>";
    }
    
}