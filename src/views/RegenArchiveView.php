<?php
// Архив Regen

class RegenArchiveView extends LayoutView {
	protected $subjects;
	
	public function content():void { ?>

<div class='text-center'>
	<h1>Архив отчётов</h1>
	<h3>Выбери дисциплину</h3>
</div>

<div class='card'>
	<div id='subjectsList'>
		<?php while ($subject = $this->subjects->fetchArray()) { ?>
			<div id='subject<?= $subject['id'] ?>' class='crud-item'>
				<p><?= $subject['name'] ?></p>
				<div class='crud-buttons'>
					<button onclick='document.location.href = "/regen/archive/<?=$subject['id']?>"'>Отчёты</button>
					<button onclick='crudUpdateShowWindow("subjects", {"Название": {type: "plain", name: "name", default: "<?=$subject['name']?>"}, "Шифр": {type: "plain", name: "code", default: "<?=$subject['code']?>"}, "Преподаватель": {type: "crudRead", name: "teacher_id", route: "teachers", default:<?=$subject['teacher_id']?>}, "ID": {type: "hidden", name: "id", default: <?=$subject['id']?>}}, "Обновление дисциплины", updateSubject)'>Изменить</button>
					<button onclick='crudDelete("subjects", <?= $subject['id'] ?>, "subject<?= $subject['id'] ?>")' class='danger'>Удалить</button>
				</div>
			</div>
		<?php } ?>
	</div>

	<button onclick='crudCreateShowWindow("subjects", {"Название": {type: "plain", name: "name"}, "Шифр": {type: "plain", name: "code"}, "Преподаватель": {type: "crudRead", name: "teacher_id", route: "teachers"}}, "Добавление дисциплины", createSubject)' class='createbutton form-control'>Добавить</button>
</div>

<script>
	function updateSubject(subject) {
		$("#subject"+subject.id).replaceWith(getSubject(subject));
	}

	function createSubject(subject) {
		const new_item = getSubject(subject);
        $("#subjectsList").append($(new_item));
	}

	function getSubject(subject) {
		return `
            <div id='subject`+subject.id+`' class='crud-item'>
                <p>`+subject.name+`</p>
                <div class='crud-buttons'>
                    <button>Отчёты</button>
                    <button onclick='crudUpdateShowWindow("subjects", {"Название": {type: "plain", name: "name", default: "`+subject.name+`"}, "Шифр": {type: "plain", name: "code", default: "`+subject.code+`"}, "Преподаватель": {type: "crudRead", name: "teacher_id", route: "teachers", default:`+subject.teacher_id+`}, "ID": {type: "hidden", name: "id", default: `+subject.id+`}}, "Обновление дисциплины", updateSubject)'>Изменить</button>
                    <button onclick='crudDelete("subjects", `+subject.id+`, "subject`+subject.id+`")' class='danger'>Удалить</button>
                </div>
            </div>`;
	}
</script>

<?php }
}
