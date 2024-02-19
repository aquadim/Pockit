<?php
namespace Pockit\Views\AutoGostPages;

// Автогост: лист с большой рамкой

class BigFramePage extends AutoGostPage {

    protected $components;
    protected $current_page;
    protected $framename;

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
    <span class="cp"><?= $this->current_page_number ?></span>
    <span class="pc"><?= static::$pages_count ?></span>
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