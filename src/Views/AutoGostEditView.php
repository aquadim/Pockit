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

		<button class='btn' id='btnAddTable'>Добавить таблицу</button>

		<a class='btn' href="/autogost/help" target="_blank">
			Помощь
			<svg
				width="16px"
				height="16px"
				viewBox="0 0 24 24"
				fill="none"
				xmlns="http://www.w3.org/2000/svg">
				<path d="M15.197 3.35462C16.8703 1.67483 19.4476 1.53865 20.9536 3.05046C22.4596 4.56228 22.3239 7.14956 20.6506 8.82935L18.2268 11.2626M10.0464 14C8.54044 12.4882 8.67609 9.90087 10.3494 8.22108L12.5 6.06212" stroke="var(--gray-9)" stroke-width="1.5" stroke-linecap="round"/>
				<path d="M13.9536 10C15.4596 11.5118 15.3239 14.0991 13.6506 15.7789L11.2268 18.2121L8.80299 20.6454C7.12969 22.3252 4.55237 22.4613 3.0464 20.9495C1.54043 19.4377 1.67609 16.8504 3.34939 15.1706L5.77323 12.7373" stroke="var(--gray-9)" stroke-width="1.5" stroke-linecap="round"/>
			</svg>
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
