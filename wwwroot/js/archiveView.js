function getSubject(obj) {
    const crudItem = document.createElement('div');
    crudItem.id = 'subject'+obj.id;
    crudItem.classList.add('crud-item');

    const description = document.createElement('p');
    description.innerHTML =
    '<strong>' + obj.myName + '</strong>' +
    '<p>Ведёт: ' + obj.teacher.full + '</p>';

    const crudButtons = document.createElement('div');
    crudButtons.classList.add('crud-buttons');

    const btnToReports = document.createElement('a');
    btnToReports.textContent = 'Отчёты дисциплины';
    btnToReports.classList.add('btn');
    btnToReports.href = '/autogost/archive/'+obj.id;

    const btnUpdate = document.createElement('button');
    btnUpdate.textContent = 'Обновить информацию';
    btnUpdate.classList.add('btn');
    btnUpdate.onclick = async function() {
        // Получаем список всех преподов
        const teachers = await getAllTeachers();
        crudUpdateShowWindow(
            'subjects',
            {
                'Название': {type: "plain", name: "name", default: obj.title},
                'Название в программе': {type: "plain", name: "myName", default: obj.myName},
                'Шифр без точки на конце': {type: "plain", name: "code", default: obj.code},
                'Преподаватель': {type: 'select', name: "teacherId", options: teachers, default:obj.teacher.id}
            },
            "Обновление дисциплины",
            function(obj) {
                notify('updated', 'success');
            }
        );
    };

    const btnDelete = document.createElement('button');
    btnDelete.textContent = 'Скрыть';
    btnDelete.classList.add('btn', 'danger');
    btnDelete.onclick = () => {
        crudDelete('subjects', obj.id, 'subject'+obj.id);
    };

    crudItem.append(description);
    crudButtons.append(btnToReports);
    crudButtons.append(btnUpdate);
    crudButtons.append(btnDelete);
    crudItem.append(crudButtons);

    return crudItem;
}

const lvSubjects = document.getElementById('lvSubjects');
const loading = document.getElementById('loading');
const btnAdd = document.getElementById('btnAdd');

window.onload = async function() {
    // Получить список из всех предметов
    const response = await fetch('/subjects/read');
    const data = await response.json();
    data.forEach(function(obj) {
        const subject = getSubject(obj);
        lvSubjects.append(subject);
    });
    lvSubjects.classList.remove('hidden');
    loading.classList.add('hidden');
};

btnAdd.onclick = async function() {
    // Получаем список всех преподов
    const teachers = await getAllTeachers();

    crudCreateShowWindow(
        'subjects',
        {
            'Название': {type: "plain", name: "name"},
            'Название в программе': {type: "plain", name: "myName"},
            'Шифр без точки на конце': {type: "plain", name: "code"},
            'Преподаватель': {type: 'select', name: "teacherId", options: teachers}
        },
        'Добавление дисциплины',
        function(obj) {
            lvSubjects.append(getSubject(obj));
        },
        false
    )
}