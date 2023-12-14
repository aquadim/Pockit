<?php
// Главная страница приложения

class HomeView extends LayoutView {
	protected $welcome_text;
	public function content():void { ?>

<!--Добро пожаловать-->
<h1><?= $this->welcome_text ?></h1>
<h3>Выбери действие</h3>

<div>
	<div>
		<p><a href="/regen/new">Создать отчёт</a></p>
		<p><a href="/regen/new">Архив отчёт</a></p>
		<p><a href="/grades">Оценки</a></p>
	</div>
</div>

<!--Меню действий-->
<!--
<div id='home-actions'>
	<div class='home-action'>
		<img src="/img/home-actions/file.png">
		<a class='full-link' href="/regen/new" target="_blank">Создать отчёт<span></span></a>
	</div>
	<div class='home-action'>
		<img src="/img/home-actions/archive.png">
		<a class='full-link' href="/regen/archive">Архив отчётов<span></span></a>
	</div>
	<div class='home-action'>
		<img src="/img/home-actions/table.png">
		<a class='full-link' href="/regen/tabgen">Генератор таблиц<span></span></a>
	</div>
	<div class='home-action'>
		<img src="/img/home-actions/settings.png">
		<a class='full-link' href="#">Настройки<span></span></a>
	</div>
	<div class='home-action'>
		<img src="/img/home-actions/lock.png">
		<a class='full-link' href="/passwords">Менеджер паролей<span></span></a>
	</div>
	<div class='home-action'>
		<img src="/img/home-actions/search.png">
		<a class='full-link' href="/knowledge">Учебные материалы<span></span></a>
	</div>
</div>
-->
		
<?php }}
