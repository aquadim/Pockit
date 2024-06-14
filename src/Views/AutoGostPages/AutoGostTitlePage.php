<?php
namespace Pockit\Views\AutoGostPages;

// Автогост: титульный лист

class AutoGostTitlePage extends AutoGostPage {

    public function view() : void { ?>

<div class='page'>
    <p class='t-center'>МИНИСТЕРСТВО ОБРАЗОВАНИЯ КИРОВСКОЙ ОБЛАСТИ</p>
    <p class='t-center'>Кировское областное государственное профессиональное образовательное бюджетное учреждение</p>
    <p class='t-center'>«Вятско-Полянский механический техникум»</p>
    <p class='t-center'>(КОГПОБУ ВПМТ)</p>

    <div style='margin-top: 5cm;'>
        <p class='t-center'>ОТЧЁТ О ВЫПОЛНЕНИИ</p>
        <p class='t-center'><?= static::$work_type->getNameGen() ?> №<?= static::$work_number ?></p>
        <p class='t-center'>по дисциплине «<?= static::$subject->getName() ?>»</p>
    </div>

    <div style='margin-top: 5cm;'>
        <p class='t-right'>Выполнил студент</p>
        <p class='t-right'>группы <?= static::$author_group ?></p>
        <p class='t-right'><?= static::$author_full ?></p>
        <p class='t-right'>Проверил преподаватель</p>
        <p class='t-right'><?= static::$teacher_full ?></p>
    </div>

    <div style='margin-top: 6cm;'>
        <p class='t-center'>г. Вятские Поляны</p>
        <p class='t-center'><?= date("Y") ?> г.</p>
    </div>
</div>

    <?php }
}