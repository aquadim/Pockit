function getLink(obj) {
    const crudItem = document.createElement('div');
    crudItem.id = 'link'+obj.id;
    crudItem.classList.add('crud-item');

    const description = document.createElement('p');
    description.innerHTML = '<p>' + obj.name + '</p>';

    const crudButtons = document.createElement('div');
    crudButtons.classList.add('crud-buttons');

    const btnGo = document.createElement('a');
    btnGo.target = '_blank';
    btnGo.textContent = 'Перейти';
    btnGo.classList.add('btn');
    btnGo.href = obj.href;

    const btnUpdate = document.createElement('button');
    btnUpdate.textContent = 'Обновить информацию';
    btnUpdate.classList.add('btn');
    btnUpdate.onclick = async function() {
        crudUpdateShowWindow(
            'links',
            {
                'Название': {type: "plain", name: "name", default: obj.name},
                'Ссылка': {type: "plain", name: "href", default: obj.href},
                'id': {type: 'hidden', name: 'linkId', default: obj.id}
            },
            "Обновление ссылки",
            function(receivedObj) {
                crudItem.replaceWith(getLink(receivedObj));
                notify('Успешно обновлено', 'success');
            }
        );
    };

    const btnDelete = document.createElement('button');
    btnDelete.textContent = 'Удалить';
    btnDelete.classList.add('btn', 'danger');
    btnDelete.onclick = () => {
        crudDelete('links', obj.id, 'link'+obj.id);
    };

    crudItem.append(description);
    crudButtons.append(btnGo);
    crudButtons.append(btnUpdate);
    crudButtons.append(btnDelete);
    crudItem.append(crudButtons);

    return crudItem;
}

const lvLinks = document.getElementById('lvLinks');
const loading = document.getElementById('loading');
const btnAddLink = document.getElementById('btnAddLink');

window.onload = async function() {
    // Получить список из всех ссылок
    const response = await fetch('/links/read');
    const data = await response.json();
    
    data.forEach(function(obj) {
        const link = getLink(obj);
        lvLinks.append(link);
    });
    lvLinks.classList.remove('hidden');
    loading.classList.add('hidden');
};

btnAddLink.onclick = async function() {
    crudCreateShowWindow(
        'links',
        {
            'Название': {type: "plain", name: "name"},
            'Ссылка': {type: "plain", name: "href"}
        },
        'Добавление ссылки',
        function(addedObj) {
            lvLinks.append(getLink(addedObj));
            notify('Успешно создано', 'success');
        }
    );
}