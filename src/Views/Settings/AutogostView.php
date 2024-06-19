<?php
// Страница настройки автогоста

namespace Pockit\Views\Settings;

use Pockit\Views\LayoutView;

class AutogostView extends LayoutView {
    protected $page_title = 'Настройки автогоста';
    protected $crumbs = [
        'Главная' => '/',
        'Настройки' => "/settings",
        'Автогост' => ''
    ];
    protected $use_gost_type_b;

    public function customScripts() : void { ?>
<script src='/js/agstSettings.js'></script>
	
	<?php } public function content() : void { ?>

<div class='card m-1'>
	<h1 class='text-center card-title'>Настройки автогоста</h1>

    <form action="/api/saveAgstSettings" method="post" id='formMain'>
        <div class="form-control-container">
			<label for="selFont">Шрифт в отчётах</label>
            <select
                class="form-control"
                id="selFont"
                name="fontId"
                autocomplete="off">

                <option
                    value='0'
                    <?php if (!$this->use_gost_type_b) { ?>selected<?php } ?>>Times New Roman</option>
                <option
                    value='1'
                    <?php if ($this->use_gost_type_b) { ?>selected<?php } ?>>Gost Type B</option>
            </select>
            <p>
                Внимание: при изменении шрифта, существующие отчёты могут
                отображаться неправильно. Возможно понадобится расставить
                маркеры разрыва страниц заново.
            </p>
		</div>

        <button class='btn success w-100'>Сохранить</button>
    </form>
</div>

<?php }
}
