<?php
// Полезные функции

namespace Pockit\Common;

// Возвращает сообщение об ошибке загрузки файла
function getFileUploadErrorText($error_code)
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