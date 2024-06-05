<?php
namespace Pockit\Views;

// Информация о приложении

class AboutView extends LayoutView {
	protected $page_title = "О карманном сервере";
	
	public function content() : void { ?>

	<div class='card m-3'>
		<h1 class='card-title text-center'>О карманном сервере</h1>
		<p class='textwall'>
			Разработчик: Вадим Королёв.
			<a class='fg-accent' href="https://vk.com/aquavadim">
				ВКонтакте
			</a>
			/
			<a class='fg-accent' href="https://t.me/vadim_aqua">
				Telegram
			</a>
			/
			<a class='fg-accent' href='https://t.me/aquadimcodes'>
				Telegram канал разработчика
			</a>
		</p>
		<p class='textwall'>
			Иконки: 
			<a 	class='fg-accent'
				href='https://github.com/PapirusDevelopmentTeam/papirus-icon-theme'>
				Papirus
			</a>
		</p>
		<p class='textwall'>
			Алгоритм сбора оценок: 
			<a 	class='fg-accent'
				href='https://vk.com/vpmt_bot'>
				Техбот
			</a>
		</p>
		
	</div>
		
<?php }
}
