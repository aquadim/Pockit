<?php
// Полезные функции

use Pockit\Common\Database;
use Pockit\Common\SettingType;
use Pockit\Models\Setting;

if (!function_exists('getFileUploadErrorText')) {
// Возвращает сообщение об ошибке загрузки файла
function getFileUploadErrorText($error_code) {
    switch ($error_code) {
        case 0:
            return "Обнаружена проблема с вашим файлом.";
        case 1:
        case 2:
            return "Слишком большой файл";
        case 3:
            return "Файл загружен только частично";
        case 4:
            return "Вы должны загрузить файл";
        default:
            return "Обнаружена проблема с вашим файлом";
    }
}
}

// Возвращает значение настройки
if (!function_exists('getSettingValue')) {
function getSettingValue(SettingType $setting) {
    $em = Database::getEm();
    $setting = $em->find(Setting::class, $setting->value);
    if ($setting === null) {
        return null;
    }
    return $setting->getValue();
}
}

// Возвращает значение настройки
if (!function_exists('setSettingValue')) {
function setSettingValue(SettingType $setting_type, string $new_value) {
    $em = Database::getEm();
    $setting = $em->find(Setting::class, $setting_type->value);
    if ($setting === null) {
        // В БД такой настройки нет, создаём
        $setting = new Setting();
        $setting->setId($setting_type->value);
        $em->persist($setting);
    }
    $setting->setValue($new_value);
    $em->flush();
}
}