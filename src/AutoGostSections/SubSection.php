<?php
namespace Pockit\AutoGostSections;

// Секция титульного листа

use Pockit\Views\AutoGostPages\BigFramePage;
use Pockit\Views\AutoGostPages\SmallFramePage;

class SubSection extends Section {

    private string $name;
    private string $id;

    public function __construct($current_page, $name) {
        $this->name = $name;
        $this->id = uniqid();
        $this->addFirstPage($current_page);
    }

    protected function addFirstPage($current_page) {
        $this->pages[] = new BigFramePage([
            'current_page' => $current_page,
            'framename' => 'Основная часть<br>'.$this->name
        ]);
    }

    public function pageBreak($current_page) {
        $this->pages[] = new SmallFramePage([
            'current_page' => $current_page,
            'framename' => $this->name]);
    }

    protected function beforeOutput() {
        echo "<section class='subsection' id='section".$this->id."'>";
    }
    
}