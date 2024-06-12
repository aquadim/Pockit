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
		return false;
	}
	const url = '/'+route+'/delete?id='+id;
	$.ajax({
		url: url,
		success: function() {
			document.getElementById(containerID).remove();
            notify('Успешно удалено', 'success');
		}
	});
	return true;
}

// Показывает форму обновления элемента
async function crudUpdateShowWindow(route, options, name, afterUpdatedCallback) {
	const card = await createWindow(route, "update", name, options, afterUpdatedCallback)
    $(document.body).append(card);
	$(document.body).append($('<div class="dark-overlay"></div>'));
}

// Показывает форму создания элемента
async function crudCreateShowWindow(route, options, name, afterCreatedCallback, multipart=false) {
	const card = await createWindow(
		route,
		"create",
		name,
		options,
		afterCreatedCallback,
		multipart
	);

    $(document.body).append(card);
	$(document.body).append($('<div class="dark-overlay"></div>'));
}

async function createWindow(route, action, name, options, onSuccess, multipart) {
	// Создание карточки-контейнера
	const card = document.createElement('div');
	card.classList.add('card', 'modal');

	// Создание формы и привязка к ней обратного вызова
	const form = document.createElement('form');
	form.classList.add('crudcreateform');
	form.method = 'post';
	form.action = "/"+route+"/"+action;
	if (multipart) {
		form.enctype = 'multipart/form-data';
	}
	
	form.onsubmit = async function(e) {
		e.preventDefault();

		// Собираем данные со всех полей, отсылаем
		const fd = new FormData(this);
		const response = await fetch("/"+route+"/"+action, {
			method: 'post',
			body: fd
		});
		const jsonData = await response.json();
        if (jsonData.ok) {
            onSuccess(jsonData.obj);
            removeModalWindows();
            notify('Успешно создано', 'success');
        } else {
            notify(jsonData.message, 'danger');
        }
	};

	// Добавление к форме полей ввода
	for (const [key, value] of Object.entries(options)) {

		if (value.type == "hidden") {
			// Невидимое поле ввода
			const input = document.createElement('input');
			input.type = 'hidden';
			input.name = value.name;
			input.value = value.default;
			form.append(input);
			continue;
		}
		
		// Добавляем контейнер поля
		const control_container = document.createElement('div');
		control_container.classList.add('form-control-container');

		// Надпись
		const control_label = document.createElement('label');
		control_label.for = key;
		control_label.textContent = key;
		control_container.append(control_label);

		// Непосредственно поле ввода
		if (value.type == 'plain')  {
			// Текстовое
			const input = document.createElement('input');
			input.classList.add('form-control');
			input.id = key;
			input.type = 'text';
			input.name = value.name;
			if (value.default != undefined) {
				input.value = value.default;
			}
			control_container.append(input);
			
		} else if (value.type == 'crudRead') {
			// Выбор из нескольких вариантов
			const values = JSON.parse(await crudRead(value.route));

			const input = document.createElement('select');
			input.classList.add('form-control');
			input.id = key;
			input.name = value.name;

			for (let i = 0; i < values.length; ++i) {
				const option = document.createElement('option');
				option.value = values[i].id;
				option.textContent = values[i].repr;
				if (values[i].id == value.default) {
					// Это значение по-умолчанию
					option.selected = 'selected';
				}
				input.append(option);
			}
			control_container.append(input);

		} else if (value.type == 'password') {
			// Пароль
			const input = document.createElement('input');
			input.classList.add('form-control');
			input.id = key;
			input.type = 'password';
			input.name = value.name;
			control_container.append(input);

		} else if (value.type == 'date') {
			// Дата
			const input = document.createElement('input');
			input.classList.add('form-control');
			input.id = key;
			input.type = 'date';
			input.name = value.name;
			if (value.default != undefined) {
				input.value = value.default;
			}
			control_container.append(input);

		} else if (value.type == 'file') {
			const input = document.createElement('input');
			input.classList.add('form-control');
			input.id = key;
			input.type = 'file';
			input.name = value.name;
			if (value.accept != undefined) {
				input.accept = value.accept;
			}
			control_container.append(input);

        } else if (value.type == 'select') {
            const input = document.createElement('select');
			input.classList.add('form-control');
			input.id = key;
			input.name = value.name;

            const opts = value.options;
            for (let i = 0; i < opts.length; ++i) {
				const option = document.createElement('option');
				option.value = opts[i].id;
				option.textContent = opts[i].repr;
				if (opts[i].id == value.default) {
					// Это значение по-умолчанию
					option.selected = 'selected';
				}
				input.append(option);
			}
			control_container.append(input);

		} else {
			console.log("Неизвестный тип: " + value.type);
			continue;	
		}
		form.append(control_container);
	};

	// Кнопки добавления и отмены
	const okCancelRow = document.createElement('div');
	okCancelRow.classList.add('succesCancelRow');

	const btnSave = document.createElement('button');
	btnSave.classList.add('btn', 'success');
	btnSave.type = 'submit';
	btnSave.textContent = 'Сохранить';

	const btnCancel = document.createElement('button');
	btnCancel.classList.add('btn');
	btnCancel.type = 'button';
	btnCancel.onclick = function() {
		removeModalWindows();
	}
	btnCancel.textContent = 'Отмена';

	okCancelRow.append(btnSave);
	okCancelRow.append(btnCancel);

	form.append(okCancelRow);

	// Заголовок
	const heading = document.createElement('h1');
	heading.textContent = name;

	// Добавление всех элементов и показ
    card.append(heading);
    card.append(form);

    return card;
}

// Удаляет все компоненты с модальными окнами
function removeModalWindows() {
	$('.modal, .dark-overlay').remove();
}

// Создаёт уведомление
function notify(message, notifClass) {
    const notification = document.createElement('div');
    notification.textContent = message;
    notifyArea.append(notification);
    notification.classList.add('notification', 'notifSlideLeft', notifClass);
    setTimeout(function() {
        notification.classList.add('notifSlideRight');
    }, 5000);
    setTimeout(function() {
        notification.remove();
    }, 5500);
    notification.onclick = () => notification.classList.add('notifSlideRight');
}

// API: Возвращает всех преподавателей
async function getAllTeachers() {
    const response = await fetch('/teachers/read');
    const teachers = await response.json();
    return teachers;
}

const notifyArea = document.getElementById('notifyArea');