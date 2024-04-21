<?php
namespace Pockit\Views;

// Страница просмотра ссылок

class LinkView extends LayoutView {
	protected $passwords;
	
	public function content():void { ?>

<div class='card m-1'>
	<h1 class='text-center'>Полезные ссылки</h1>
	<div id='linksList'></div>
	<button id='addLinkButton' class='m-1 btn success w-100'>Добавить</button>
</div>

<!--Шаблон ссылки-->
<script id="linkTemplate" type="text/x-jsrender">
	<div class='crud-item'>
		<p>{^{>name}}</p>
		<div class='crud-buttons'>
			<a data-link="href{:href}" target="_blank" class='btn'>Перейти по ссылке</a>
			<button class='btn edit-button'>Изменить</button>
			<button class='btn danger delete-button'>Удалить</button>
		</div>
	</div>
</script>

<script>
	<!--Данные-->
	var data = <?php echo json_encode($this->passwords); ?>;
		
	var template = $.templates("#linkTemplate");
	template.link("#linksList", data);

	<!--Интерактивность-->
	const addLinkButton = document.getElementById('addLinkButton');
	addLinkButton.onclick = function(e) {
		crudCreateShowWindow(
			"links",
			{
				"Название": {type: "plain", name: "name"},
				"Ссылка": {type: "plain", name: "href"},
			},
			"Добавление ссылки",
			function(addedPassword) {
				$.observable(data).insert({
					name: addedPassword.name,
					id: addedPassword.id,
					href: addedPassword.href
				});
			}
		)
	}

	$('#linksList').on('click', '.delete-button', function() {
		const view = $.view(this);
		const linkID = view.data.id;
		const deleted = crudDelete("links", linkID);
		if (deleted) {
			$.observable(data).remove(view.index);
		}
	});

	$('#linksList').on('click', '.edit-button', function() {
		const view = $.view(this);
		crudUpdateShowWindow(
			"links",
			{
				"Название": {type: "plain", name: "name", default: view.data.name},
				"Ссылка": {type: "plain", name: "href", default: view.data.href},
				"ID": {type: "hidden", name: "id", default: view.data.id}
			},
			"Обновление ссылки",
			function(updatedLink) {
				console.log(updatedLink);
				$.observable(view.data).setProperty("name", updatedLink.name);
				$.observable(view.data).setProperty("href", updatedLink.href);
				//template.link("#linkTemplate", data);
			}
		);
	});
</script>

<?php }
}
