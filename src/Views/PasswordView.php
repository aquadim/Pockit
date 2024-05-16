<?php
namespace Pockit\Views;

// Страница просмотра паролей

class PasswordView extends LayoutView {
	protected $passwords;
	
	public function content():void { ?>

<div class='card m-1'>
	<h1 class='text-center card-title'>Менеджер паролей</h1>
	<div id='passwordsList'></div>
	<button id='addPasswordButton' class='m-1 btn success w-100'>Добавить</button>
	<div id='passwordView'></div>
</div>

<!--Шаблон пароля-->
<script id="passwordTemplate" type="text/x-jsrender">
	<div class='crud-item'>
		<p>{^{>name}}</p>
		<div class='crud-buttons'>
			<button class='btn edit-button'>Посмотреть</button>
			<button class='btn danger delete-button'>Удалить</button>
		</div>
	</div>
</script>

<!--Шаблон окна просмотра-->
<script id="passwordWindowTemplate" type="text/x-jsrender">
  
	<div class="dark-overlay" data-link="visible{:passwordDetailsID != -1}">
		<div class='card modal'>
			<h1>Просмотр пароля</h1>
			<div class='crudcreateform'>
				<div class='form-control-container'>
					<label for="secretInput">Секретный ключ</label>
					<input class="form-control" id="secretInput" type="password"/>
					<div style='display: grid;grid-template-columns: auto 25%;grid-gap: 1em;width: 100%;'>
						<button type="submit" class="show-button success form-control">Узнать пароль</button>
						<button type="submit" class="close-button form-control">Закрыть</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</script>

<script>
	<!--Данные-->
	var data =
	{
		passwordDetailsID: -1,
		passwords: <?php echo json_encode($this->passwords); ?>
	}
	var template = $.templates("#passwordTemplate");
	template.link("#passwordsList", data.passwords);

	var passTemp = $.templates("#passwordWindowTemplate");
	passTemp.link("#passwordView", data);

	<!--Интерактивность-->
	const addPasswordButton = document.getElementById('addPasswordButton');
	addPasswordButton.onclick = function(e) {
		crudCreateShowWindow(
			"passwords",
			{
				"Название": {type: "plain", name: "name"},
				"Пароль": {type: "password", name: "password"},
				"Секретный ключ": {type: "password", name: "key"}
			},
			"Добавление пароля",
			function(addedPassword) {
				$.observable(data.passwords).insert({
					name: addedPassword.name,
					id: addedPassword.id,
					value: addedPassword.value
				});
			}
		)
	}

	$('#passwordsList').on('click', '.delete-button', function() {
		const view = $.view(this);
		const passwordID = view.data.id;
		const deleted = crudDelete("passwords", passwordID);
		if (deleted) {
			$.observable(data.passwords).remove(view.index);
		}
	});

	$('#passwordsList').on('click', '.edit-button', function() {
		const view = $.view(this);
		const passwordID = view.data.id;
		$.observable(data).setProperty("passwordDetailsID", passwordID);
		passTemp.link("#passwordView", data);
	});

	$('#passwordView').on('click', '.close-button', function() {
		$.observable(data).setProperty("passwordDetailsID", -1);
	});
	
	$('#passwordView').on('click', '.show-button', function() {
		// Отсылка запроса на получение пароля
		$.ajax({
			url: "/passwords/decrypt",
			type: "POST",
			data: {
				id: data.passwordDetailsID,
				secret: $('#secretInput').val()
			},
			success: function(data) {
				alert(data);
			}
		});
	});
</script>

<?php }
}
