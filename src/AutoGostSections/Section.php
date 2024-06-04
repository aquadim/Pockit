<?php
namespace Pockit\AutoGostSections;

// Секция автогоста

abstract class Section {
    // Страницы секции
    protected $pages;

    protected function addFirstPage() {}

    public function pageBreak() {}

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