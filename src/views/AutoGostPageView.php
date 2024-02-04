<?php
// Страница отчёта Автогоста

class AutoGostPageView extends View {
	// Месяцы в родительном падеже
	protected $months_gen = [1=>"января",2=>"февраля",3=>"марта",4=>"апреля",5=>"мая",6=>"июня",7=>"июля",8=>"августа",9=>"сентября",10=>"октября",11=>"ноября",12=>"декабря"];
	public $work_code;
	public $author_surname;
	public $author_full;
	public $subject;
	public $work_type;
	public $pages_count;
	public $author_group;
	public $work_number;
	public $current_page_number;
	public $current_page_marker;
	public $page_content;
	public $teacher_full;
	public $teacher_surname;

	public function view():void { ?>
<!--Страница <?= $this->current_page_number ?>-->
<div class='page-container'>
	<?php switch ($this->current_page_marker) { case '!': ?><!--Страница с большой рамкой-->
	<div class="page big">
		<span class="co"><?= $this->work_code ?></span>
		<span class="iz"></span>
		<span class="ls"></span>
		<span class="nd"></span>
		<span class="pd"></span>
		<span class="dt"></span>
		<span class="rz"></span>
		<span class="sr"><?= $this->author_surname ?></span>
		<span class="lt"></span>
		<span class="pl"></span>
		<span class="al"></span>
		<span class="pr"></span>
		<span class="st"><?= $this->teacher_surname ?></span>
		<span class="cp"><?= $this->current_page_number ?></span>
		<span class="pc"><?= $this->pages_count ?></span>
		<span class="nm"><?= $this->work_type['name_nom'] . ' №'.$this->work_number ?></span>
		<span class="gr">ВПМТ <?= $this->author_group ?></span>
		<span class="nc"></span>
		<span class="ut"></span>
		<?= $this->page_content ?>
	
	</div><?php break; case '!!': ?><!--Страница с маленькой рамкой-->
	<div class="page small">
		<span class="iz"></span>
		<span class="ls"></span>
		<span class="nd"></span>
		<span class="pd"></span>
		<span class="dt"></span>
		<span class="co"><?= $this->work_code ?></span>
		<span class="pl"></span>
		<span class="cp"><?= $this->current_page_number ?></span>
		<?= $this->page_content ?>
	
	</div><?php break; case '!-': ?><!--Титульный лист-->
	<div class='page tpage'>
		<div style='font-size:14pt'>
			<p class='title'>МИНИСТЕРСТВО ОБРАЗОВАНИЯ КИРОВСКОЙ ОБЛАСТИ</p>
			<p class='title'>Кировское областное государственное профессиональное</p>
			<p class='title'>образовательное бюджетное учреждение</p>
			<p class='title'>«Вятско-Полянский механический техникум»</p>
			<p class='title'>(КОГПОБУ ВПМТ)</p>
		</div>
		<div style='margin-top:7cm;font-size:18pt'>
			<p class='title'>ОТЧЁТ О ВЫПОЛНЕНИИ</p>
			<p class='title'><?= $this->work_type['name_gen'] ?> №<?= $this->work_number ?></p>
			<p class='title'>по дисциплине «<?= $this->subject['name'] ?>»</p>
		</div>
		<div style='font-size:14pt;margin-top:3cm'>
			<p class='r'>Выполнил студент</p>
			<p class='r'>группы <?= $this->author_group ?></p>
			<p class='r'><?= $this->author_full ?></p>
			<p class='r'>Проверил преподаватель</p>
			<p class='r'><?= $this->teacher_full ?></p>
		</div>
		<div style='font-size:14pt;margin-top:4cm'>
			<p class='title'>г. Вятские Поляны</p>
			<p class='title'><?= date("Y") ?> г.</p>
		</div>
	
	</div><?php break; case '!--': ?><!--Титульный лист (старый)-->
	<div class="page tpage">
		<table>
			<tbody>
				<tr>
					<td width="98" style="border:3pt ridge #000 ;border-right-style:none;padding: 0cm 0.19cm">
						<img src="data:image/gif;base64,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" style='margin:0.19cm;border-width:3px;'>
					</td>

					<td width="462" style="border:3pt ridge #000;border-left-style:none;padding: 0cm 0.19cm">
						<p class="title">МИНИСТЕРСТВО ОБРАЗОВАНИЯ КИРОВСКОЙ ОБЛАСТИ</p>
						<p class="title">Кировское областное государственное профессиональное образовательное бюджетное учреждение «Вятско-Полянский механический техникум»</p>
						<p class="title">(КОГПОБУ ВПМТ)</p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="title" style="font-size: 18pt;margin-top:20%;"><strong>ОТЧЁТ</strong></p>
		<div style="font-size:14pt;">
			<p class="title">по <?= $this->work_type['name_titlepage'] ?></p>
			<p class="title"><?= $this->subject['name'] ?></p>
			<p class="title">Специальность 09.02.07. Информационные системы и программирование</p>
		</div>
		<div style="font-size:12pt;margin-top:30%;">
			<p class='r'>Выполнил:</p>
			<p class='r'>_____________ <?= $this->author_full ?> <?= $this->author_group ?></p>
			<p class='r'><?= '«' . date('j') . '» ' . $this->months_gen[date('n')] . ' ' . date('Y') . ' г.' ?></p>
			<br><br>
			<p class='r'>Руководитель:</p>
			<p class='r'>_____________ <?= $this->teacher_full ?></p>
			<p class='r'>«__» _____________ 2023 г.</p>
		</div>
		
	</div><?php break; case '!0': ?><!--Приложение-->
	<div class="page apage">
		<?= $this->page_content ?>
	
	</div><?php break; default: break; } ?>

</div>
<hr/>
<?php }
}
