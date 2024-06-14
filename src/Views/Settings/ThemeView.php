<?php
// Страница настроек тем

namespace Pockit\Views\Settings;

use Pockit\Views\LayoutView;

class ThemeView extends LayoutView {

    protected $themes;

	public function customScripts() { ?>
<script src='/js/themesView.js'></script>
	
	<?php } public function content():void { ?>

<div class='card m-1'>
	<h1 class='text-center card-title'>Темы</h1>
    <div id='loading' class='loader'></div>
    <div id='lvThemes'></div>
	<button id='btnAddTheme' class='btn success w-100'>Добавить тему</button>
</div>

<?php }
}
