<?php
namespace Pockit\Views;

// Страница "404"

class NotFoundView extends LayoutView {
	protected $page_title = "Ничего не найдено";
	public function content():void { ?>
		
<div style='text-align:center;margin-top: 5rem;'>
	<h1>Ничего не найдено</h1>
	<h2>404</h2>
	<a style='display:block' href='/'>На главную</a>
</div>

<?php }}
