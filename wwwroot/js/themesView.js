// Возвращает объект списка темы
function getTheme(obj) {
    const crudItem = document.createElement('div');
    crudItem.id = 'theme'+obj.id;
    crudItem.classList.add('crud-item');

    const description = document.createElement('p');
    description.innerHTML =
        '<strong>' + obj.name + '</strong>' + '<br/>' +
        'Автор: ' + obj.author + '<br/>';

    const crudButtons = document.createElement('div');
    crudButtons.classList.add('crud-buttons');

    const btnEnable = document.createElement('a');
    btnEnable.textContent = 'Активировать';
    btnEnable.classList.add('btn');
    btnEnable.href = '/themes/activate/'+obj.id;

    const btnDelete = document.createElement('button');
    btnDelete.textContent = 'Удалить навсегда';
    btnDelete.classList.add('btn', 'danger');
    if (obj.canBeDeleted) {
        btnDelete.onclick = () => {
            crudDelete('themes', obj.id, 'theme'+obj.id);
        }
    } else {
        btnDelete.disabled = 'disabled';
    }

    crudButtons.append(btnEnable);
    crudButtons.append(btnDelete);
    crudItem.append(description);
    crudItem.append(crudButtons);

    return crudItem;
}

const lvThemes = document.getElementById('lvThemes');
const btnAddTheme = document.getElementById('btnAddTheme');

window.onload = async function() {
    // Получить список всех тем
    const themes = await getAllThemes();
    themes.forEach(function(obj) {
        const theme = getTheme(obj);
        lvThemes.append(theme);
    });
    lvThemes.classList.remove('hidden');
    loading.classList.add('hidden');
};

btnAddTheme.onclick = () => {
    crudCreateShowWindow(
        'themes',
        {
            'Файл темы (zip архив)': {type: "file", name: "themeFile", accept: '.zip'}
        },
        'Добавление темы',
        function(obj) {
            lvThemes.append(getTheme(obj));
        },
        true
    )
}