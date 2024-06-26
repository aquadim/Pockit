<?php
namespace Pockit\Views\AutoGostPages;

// Автогост: лист с большой рамкой

class BigFramePage extends AutoGostPage {

    protected $components;
    protected $framename;
    protected $page_count; // Количество страниц в разделе

    public function setPageCount($page_count) {
        $this->page_count = $page_count;
    }

    public function addComponent($HTML) {
        $this->components .= $HTML;
    }

    public function view() : void { ?>

<div class='page'>
    <!--Статичные компоненты-->
    <span class="co"><?= static::$work_code ?></span>
    <span class="iz">Изм.</span>
    <span class="ls">Лист</span>
    <span class="nd">№ докум.</span>
    <span class="pd">Подпись</span>
    <span class="dt">Дата</span>
    <span class="rz">Разраб.</span>
    <span class="sr"><?= static::$author_surname ?></span>
    <span class="lt">Лит.</span>
    <span class="pl">Лист</span>
    <span class="al">Листов</span>
    <span class="pr">Провер.</span>
    <span class="st"><?= static::$teacher_surname ?></span>
    <span class="cp">1</span>
    <span class="pc"><?= $this->page_count ?></span>
    <div class='nm'>
        <div><?= $this->framename ?></div>
    </div>
    <span class="gr">ВПМТ <?= static::$author_group ?></span>
    <span class="nc">Н. Контр.</span>
    <span class="ut">Утверд.</span>
    <p class='t-center section-heading'><?= $this->framename ?></p>
    <br>
    <!--//Статичные компоненты-->

    <!--Динамичные компоненты-->
    <?= $this->components ?>
    <!--//Динамичные компоненты-->
</div>

    <?php }

}