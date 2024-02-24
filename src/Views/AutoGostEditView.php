<?php
namespace Pockit\Views;

// Страница редактирования отчёта

class AutoGostEditView extends LayoutView {
	protected $markup;
	protected $filename;
	protected $report_id;

	public function customHead():void { ?>
<link rel="stylesheet" href="/css/autogost-report.css">
<?php }

	public function customScripts():void { ?>
<script src="/js/autogostpreview.js"></script>
<script type="text/javascript" src="/js/sidebar.js"></script>
<?php }

	public function content():void { ?>

<!--Панель управления-->
<div id='agstControls' class='sidebar sidebarOpen'>
	<button class='btn border-accent' id='switchMarkup'>Разметка</button>
	<button class='btn' id='switchPreview'>Превью</button>
	<button class='btn' id="printReport">Печать</button>
	<button class='btn' id="getFilename">Получить название файла</button>
	<button class='btn success' id='saveMarkupButton'>Сохранить</button>
</div>

<!--Основной раздел-->
<div id="agstMain" class="contentShifted">
	
	<div class='card m-1'>

		<!--Редактор разметки-->
		<div class="editor" id="agstEditor">
			<div id="agstLineNumbers"></div>
			<textarea id="agstMarkup" autocomplete="off"><?= $this->markup ?></textarea>
		</div>

		<!--Превью-->
		<div id="agstPreview" class='hidden'>
			<!--Ошибки-->
			<div class='card danger hidden' id="agstErrors">
				<h3 class='text-center'></h3>
				<ol id="agstErrorsList"></ol>
			</div>
			<div id="agstOutput"></div>
		</div>
		
	</div>

	<div class="text-center w-100">
		<button id="btnToggleSidebar" class='btn' onclick="toggleSidebar()">❌ Закрыть панель инструментов</button>
	</div>
</div>

<!--Связь с javascript-->
<script type="text/javascript">
	var globalReportId = <?= $this->report_id ?>;
	var globalFilename = "<?= $this->filename ?>".replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, "");
	var globalSidebarOpened = true;
</script>

<?php }
}
