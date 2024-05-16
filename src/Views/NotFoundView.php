<?php
namespace Pockit\Views;

// Страница "404"

class NotFoundView extends LayoutView {
	protected $page_title = "Ничего не найдено";
	public function content():void { ?>
		
<div class="card m-3 text-center">
	<h1 class="fg-accent">404</h1>
	<p class='m-1'>Ничего не найдено</p>
	<a href="/" class="btn">На главную</a>
</div>

<?php }}
