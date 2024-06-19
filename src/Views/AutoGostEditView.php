<?php
namespace Pockit\Views;

// Страница редактирования отчёта

class AutoGostEditView extends LayoutView {
	protected $filename;
	protected $report_id;
    protected $use_gosttypeb;

	public function customHead():void { ?>
<link rel="stylesheet" href="/css/autogost-report.css">
<?php }

	public function customScripts():void { ?>

<script>
const PHP_report_id = <?= $this->report_id ?>;
const PHP_filename = "<?= $this->filename ?>".replace(/[&\/\\#,+()$~%.'":*?<>{}]/g, "");
</script>

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
        <button class='btn' id='btnAddImage'>Добавить изображения</button>
		<button class='btn' id='btnAddTable'>Добавить таблицу</button>
		<a class='btn' href="/autogost/help" target="_blank">
			Помощь
		</a>
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
			<div class='card danger hidden' id="agstErrorsContainer">
				<p id="agstErrors"></p>
			</div>
			<!--Вывод HTML-->
			<div
                id="agstOutput"
                class="<?= $this->use_gosttypeb ? 'fontGostTypeB' : 'fontTimes' ?>">
            </div>
		</div>
		
	</div>

	<div class="text-center w-100">
		<button id="btnToggleSidebar" class='btn m-1' onclick="toggleSidebar()">❌ Закрыть панель инструментов</button>
	</div>
</div>

<?php }
}
