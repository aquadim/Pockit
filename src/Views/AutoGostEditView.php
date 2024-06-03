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
<script src="/js/autogost.bundle.js"></script>
<?php }

	public function content():void { ?>

<!--Панель управления-->
<div id='agstSidebar' class='sidebar sidebarOpen'>
	<div id='agstControls'>

		<button class='btn success' id='saveMarkupButton'>Сохранить</button>

		<button class='btn' id="printReport">Печать</button>
		<button class='btn' id="getFilename">Скопировать название файла</button>
		<a href="/autogost/jshtml/<?= $this->report_id ?>" class='btn' id='btnGetHTML'>Скачать HTML</a>

		<label class='btn' id='lblAddImage'>
			<input type='file' multiple="multiple" id='btnAddImage'/>
			Добавить изображения
			<div class='loader hidden' id='loaderAddImage'></div>
		</label>

		<button class='btn' id='switchPreview'>Превью</button>
		<button class='btn border-accent' id='switchMarkup'>Разметка</button>
		
	</div>
</div>

<!--Основной раздел-->
<div id="agstMain" class="contentShifted">
	
	<div class='card m-1'>

		<div class='loader hidden' id='agstLoader'></div>

		<!--Редактор разметки-->
		<div class="editor" id="agstEditor"></div>

		<!--Превью-->
		<div id="agstPreview" class='hidden'>
			<!--Ошибки-->
			<div class='card danger hidden' id="agstErrors">
				<h3 class='text-center'></h3>
				<ol id="agstErrorsList"></ol>
			</div>
			<!--Вывод HTML-->
			<div id="agstOutput"></div>
		</div>
		
	</div>

	<div class="text-center w-100">
		<button id="btnToggleSidebar" class='btn m-1' onclick="toggleSidebar()">❌ Закрыть панель инструментов</button>
	</div>
</div>

<!--Связь с javascript-->
<script type="text/javascript">
	var PHP_report_id = <?= $this->report_id ?>;
	var PHP_filename = "<?= $this->filename ?>".replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, "");
</script>

<?php }
}
