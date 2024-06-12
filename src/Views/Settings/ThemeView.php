<?php
// Страница настроек тем

namespace Pockit\Views\Settings;

use Pockit\Views\LayoutView;

class ThemeView extends LayoutView {

	public function customScripts() { ?>
<script src='/js/themesView.js'></script>
	
	<?php } public function content():void { ?>

<div class='card m-1'>
	<h1 class='text-center card-title'>Темы</h1>
	<button id='btnAddTheme' class='btn success w-100'>Добавить тему</button>
</div>

<?php }
}
