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

<div class='card' id="controls">
	<div id='control-buttons'>
		<button class='btn' id='switchMarkup'>Разметка</button>
		<button class='btn' id='switchPreview'>Превью</button>
		<button class='btn' id="printReport">Печать</button>
	</div>

	<textarea id="markuparea" autocomplete="off"><?= $this->markup ?></textarea>
	<div style='display: none;' id="preview"></div>
</div>

<div class='card no-print'>
	<form id='saveForm'>
		<input id='idInput' type='hidden' name='id' value='<?=$this->report_id?>'>
		<button id='saveMarkupButton' class='form-control success' style='width:100%'>Сохранить</button>
	</form>
	<p>Название файла: <span class='filename'><?= $this->filename ?></span></p>
</div>
<?php }
}