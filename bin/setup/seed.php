<?php
// Запись первоначальных данных
require_once __DIR__ . '/../../src/bootstrap.php';

use Pockit\Common\Database;
use Pockit\Models\WorkType;
use Pockit\Models\Teacher;
use Pockit\Models\Theme;
use Pockit\Models\Setting;

function databaseSeed() {
    $em = Database::getEm();

    // -- Типы работ --
    $wt_lab = new WorkType();
    $wt_lab->setNameNom('ЛАБОРАТОРНАЯ РАБОТА');
    $wt_lab->setNameGen('ЛАБОРАТОРНОЙ РАБОТЫ');
    $wt_lab->setHidden(false);

    $wt_pra = new WorkType();
    $wt_pra->setNameNom('ПРАКТИЧЕСКАЯ РАБОТА');
    $wt_pra->setNameGen('ПРАКТИЧЕСКОЙ РАБОТЫ');
    $wt_pra->setHidden(false);

    $em->persist($wt_lab);
    $em->persist($wt_pra);

    // -- Преподаватели --
    $teachers = [
        ["Пивоваров", "Сергей", "Александрович"],
        ["Галимова", "Екатерина", "Валерьевна"],
        ["Ильина", "Светлана", "Анатольевна"],
        ["Немтинова", "Екатерина", "Александрова"]
    ];
    foreach ($teachers as $teacher) {
        $obj = new Teacher();
        $obj->setName($teacher[1]);
        $obj->setSurname($teacher[0]);
        $obj->setPatronymic($teacher[2]);
        $em->persist($obj);
    }

    // -- Темы --
    // Тёмная
    $dark_theme = new Theme();
    $dark_theme->setName('Pockit стандарт тёмная');
    $dark_theme->setAuthor('Разработчик');
    $dark_theme_css = file_get_contents(__DIR__ . '/themes/dark.css');
    $dark_theme->setCss($dark_theme_css);
    copy(
        __DIR__ . '/themes/pockitDark.jpg',
        index_dir . '/wwwroot/img/home/pockitDark.jpg');
    $dark_theme->setHomeBgLocation('pockitDark.jpg');
    $dark_theme->setCanBeDeleted(false);

    // Светлая
    $light_theme = new Theme();
    $light_theme->setName('Pockit стандарт светлая');
    $light_theme->setAuthor('Разработчик');
    $light_theme_css = file_get_contents(__DIR__ . '/themes/light.css');
    $light_theme->setCss($light_theme_css);
    copy(
        __DIR__ . '/themes/pockitLight.jpg',
        index_dir . '/wwwroot/img/home/pockitLightHome.jpg');
    $light_theme->setHomeBgLocation('pockitLightHome.jpg');
    $light_theme->setCanBeDeleted(false);

    $em->persist($dark_theme);
    $em->persist($light_theme);

    // -- Настройки --
    $settings = [
        1,                  // Id активной темы
        0,                  // Была ли выполнена первоначальная настройка,
        '<placeholder>',    // UserName
        '<placeholder>',    // JournalLogin
        '<placeholder>',    // JournalPassword
        1,                  // JournalPeriodId
        '<placeholder>',    // AgstGroup
        '<placeholder>',    // AgstCode
        '<placeholder>',    // AgstSurname
        '<placeholder>',    // AgstFull
        0                   // AgstUseGostTypeB
    ];
    foreach ($settings as $setting) {
        $obj = new Setting();
        $obj->setValue($setting);
        $em->persist($obj);
    }

    $em->flush();
}

databaseSeed();
