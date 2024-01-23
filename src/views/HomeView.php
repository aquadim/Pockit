<?php
// Главная страница приложения

class HomeView extends LayoutView {
	protected $welcome_text;

	protected function customHead() { ?>
<link rel="stylesheet" href="/css/home.css">
	<?php }
	
	public function content():void { ?>

<div class='text-center'>
	<h1><?= $this->welcome_text ?></h1>
	<h3>Выбери действие</h3>
</div>

<div class='actions' style='width: 50%; margin: auto;'>
	<div class='actions-row'>
		<div class='action'>
			<img src="/img/actions/file.png">
			<a href="/autogost/new">Создать отчёт<span class='stretched-link'></span></a>
		</div>
		<div class='action'>
			<img src="/img/actions/archive.png">
			<a href="/autogost/archive">Архив отчётов<span class='stretched-link'></span></a>
		</div>
		<div class='action'>
			<img src="/img/actions/exam.png">
			<a href="/grades">Оценки<span class='stretched-link'></span></a>
		</div>
	</div>
</div>
		
<?php }
}
