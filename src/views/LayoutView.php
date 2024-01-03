<?php
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

	protected function customHead() {
		
	}
	
	public function view():void { ?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= $this->page_title ?></title>
		<link rel="stylesheet" href="/jqueryui/themes/base/jquery-ui.min.css">
		<link rel="stylesheet" href="/css/pockit.css">
		<script src="/jquery/jquery.min.js"></script>
		<script src="/jqueryui/jquery-ui.min.js"></script>

		<?php $this->customHead() ?>
	</head>
	<body>
		<?php $this->breadcrumbs(); ?>
		<?php $this->content(); ?>
	</body>
</html>

<?php }
}
