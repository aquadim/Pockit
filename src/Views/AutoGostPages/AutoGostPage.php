<?php
// Страница отчёта Автогоста

namespace Pockit\Views\AutoGostPages;

use Pockit\Common\SettingType;
use Pockit\Views\View;

class AutoGostPage extends View {
	
    #region Переменные для представлений
    protected static $months_gen = [
	1=>"января",
	2=>"февраля",
	3=>"марта",
	4=>"апреля",
	5=>"мая",
	6=>"июня",
	7=>"июля",
	8=>"августа",
	9=>"сентября",
	10=>"октября",
	11=>"ноября",
	12=>"декабря"
    ];
    protected static $work_code;
    protected static $teacher_full;
    protected static $author_surname;
    protected static $author_full;
    protected static $subject;
    protected static $work_type;
    protected static $author_group;
    protected static $work_number;
    protected static $teacher_surname;
    protected static $report_date;
    #endregion

    // Инициализирует статичные переменные, необходимые для отображения
    // страниц
    public static function init(
        $subject,
        $teacher,
        $work_type,
        $report
    )
    {
        // Получение данных из настроек
        $code = getSettingValue(SettingType::AgstCode);
        $surname = getSettingValue(SettingType::AgstSurname);
        $agst_full = getSettingValue(SettingType::AgstFull);
        $group = getSettingValue(SettingType::AgstGroup);
        
        static::$work_code = $subject->getCode().$code;
        static::$teacher_full = $teacher->getFullName();
        static::$author_surname = $surname;
        static::$author_full = $agst_full;
        static::$subject = $subject;
        static::$work_type = $work_type;
        static::$author_group = $group;
        static::$work_number = $report->getWorkNumber();
        static::$teacher_surname = $teacher->getSurname();
        static::$report_date = $report->getDateFor();
    }

    // Номер страницы
    protected $number;
}