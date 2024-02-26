<?php
namespace Pockit\Views;

// Архив AutoGost - список отчётов

class AutoGostReportsView extends LayoutView {
	protected $reports;
	protected $subject;
	
	public function content():void { ?>

<div class='card m-1'>
	<div class='text-center'>
		<h1>Архив отчётов</h1>
		<p>Дисциплина: <span class='fg-accent'><?= $this->subject['name'] ?></span></p>
	</div>

	<div id='reportsList'>
		<?php while ($report = $this->reports->fetchArray()) { ?>
			<div id='report<?= $report['id'] ?>' class='crud-item'>
				<p><?=$report['work_number']?><br><?=$report['notice']?><br>Создан: <?=$report['date_create']?></p>
				<div class='crud-buttons'>
					<a class='btn' href="/autogost/edit/<?=$report['id']?>">Просмотр</a>
					<button class='btn' onclick='crudUpdateShowWindow("reports", {"Номер работы": {type: "plain", name: "work_number", default:"<?=$report['work_number']?>"}, "Примечание": {type: "plain", name: "notice", default:"<?=$report['notice']?>"}, "Тип работы": {type: "crudRead", name: "work_type", route: "work_types", default:<?=$report['work_type']?>}, "ID": {type: "hidden", name: "id", default: <?=$report['id']?>}}, "Обновление отчёта", updateReport)'>Изменить</button>
					<button class='btn danger' onclick='crudDelete("reports", <?= $report['id'] ?>, "report<?= $report['id'] ?>")'>Удалить</button>
				</div>
			</div>
		<?php } ?>
	</div>

	<a href="/autogost/new/" class="btn success w-100">Добавить</a>
</div>

<script>
	function updateReport(report) {
		$("#report"+report.id).replaceWith(getReport(report));
	}

	function getReport(report) {
		return `
            <div id='report`+report.id+`' class='crud-item'>
				<p>`+report.work_number+`<br>`+report.notice+`<br>Создан: `+report.date_create+`</p>
				<div class='crud-buttons'>
					<a class='btn' href='/autogost/edit/`+report.id+`'>Просмотр</a>
					<button class='btn' onclick='crudUpdateShowWindow("reports", {"Номер работы": {type: "plain", name: "work_number", default:"`+report.work_number+`"}, "Примечание": {type: "plain", name: "notice", default:"`+report.notice+`"}, "Тип работы": {type: "crudRead", name: "work_type", route: "work_types", default:`+report.work_type+`}, "ID": {type: "hidden", name: "id", default: `+report.id+`}}, "Обновление отчёта", updateReport)'>Изменить</button>
					<button onclick='crudDelete("reports", `+report.id+`, "report`+report.id+`")' class='btn danger'>Удалить</button>
				</div>
			</div>`;
	}
</script>

<?php }
}
