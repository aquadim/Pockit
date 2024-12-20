const btnAddSubject = document.getElementById('btnAddSubject');
const selSubjectId = document.getElementById('sel-subject_id');

let teachers;

window.onload = async function() {
    teachers = await getAllTeachers();
}

btnAddSubject.onclick = async function() {
    crudCreateShowWindow(
        'subjects',
        {
            'Название для титульных листов': {type: "plain", name: "name"},
            'Название в программе': {type: "plain", name: "myName"},
            'Шифр дисциплины без точки на конце (например МДК.05.02)': {type: "plain", name: "code"},
            'Преподаватель': {type: 'select', name: "teacherId", options: teachers}
        },
        'Добавление дисциплины',
        function(obj) {
            const opt = document.createElement('option');
            opt.value = obj.id;
            opt.textContent = obj.repr;
            opt.selected = true;
            selSubjectId.append(opt);
            notify('Успешно создано', 'success');
        },
        false
    )
}