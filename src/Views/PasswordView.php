<?php
// Страница просмотра паролей

namespace Pockit\Views;

class PasswordView extends LayoutView {

    public function customScripts() { ?>

<script src="/js/passwordsView.js"></script>
	
	<?php } public function content() : void { ?>

<div class='card m-1'>
	<h1 class='text-center card-title'>Менеджер паролей</h1>
    <div id='loading' class='loader'></div>
	<div id='lvPasswords'></div>
	<button id='btnAddPassword' class='m-1 btn success w-100'>Добавить</button>
</div>

    <?php }}
