<?php
namespace Pockit\AutoGostSections;

// Секция титульного листа

use Pockit\Views\AutoGostPages\BigFramePage;
use Pockit\Views\AutoGostPages\SmallFramePage;

class SubSection extends Section {

    private string $name;
    private string $id;
    private int $page_count;

    public function __construct($name) {
        $this->name = $name;
        $this->id = uniqid();
        $this->addFirstPage(1);
        $this->page_count = 1;
    }

    protected function addFirstPage() {
        $this->pages[] = new BigFramePage([
            'framename' => $this->name
        ]);
    }

    public function pageBreak($current_line) {
        $this->page_count++;
        $this->pages[] = new SmallFramePage([
            'current_page' => $this->page_count
        ]);
    }

    protected function beforeOutput() {
        $this->pages[0]->setPageCount($this->page_count);
        echo "<section class='subsection' id='section".$this->id."'>";
    }
    
}