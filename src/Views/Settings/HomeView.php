<?php
// Главная страница настроек

namespace Pockit\Views\Settings;

use Pockit\Views\LayoutView;

class HomeView extends LayoutView {
	
	public function content():void { ?>

<div class='card m-1'>
	<h1 class='text-center card-title'>Настройки</h1>
    <div class='actions shadowedFg'>

		<div class='action'>
			<img src="/img/actions/color.png"/>
			<a href="/settings/themes">Темы<span class='stretched-link'></span></a>
		</div>

		<div class='action'>
			<img src="/img/actions/newReport.png"/>
			<a href="/settings/autogost">Автогост<span class='stretched-link'></span></a>
		</div>

        <div class='action'>
			<img src="/img/actions/aboutMe.png"/>
			<a href="/settings/about">Обо мне<span class='stretched-link'></span></a>
		</div>
		
	</div>

	<a class='btn m-1' href='/'>Главная страница</a>
</div>

<?php }
}
