<?php
namespace Pockit\Views;

// Архив AutoGost - список отчётов

class AutoGostReportsView extends LayoutView {
	protected $reports;
	protected $subject;
	
	public function content():void { ?>

<div class='card m-1'>
	<div class='text-center card-title'>
		<h1>Архив отчётов</h1>
		<p>Дисциплина: <span class='fg-accent'><?= $this->subject['name'] ?></span></p>
	</div>

	<div id='reportsList'></div>

	<a href="/autogost/new?selected=<?= $this->subject['id'] ?>" class="btn success w-100 m-1">Добавить</a>
</div>

<script>
	<?php
	$all_reports = [];
	while ($report = $this->reports->fetchArray()) {
		$all_reports[] = [
			'id' => $report['id'],
			'work_number' => $report['work_number'],
			'notice' => $report['notice'],
			'date_create' => $report['date_create'],
			'date_for' => $report['date_for']
		];
	}
	?>
	const reports = <?= json_encode($all_reports) ?>;
	const reportsContainer = document.getElementById('reportsList');
	for (const report of reports) {
		const createdReport = getReport(report);
		reportsContainer.append(createdReport);
	}
	
	function updateReport(report) {
		$("#report"+report.id).replaceWith(getReport(report));
	}

	function getReport(report) {
		const crudItem = document.createElement('div');
		crudItem.id = 'report'+report.id;
		crudItem.classList.add('crud-item');

		const description = document.createElement('p');
		description.innerHTML =
			report.work_number + '<br/>' +
			report.notice + '<br/>' +
			'Создан: ' + report.date_create;

		const crudButtons = document.createElement('div');
		crudButtons.classList.add('crud-buttons');

		const viewButton = document.createElement('a');
		viewButton.textContent = 'Просмотр и редактирование';
		viewButton.classList.add('btn');
		viewButton.href = '/autogost/edit/' + report.id;

		const updateButton = document.createElement('button');
		updateButton.textContent = 'Обновить данные';
		updateButton.classList.add('btn');
		updateButton.onclick = function() {
			crudUpdateShowWindow('reports', {
				"Номер работы": {
					type: "plain",
					name: "work_number",
					default: report.work_number
				},

				"Примечание": {
					type: "plain",
					name: "notice",
					default: report.notice
				},

				"Тип работы": {
					type: "crudRead",
					name: "work_type",
					route: "work_types",
					default: report.work_type
				},

				"Дата отчёта": {
					type: "date",
					name: "date_for",
					default: report.date_for
				},

				"ID": {
					type: "hidden",
					name: "id",
					default: report.id
				}
			}, "Обновление отчёта", updateReport);
		};

		const deleteButton = document.createElement('button');
		deleteButton.textContent = 'Удалить';
		deleteButton.classList.add('btn', 'danger');
		deleteButton.onclick = function() {
			crudDelete('reports', report.id, 'report'+report.id);
		}

		crudItem.append(description);
		crudItem.append(crudButtons);
		crudButtons.append(viewButton);
		crudButtons.append(updateButton);
		crudButtons.append(deleteButton);

		return crudItem;
	}
</script>

<?php }
}
