<?php
namespace Pockit\Views;

// Архив AutoGost

class AutoGostArchiveView extends LayoutView {
	protected $subjects;

    public function customScripts() { ?>
<script src="/js/archiveView.js"></script>
	
	<?php } public function content() : void { ?>

<div class='card m-1'>
	<h1 class='text-center card-title'>Архив отчётов</h1>
    <div id='loading' class='text-center'>
        <p class='mono'>Загрузка...</p>
        <div class='loader'></div>
    </div>
	<div id='lvSubjects'></div>

	<button id='btnAdd' class='btn success w-100'>Добавить дисциплину</button>
</div>

    <?php }}
