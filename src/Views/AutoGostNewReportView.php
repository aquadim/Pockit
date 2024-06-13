<?php
// Страница создания отчёта

namespace Pockit\Views;

class AutoGostNewReportView extends LayoutView {
	protected $subjects;
	protected $work_types;
	protected $error_text;
	protected $selected;

	protected function content():void { ?>

<div class="card m-1">
	<h1 class='text-center card-title'>Создание отчёта</h1>
	
	<?php if (isset($this->error_text)) {?>
		<div class='card error'>
			<?= $this->error_text ?>
		</div>
	<?php } ?>

	<form action="/autogost/new" method="POST">
		<div class="form-control-container">
			<label for="sel-subject_id">Предмет</label>
			<select class="form-control" id="sel-subject_id" name="subject_id">
				<?php foreach ($this->subjects as $s) { ?>
					<option
                        value="<?= $s->getId() ?>"
                        <?= $s->getId() == $this->selected ? "selected" : '' ?>>
                        <?= $s->getMyName() ?>
                    </option>
				<?php } ?>
			</select>
		</div>

		<div class="form-control-container">
			<label for="sel-work_type">Тип работы</label>
			<select class="form-control" id="sel-work_type" name="work_type">
				<?php foreach ($this->work_types as $wt) { ?>
					<option value="<?= $wt->getId() ?>">
                        <?= $wt->getNameNom() ?>
                    </option>
				<?php } ?>
			</select>
		</div>

		<div class="form-control-container">
			<label for="inp-number">Номер работы</label>
			<input class="form-control" id="inp-number" placeholder="Номер работы" type="text" name="number"/>
		</div>

		<div class='form-control-container'>
			<label for='inp-date'>Дата отчёта (ставится в рамки и титульный лист)</label>
			<input
				class='form-control'
				id='inp-date'
				type='date'
				name='date_for'
				value='<?= date('Y-m-d') ?>'/>
		</div>
		
		<div class="form-control-container">
			<label for="inp-notice">Комментарий</label>
			<input class="form-control" id="inp-notice" placeholder="Всё что угодно" type="text" name="notice"/>
		</div>

		<button type="submit" class="btn success w-100 form-control" onclick="this.textContent='Создаём...'">Создать</button>
	</form>
</div>

<?php }} ?>
