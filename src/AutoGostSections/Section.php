<?php
namespace Pockit\AutoGostSections;

// Секция автогоста

abstract class Section {
    // Страницы секции
    protected $pages;

    public function __construct($current_page) {
        $this->addFirstPage($current_page);
    }

    protected function addFirstPage($current_page) {}

    public function pageBreak($current_page) {}

    public function addHTML($HTML) {
        end($this->pages)->addComponent($HTML);
    }

    protected function beforeOutput() {
        echo "<section>";
    }

    protected function afterOutput() {
        echo "</section>";
    }

    public function output() {
        $this->beforeOutput();
        foreach ($this->pages as $page) {
            $page->view();
        }
        $this->afterOutput();
    }
    
}