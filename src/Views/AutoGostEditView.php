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
<?php }

	public function content():void { ?>

<div id='control-sidebar' class='card'>
	
	<button class='btn selected' id='switchMarkup'>Разметка</button>
	<button class='btn' id='switchPreview'>Превью</button>
	<button class='btn' id="printReport">Печать</button>
	<button class='btn' id="getFilename">Получить название файла</button>
	<button class='btn success' id='saveMarkupButton'>Сохранить</button>
	
</div>

<div style='margin-left:215px'>
	<div class='card' id="controls">
		<textarea id="markuparea" autocomplete="off"><?= $this->markup ?></textarea>
		<div style='display: none;' id="preview"></div>
	</div>
</div>

<input type='hidden' id='idInput' value='<?= $this->report_id ?>'>
<input type='hidden' id='filename' value='<?= $this->filename ?>'>

<?php }
}
