<?php
namespace Pockit\Views;

// Главная страница приложения

class HomeView extends LayoutView {
	protected $page_title = "Дом";
	protected $welcome_text;
	protected $bg;

	protected function customHead() { ?>

<link rel="stylesheet" href="/css/home.css"/>
<link rel="preload" href="/img/home/<?= $this->bg ?>" as="image"/>

<?php }
	
	public function content() : void { ?>

<div id="bg"></div>
<span style='position:absolute;top:0px;right:0px;'>
	<a style='color:currentColor' href='/settings'>Настройки</a>
</span>

<div class='text-center' style='color: white; text-shadow: black 2px 2px; margin-top: 5rem;'>
	<h1><?= $this->welcome_text ?></h1>
	<h3>Выбери действие</h3>

	<div class='actions'>

		<div class='action'>
			<img src="/img/actions/newReport.png"/>
			<a href="/autogost/new">Создать отчёт<span class='stretched-link'></span></a>
		</div>

		<div class='action'>
			<img src="/img/actions/archive.png"/>
			<a href="/autogost/archive">Архив отчётов<span class='stretched-link'></span></a>
		</div>

		<div class='action'>
			<img src="/img/actions/star.png"/>
			<a href="/grades">Оценки<span class='stretched-link'></span></a>
		</div>

		<div class='action'>
			<img src="/img/actions/safe.png">
			<a href="/passwords">Менеджер паролей<span class='stretched-link'></span></a>
		</div>

		<div class='action'>
			<img src="/img/actions/redBook.png">
			<a href="/links">Полезные ссылки<span class='stretched-link'></span></a>
		</div>
		
		<div class='action'>
			<img src="/img/actions/info.png">
			<a href="/about">О программе<span class='stretched-link'></span></a>
		</div>
		
	</div>
</div>
		
<?php }
}
