function getReport(obj) {
    const crudItem = document.createElement('div');
    crudItem.id = 'report'+obj.id;
    crudItem.classList.add('crud-item');

    const description = document.createElement('p');

    const createdAt = new Date(obj.createdAt.date);
    const dateFor = new Date(obj.dateFor.date);

    var year = dateFor.getFullYear();
    var month = (dateFor.getMonth() + 1).toString().padStart(2, '0');
    var day = dateFor.getDate().toString().padStart(2, '0');
    var formattedDateFor = year + '-' + month + '-' + day;
    
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    };
    description.innerHTML =
        '<p>№' + obj.workNumber + '</p>' +
        '<p>' + obj.comment + '</p>' +
        '<p>Создан: ' + createdAt.toLocaleDateString('ru-RU', options);

    const crudButtons = document.createElement('div');
    crudButtons.classList.add('crud-buttons');

    const viewButton = document.createElement('a');
    viewButton.textContent = 'Просмотр и редактирование';
    viewButton.classList.add('btn');
    viewButton.href = '/autogost/edit/' + obj.id;

    const updateButton = document.createElement('button');
    updateButton.textContent = 'Обновить данные';
    updateButton.classList.add('btn');
    updateButton.onclick = function() {
        crudUpdateShowWindow(
            'reports',
            {
                "Номер работы": {type: "plain", name: "workNumber", default: obj.workNumber},
                "Тип работы" : {type: "select", name: "workType", options: workTypes, default: obj.workType.id},
                "Дата отчёта": {type: 'date', name: 'dateFor', default: formattedDateFor},
                "Комментарий": {type: "plain", name: "comment", default: obj.comment},
                "id": {type: 'hidden', name: 'reportId', default: obj.id}
            },
            "Обновление отчёта",
            function(receivedObj) {
                crudItem.replaceWith(getReport(receivedObj));
                notify('Обновлено успешно!', 'success');
            }
        );
    };

    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Удалить';
    deleteButton.classList.add('btn', 'danger');
    deleteButton.onclick = function() {
        crudDelete('reports', obj.id, 'report'+obj.id);
    }

    crudButtons.append(viewButton);
    crudButtons.append(updateButton);
    crudButtons.append(deleteButton);
    crudItem.append(description);
    crudItem.append(crudButtons);

    return crudItem;
}

const lvReports = document.getElementById('lvReports');
const loading = document.getElementById('loading');
let workTypes;

window.onload = async function() {
    // Получить список из всех отчётов по дисциплине
    const reports = await getReportsBySubjectId(PHP_subject_id);
    reports.forEach(function(obj) {
        const report = getReport(obj);
        lvReports.append(report);
    });
    lvReports.classList.remove('hidden');
    loading.classList.add('hidden');

    // Получить все типы работ
    workTypes = await getAllWorkTypes();
};