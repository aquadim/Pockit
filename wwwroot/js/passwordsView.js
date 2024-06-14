function getPassword(obj) {
    const crudItem = document.createElement('div');
    crudItem.id = 'password'+obj.id;
    crudItem.classList.add('crud-item');

    const description = document.createElement('p');
    description.innerHTML = '<p>' + obj.name + '</p>';

    const crudButtons = document.createElement('div');
    crudButtons.classList.add('crud-buttons');

    const viewButton = document.createElement('button');
    viewButton.textContent = 'Напомнить';
    viewButton.classList.add('btn');
    viewButton.onclick = function() {
        // Создать окно просмотра
        const win = document.createElement('div');
        win.classList.add('card', 'modal');

        // Заголовок
        const heading = document.createElement('h1');
        heading.textContent = 'Напомнить пароль';
        heading.classList.add('card-title', 'text-center');

        // Секретный ключ
        const secretKeyContainer = document.createElement('div');
        secretKeyContainer.classList.add('form-control-container');
        const secretKeyLabel = document.createElement('label');
        secretKeyLabel.for = 'inpSecretKey';
        secretKeyLabel.textContent = 'Секретный ключ';
        const secretKeyInput = document.createElement('input');
        secretKeyInput.id = 'inpSecretKey';
        secretKeyInput.classList.add('form-control');
        secretKeyInput.type = 'password';

        // Ряд кнопок
        const buttonRow = document.createElement('div');
        buttonRow.classList.add('succesCancelRow');

        // Кнопка "Готово"
        const btnDone = document.createElement('button');
        btnDone.classList.add('btn', 'success');
        btnDone.textContent = 'Напомнить';
        btnDone.onclick = async function() {
            // 1. Отсылаем запрос на расшифровывание
            const fd = new FormData();
            fd.append('secretKey', secretKeyInput.value);
            const response = await fetch(
                '/passwords/decrypt/'+obj.id,
                {
                    method: 'post',
                    body: fd
                }
            );
            const data = await response.json();

            // 2. Показываем результат
            if (!data.ok) {
                notify('Неверный секретный ключ', 'danger');
                return;
            } else {
                alert(data.output);
            }
            
            // 3. Всё модальное убираем
            removeModalWindows();
        }

        // Кнопка "Отмена"
        const btnCancel = document.createElement('button');
        btnCancel.classList.add('btn');
        btnCancel.textContent = 'Отмена';
        btnCancel.onclick = () => removeModalWindows();

        // Тёмный фон
        const overlay = document.createElement('div');
        overlay.classList.add('dark-overlay');

        // Упаковка
        secretKeyContainer.append(secretKeyLabel);
        secretKeyContainer.append(secretKeyInput);

        buttonRow.append(btnDone);
        buttonRow.append(btnCancel);

        win.append(heading);
        win.append(secretKeyContainer);
        win.append(buttonRow);
    
        document.body.append(win);
        document.body.append(overlay);
    }

    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Удалить';
    deleteButton.classList.add('btn', 'danger');
    deleteButton.onclick = function() {
        crudDelete('passwords', obj.id, 'password'+obj.id);
    }

    crudButtons.append(viewButton);
    crudButtons.append(deleteButton);
    crudItem.append(description);
    crudItem.append(crudButtons);

    return crudItem;
}

const lvPasswords = document.getElementById('lvPasswords');
const loading = document.getElementById('loading');
const btnAddPassword = document.getElementById('btnAddPassword');

window.onload = async function() {
    // Получить список всех паролей
    const passwords = await getAllPasswords();
    passwords.forEach(function(obj) {
        const password = getPassword(obj);
        lvPasswords.append(password);
    });
    lvPasswords.classList.remove('hidden');
    loading.classList.add('hidden');
};

btnAddPassword.addEventListener('click', function() {
    crudCreateShowWindow(
        'passwords',
        {
            'Название': {type: 'plain', name: 'name'},
            'Пароль': {type: 'password', name: 'password'},
            'Секретный ключ': {type: 'password', name: 'secretKey'}
        },
        'Добавление пароля',
        function(receivedObj) {
            lvPasswords.append(getPassword(receivedObj));
            notify('Успешно создано', 'success');
        }
    );
});

//~ <div class="dark-overlay" data-link="visible{:passwordDetailsID != -1}">
		//~ <div class='card modal'>
			//~ <h1>Просмотр пароля</h1>
			//~ <div class='crudcreateform'>
				//~ <div class='form-control-container'>
					//~ <label for="secretInput">Секретный ключ</label>
					//~ <input class="form-control" id="secretInput" type="password"/>
					//~ <div style='display: grid;grid-template-columns: auto 25%;grid-gap: 1em;width: 100%;'>
						//~ <button type="submit" class="show-button success form-control">Узнать пароль</button>
						//~ <button type="submit" class="close-button form-control">Закрыть</button>
					//~ </div>
				//~ </div>
			//~ </div>
		//~ </div>
	//~ </div>