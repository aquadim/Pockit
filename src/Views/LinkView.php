<?php
// Страница просмотра ссылок

namespace Pockit\Views;

class LinkView extends LayoutView {
	
	public function customScripts() { ?>

<script src="/js/linksView.js"></script>
	
	<?php } public function content() : void { ?>

<div class='card m-1'>
	<h1 class='text-center card-title'>Полезные ссылки</h1>
    <div id='loading' class='loader'></div>
	<div id='lvLinks'></div>
	<button id='btnAddLink' class='m-1 btn success w-100'>Добавить</button>
</div>

<?php }
}
