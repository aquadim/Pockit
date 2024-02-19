<?php
namespace Pockit\Views;

// Класс разметки

class LayoutView extends View {
	protected $page_title;
	protected $crumbs = array();

	protected function breadcrumbs() { ?>

<nav class="breadcrumb">
	<ul>
	<?php foreach ($this->crumbs as $crumb => $url) {
		if ($crumb === array_key_last($this->crumbs)) { ?>
			<li class="breadcrumb-item active"><?= $crumb ?></li>
		<?php } else { ?>
			<li class="breadcrumb-item"><a href="<?= $url ?>"><?= $crumb ?></a></li>
		<?php } ?>
	<?php } ?>
	</ul>
</nav>

<?php }

	protected function customHead() {}

	protected function customScripts() {}
	
	public function view() : void { ?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Cache-control" content="public">
		<title><?= $this->page_title ?> | Карманный сервер</title>
		<link rel='icon' type='image/png' href='/img/favicon.png'>
		<link rel="stylesheet" href="/css/pockit.css">
		<?php $this->customHead() ?>
	</head>
	<body>
		<?php $this->breadcrumbs(); ?>
		<?php $this->content(); ?>
		<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
		<script src="/js/pockit.js"></script>
		<?php $this->customScripts(); ?>
	</body>
</html>

<?php }
}
