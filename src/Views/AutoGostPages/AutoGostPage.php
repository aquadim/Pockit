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
    protected static $pages_count;
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
        $pages_count,
        $report
    )
    {
        static::$work_code = $subject['code'].$_ENV['autogost_code'];
        static::$teacher_full = $teacher["surname"]." ".mb_substr($teacher['name'],0,1).'. '.mb_substr($teacher['patronymic'],0,1).'.';
        static::$author_surname = $_ENV['autogost_surname'];
        static::$author_full = $_ENV['autogost_full'];
        static::$subject = $subject;
        static::$work_type = $work_type;
        static::$pages_count = $pages_count;
        static::$author_group = $_ENV['autogost_group'];
        static::$work_number = $report['work_number'];
        static::$teacher_surname = $teacher['surname'];
	static::$report_date = \DateTime::createFromFormat('Y-m-d', $report['date_for']);
    }

    // Номер страницы
    protected $number;
}