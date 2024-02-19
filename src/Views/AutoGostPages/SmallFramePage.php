<?php
namespace Pockit\Views\AutoGostPages;

// Автогост: лист с маленькой рамкой

class SmallFramePage extends AutoGostPage {

    protected $components;
    protected $current_page;

    public function addComponent($HTML) {
        $this->components .= $HTML;
    }

    public function view() : void { ?>

<div class='page'>
    <!--Статичные компоненты-->
    <span class="iz">Изм.</span>
    <span class="ls">Лист</span>
    <span class="nd">№ докум.</span>
    <span class="pd">Подпись</span>
    <span class="dt">Дата</span>
    <span class="co"><?= static::$work_code ?></span>
    <span class="pl">Лист</span>
    <span class="cp"><?= $this->current_page ?></span>
    <!--//Статичные компоненты-->

    <!--Динамичные компоненты-->
    <?= $this->components ?>
    <!--//Динамичные компоненты-->

</div>

    <?php }

}