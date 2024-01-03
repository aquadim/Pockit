<?php
// Страница редактирования отчёта

class RegenEditView extends LayoutView {
	protected $markup;

	public function customHead():void { ?>
<link rel="stylesheet" href="/css/regen-report.css">
<?php }

	public function content():void { ?>

<div class='card' id="controls">
	<div id='control-buttons'>
		<button id='switchMarkup'>Разметка</button>
		<button id='switchPreview'>Превью</button>
		<button id='printReportButton' onclick="printReport()">Печать</button>
	</div>

	<textarea onkeyup="textAreaAdjust(this)" id="markuparea" autocomplete="off"><?= $this->markup ?></textarea>
	<div id="preview"></div>
</div>

 
<script src="/js/regenpreview.js"></script>
<?php }
}
