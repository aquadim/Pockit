<?php
// Класс разметки

class LayoutView extends View {
	protected $page_title;
	protected $crumbs;

	protected function breadcrumbs() {
		foreach ($this->crumbs as $crumb => $url) { ?>
			<a href="<?= $url ?>"><?= $crumb ?></a>
		<?php }
	}
	
	public function view():void { ?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= $this->page_title ?></title>
	</head>
	<body>
		<?php $this->breadcrumbs(); ?>
		<?php $this->content(); ?>
	</body>
</html>

<?php }}
