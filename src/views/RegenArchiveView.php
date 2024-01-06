<?php
// Архив Regen

class RegenArchiveView extends LayoutView {
	protected $subjects;
	
	public function content():void { ?>

<div class='text-center'>
	<h1>Архив отчётов</h1>
	<h3>Выбери дисциплину</h3>
</div>

<div id='subjectsList' class='card'>
	<?php while ($subject = $this->subjects->fetchArray()) { ?>
		<div id='subject<?= $subject['id'] ?>' class='crud-item'>
			<p><?= $subject['name'] ?></p>
			<div class='crud-buttons'>
				<button>Отчёты</button>
				<button>Изменить</button>
				<button onclick='crudDelete("subjects", <?= $subject['id'] ?>, "subject<?= $subject['id'] ?>")' class='danger'>Удалить</button>
			</div>
		</div>
	<?php } ?>

	<button onclick='crudCreateShowWindow("subjects", {"Название": {type: "plain", name: "name"}, "Шифр": {type: "plain", name: "code"}, "Преподаватель": {type: "crudRead", name: "teacher_id", route: "teachers"}}, "Добавление дисциплины", createSubject)' class='createbutton form-control'>Добавить</button>
</div>

<script>
	function createSubject(subject) {

        $("#subjectsList").prepend($(`
            <div id='subject`+subject.id+`' class='crud-item'>
                <p>`+subject.name+`</p>
                <div class='crud-buttons'>
                    <button>Отчёты</button>
                    <button>Изменить</button>
                    <button onclick='crudDelete("subjects", `+subject.id+`, "subject`+subject.id+`")' class='danger'>Удалить</button>
                </div>
            </div>
            `));
	}
</script>

<?php }
}
