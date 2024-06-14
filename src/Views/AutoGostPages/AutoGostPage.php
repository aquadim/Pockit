<?php
namespace Pockit\Views\AutoGostPages;

// Страница отчёта Автогоста

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
        static::$work_code = $subject->getCode().$_ENV['autogost_code'];
        static::$teacher_full = $teacher->getFullName();
        static::$author_surname = $_ENV['autogost_surname'];
        static::$author_full = $_ENV['autogost_full'];
        static::$subject = $subject;
        static::$work_type = $work_type;
        static::$author_group = $_ENV['autogost_group'];
        static::$work_number = $report->getWorkNumber();
        static::$teacher_surname = $teacher->getSurname();
        static::$report_date = $report->getDateFor();
    }

    // Номер страницы
    protected $number;
}