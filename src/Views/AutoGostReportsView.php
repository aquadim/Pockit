<?php
// Архив AutoGost - список отчётов

namespace Pockit\Views;

class AutoGostReportsView extends LayoutView {
	protected $subject;

    public function customScripts() { ?>
<script src="/js/archiveReportsView.js"></script>
<script>
const PHP_subject_id = <?= $this->subject->getId() ?>;
</script>
	
	<?php } public function content() : void { ?>

<div class='card m-1'>
    <h1 class='text-center card-title'>Архив отчётов</h1>
    <p class='text-center fg-accent'><?= $this->subject->getMyName() ?></p>
    <div id='loading' class='text-center'>
        <div class='loader'></div>
    </div>
	<div id='lvReports'></div>
	<a
        href="/autogost/new?selected=<?= $this->subject->getId() ?>"
        class="btn success w-100 m-1">
        Добавить
    </a>
</div>

    <?php }}
