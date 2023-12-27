<?php
// Класс разметки

class LayoutView extends View {
	protected $page_title;
	protected $crumbs = array();

	protected function breadcrumbs() { ?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb" style="--bs-breadcrumb-divider-color:var(--bs-light)">
	<?php foreach ($this->crumbs as $crumb => $url) {
		if ($crumb === array_key_last($this->crumbs)) { ?>
			<li class="text-light breadcrumb-item"><?= $crumb ?></li>
		<?php } else { ?>
			<li class="breadcrumb-item active"><a href="<?= $url ?>"><?= $crumb ?></a></li>
		<?php } ?>
	<?php } ?>
	</ol>
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
		<script src="/jquery/jquery.min.js"></script>
		<script src="/jqueryui/jquery-ui.min.js"></script>

		<?php $this->customHead() ?>
	</head>
	<body class="container bg-dark text-light">
		<?php $this->breadcrumbs(); ?>
		<?php $this->content(); ?>
	</body>
</html>

<?php }
}
