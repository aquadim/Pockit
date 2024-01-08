<?php
// Страница редактирования отчёта

class RegenEditView extends LayoutView {
	protected $markup;
	protected $filename;
	protected $report_id;

	public function customHead():void { ?>
<link rel="stylesheet" href="/css/regen-report.css">
<?php }

	public function content():void { ?>

<div class='card' id="controls">
	<div id='control-buttons'>
		<button id='switchMarkup'>Разметка</button>
		<button id='switchPreview'>Превью</button>
		<button id="printReport">Печать</button>
	</div>

	<textarea onkeyup="textAreaAdjust(this)" id="markuparea" autocomplete="off"><?= $this->markup ?></textarea>
	<div style='display: none;' id="preview"></div>
</div>

<div class='card no-print'>
	<form id='saveForm'>
		<input id='idInput' type='hidden' name='id' value='<?=$this->report_id?>'>
		<button id='saveMarkupButton' class='form-control createbutton'>Сохранить</button>
	</form>
	<p>Название файла: <span class='filename'><?= $this->filename ?></span></p>
</div>
 
<script src="/js/regenpreview.js"></script>
<?php }
}
