<?php
// Архив AutoGost - список отчётов

class AutoGostReportsView extends LayoutView {
	protected $reports;
	protected $subject;
	
	public function content():void { ?>

<div class='text-center'>
	<h1>Архив отчётов по дисциплине:</h1>
	<h2><?=$this->subject['name']?></h2>
	<h3>Выбери отчёт</h3>
</div>

<div class='card'>
	<div id='reportsList'>
		<?php while ($report = $this->reports->fetchArray()) { ?>
			<div id='report<?= $report['id'] ?>' class='crud-item'>
				<p><?=$report['work_number']?><br><?=$report['notice']?><br>Создан: <?=$report['date_create']?></p>
				<div class='crud-buttons'>
					<button onclick='document.location.href = "/autogost/edit/<?=$report['id']?>"'>Просмотр</button>
					<button onclick='crudUpdateShowWindow("reports", {"Номер работы": {type: "plain", name: "work_number", default:"<?=$report['work_number']?>"}, "Примечание": {type: "plain", name: "notice", default:"<?=$report['notice']?>"}, "Тип работы": {type: "crudRead", name: "work_type", route: "work_types", default:<?=$report['work_type']?>}, "ID": {type: "hidden", name: "id", default: <?=$report['id']?>}}, "Обновление отчёта", updateReport)'>Изменить</button>
					<button onclick='crudDelete("reports", <?= $report['id'] ?>, "report<?= $report['id'] ?>")' class='danger'>Удалить</button>
				</div>
			</div>
		<?php } ?>
	</div>
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
					<button onclick='document.location.href = "/autogost/edit/`+report.id+`"'>Просмотр</button>
					<button onclick='crudUpdateShowWindow("reports", {"Номер работы": {type: "plain", name: "work_number", default:"`+report.work_number+`"}, "Примечание": {type: "plain", name: "notice", default:"`+report.notice+`"}, "Тип работы": {type: "crudRead", name: "work_type", route: "work_types", default:`+report.work_type+`}, "ID": {type: "hidden", name: "id", default: `+report.id+`}}, "Обновление отчёта", updateReport)'>Изменить</button>
					<button onclick='crudDelete("reports", `+report.id+`, "report`+report.id+`")' class='danger'>Удалить</button>
				</div>
			</div>`;
	}
</script>

<?php }
}
