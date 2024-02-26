<?php
namespace Pockit\Views;

// Архив AutoGost

class AutoGostArchiveView extends LayoutView {
	protected $subjects;
	
	public function content():void { ?>

<div class='card m-1'>
	<h1 class='text-center'>Архив отчётов</h1>
	<div id='subjectsList'>
		<?php while ($subject = $this->subjects->fetchArray()) { ?>
			<div id='subject<?= $subject['id'] ?>' class='crud-item'>
				<p><?= $subject['name'] ?></p>
				<div class='crud-buttons'>
					<a class='btn' href='/autogost/archive/<?=$subject['id']?>'>Отчёты</a>
					<button class='btn' onclick='crudUpdateShowWindow("subjects", {"Название": {type: "plain", name: "name", default: "<?=$subject['name']?>"}, "Шифр": {type: "plain", name: "code", default: "<?=$subject['code']?>"}, "Преподаватель": {type: "crudRead", name: "teacher_id", route: "teachers", default:<?=$subject['teacher_id']?>}, "ID": {type: "hidden", name: "id", default: <?=$subject['id']?>}}, "Обновление дисциплины", updateSubject)'>Изменить</button>
					<button class='btn danger' onclick='crudDelete("subjects", <?= $subject['id'] ?>, "subject<?= $subject['id'] ?>")'>Удалить</button>
				</div>
			</div>
		<?php } ?>
	</div>

	<button class='btn success w-100' onclick='crudCreateShowWindow("subjects", {"Название": {type: "plain", name: "name"}, "Шифр": {type: "plain", name: "code"}, "Преподаватель": {type: "crudRead", name: "teacher_id", route: "teachers"}}, "Добавление дисциплины", createSubject)' class='createbutton form-control'>Добавить</button>
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
		    <a class='btn' href="/autogost/archive/`+subject.id+`">Отчёты</a>
		    <button class='btn' onclick='crudUpdateShowWindow("subjects", {"Название": {type: "plain", name: "name", default: "`+subject.name+`"}, "Шифр": {type: "plain", name: "code", default: "`+subject.code+`"}, "Преподаватель": {type: "crudRead", name: "teacher_id", route: "teachers", default:`+subject.teacher_id+`}, "ID": {type: "hidden", name: "id", default: `+subject.id+`}}, "Обновление дисциплины", updateSubject)'>Изменить</button>
		    <button class='btn danger' onclick='crudDelete("subjects", `+subject.id+`, "subject`+subject.id+`")'>Удалить</button>
		</div>
	    </div>`;
	}
</script>

<?php }
}
