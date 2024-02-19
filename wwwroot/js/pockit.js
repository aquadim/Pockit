// Возвращает список значений из БД
async function crudRead(route, limit = 999) {
	const url = "/"+route+"/read?limit="+limit;
	return $.ajax({
		type: 'POST',
		url: url
	});
}

// Отправляет запрос к API на удаление
// После удаления на странице удаляется элемент с ID контейнера
function crudDelete(route, id, containerID=null) {
	if (!confirm("Точно удалить?")) {
		return;
	}
	const url = '/'+route+'/delete?id='+id;
	$.ajax({
		url: url,
		success: function() {
			document.getElementById(containerID).remove();
		}
	});
}

// Показывает форму обновления элемента
async function crudUpdateShowWindow(route, options, name, afterUpdatedCallback) {
	const card = await createWindow(route, "update", name, options, afterUpdatedCallback)
    $(document.body).append(card);
	$(document.body).append($('<div class="dark-overlay"></div>'));
}

// Показывает форму создания элемента
async function crudCreateShowWindow(route, options, name, afterCreatedCallback) {
	const card = await createWindow(route, "create", name, options, afterCreatedCallback)
    $(document.body).append(card);
	$(document.body).append($('<div class="dark-overlay"></div>'));
}

async function createWindow(route, action, name, options, afterCallback) {
	// Создание карточки-контейнера
	const card = $("<div class='card modal'></div>");

	// Создание формы и привязка к ней обратного вызова
	const form = $("<form class='crudcreateform' method='post' action='/"+route+"/"+action+"'></form>");
	form.submit(function(e) {
		e.preventDefault();
		$.ajax({
			url: "/"+route+"/"+action,
			type: 'post',
			data: $(this).serialize(),
			success: function(response) {
				// Окно и слой затемнения удаляется
				removeModalWindows();
				// Вызывается функция обратного вызова с параметром - объектом с информацией о созданном элементе
				afterCallback(JSON.parse(response));
			}
		});
	});

	// Добавление к форме полей ввода
	for (const [key, value] of Object.entries(options)) {

		if (value.type == "hidden") {
			// Невидимое поле ввода
			form.append($('<input type="hidden" name="'+value.name+'" value="'+value.default+'"/>'));
			continue;
		}
		
		// Добавляем контейнер поля
		let control_container = $("<div class='form-control-container'></div>");

		// Надпись
		control_container.append($('<label for="'+key+'">'+ key +'</label>'));

		// Непосредственно поле ввода
		if (value.type == 'plain')  {
			// Текстовое
			const input = $('<input class="form-control" id="'+key+'" type="text" name="'+value.name+'"/>');
			if (value.default != undefined) {
				input.attr("value", value.default);
			}
			control_container.append(input);
			
		} else if (value.type == 'crudRead') {
			// Выбор из нескольких вариантов
			
			const values = JSON.parse(await crudRead(value.route));
			const selectInput = $('<select class="form-control" id="'+key+'" name="'+value.name+'">');
			for (let i = 0; i < values.length; ++i) {
				if (values[i].id == value.default) {
					// Это значение по-умолчанию
					selectInput.append($('<option selected="selected" value="'+values[i].id+'">'+values[i].repr+'</option>'));
				} else {
					selectInput.append($('<option value="'+values[i].id+'">'+values[i].repr+'</option>'));
				}
			}
			control_container.append(selectInput);

		} else {
			console.log("Неизвестный тип: " + value.type);
		}

		form.append(control_container);
	};

	// Кнопки добавления и отмены
	form.append($(`
	<div style='display: grid;grid-template-columns: auto 25%;grid-gap: 1em;width: 100%;'>
		<button type="submit" class="crudcreate success form-control">Сохранить</button>
		<button type="submit" onclick='removeModalWindows()' class="crudcancel form-control">Отмена</button>
	</div>`));

	// Добавление всех элементов и показ
    card.append($('<h1>'+name+'</h1>'));
    card.append(form);

    return card;
}

// Удаляет все компоненты с модальными окнами
function removeModalWindows() {
	$('.modal, .dark-overlay').remove();
}