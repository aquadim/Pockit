<?php
// Страница редактирования отчёта

class RegenEditView extends LayoutView {
	protected $markup;

	public function customHead():void { ?>
<link rel="stylesheet" href="/css/regen-report.css">
<?php }

	public function content():void { ?>

<div id="tabs">
	<ul>
		<li><a href="#markup"><span>Разметка</span></a></li>
		<li><a href="#preview"><span>Превью</span></a></li>
	</ul>

	<div id="markup">
		<textarea onkeyup="textAreaAdjust(this)" id="markuparea" autocomplete="off"><?= $this->markup ?></textarea>
	</div>

	<div id="preview">
		<div class='preview' id="previewdiv"></div>
	</div>
</div>

<button id='printReportButton' onclick="printReport()">Печать</button>

<div class='preview' id="printpreview"></div>
 
<script src="/js/regenpreview.js"></script>
<?php }
}
