<?php
// Контроллер API

namespace Pockit\Controllers;

use Pockit\Common\Database;
use Pockit\Common\SettingType;

use Pockit\Models\Subject;
use Pockit\Models\Report;
use Pockit\Models\Teacher;
use Pockit\Models\WorkType;
use Pockit\Models\Password;
use Pockit\Models\Link;
use Pockit\Models\Theme;

class ApiController {

    #region VALIDATE
    // Проверяет запрос на создание/обновление предмета на правильность
    private static function validateSubject() {
        if (!isset($_POST['name']) || $_POST['name'] === '') {
            self::echoError('Не введено название дисциплины');
            exit();
        }
        if (!isset($_POST['code']) || $_POST['code'] === '') {
            self::echoError('Не введён шифр дисциплины');
            exit();
        }
    }
    
    // Проверяет запрос на создание/обновление отчёта на правильность
    private static function validateReport() {
        if (!isset($_POST['workNumber']) || $_POST['workNumber'] === '') {
            self::echoError('Не введён номер работы');
            exit();
        }
        if (!isset($_POST['dateFor']) || $_POST['dateFor'] === '') {
            self::echoError('Не указана дата отчёта');
            exit();
        }
    }
    
    // Проверяет запрос на создание/обновление пароля на правильность
    private static function validatePassword() {
        if (!isset($_POST['name']) || $_POST['name'] === '') {
            self::echoError('Не введено название');
            exit();
        }
        if (!isset($_POST['password']) || $_POST['password'] === '') {
            self::echoError('Не указан пароль');
            exit();
        }
        if (!isset($_POST['secretKey']) || $_POST['secretKey'] === '') {
            self::echoError('Не указан секретный ключ');
            exit();
        }
    }
    
    // Проверяет запрос на создание/обновление ссылки на правильность
    private static function validateLink() {
        if (!isset($_POST['name']) || $_POST['name'] === '') {
            self::echoError('Не введено название');
            exit();
        }
        if (!isset($_POST['href']) || $_POST['href'] === '') {
            self::echoError('Не указано назначение ссылки');
            exit();
        }
    }
    #endregion

	#region CREATE
	// Добавление предмета
	public static function createSubject() {
        self::validateSubject();
        if (!isset($_POST['myName']) || $_POST['myName'] === '') {
            $my_name = $_POST['name'];
        } else {
            $my_name = $_POST['myName'];
        }

        // Поиск препода по ID
        $em = Database::getEm();
        $teacher = $em->find(Teacher::class, $_POST['teacherId']);

        // Создание дисциплины
        $subject = new Subject();
        $subject->setName($_POST['name']);
        $subject->setMyName($my_name);
        $subject->setCode($_POST['code']);
        $subject->setTeacher($teacher);
        $subject->setHidden(false);

        $em->persist($subject);
        $em->flush();

        echo json_encode(['ok'=>true, 'obj'=>$subject->toArray()]);
	}
	
	// Добавление ссылки
	public static function createLink() {
        self::validateLink();
        $em = Database::getEm();
        $link = new Link();
        $link->setName($_POST['name']);
        $link->setHref($_POST['href']);
        $link->setHidden(false);

        $em->persist($link);
        $em->flush();

        echo json_encode(['ok'=>true, 'obj'=>$link->toArray()]);
	}
	
	// Добавление пароля
	public static function createPassword() {
        self::validatePassword();
        $em = Database::getEm();
        $password = new Password();
        $password->setName($_POST['name']);
        $password->setPassword($_POST['password'], $_POST['secretKey']);
        $password->setHidden(false);

        $em->persist($password);
        $em->flush();

        echo json_encode(['ok'=>true, 'obj'=>$password->toArray()]);
	}
	#endregion

	#region READ

	// Получение всех преподавателей
	public static function readTeacher() {
		$em = Database::getEm();
        $query = $em->createQuery(
            'SELECT teacher FROM '.Teacher::class.' teacher '
        );
        $teachers = $query->getResult();

        $output = [];
        foreach ($teachers as $t) {
            $output[] = $t->toArray();
        }
        echo json_encode($output);
	}
    
	// Получение всех ссылок
	public static function readLink() {
		$em = Database::getEm();
        $query = $em->createQuery(
            'SELECT link FROM '.Link::class.' link '.
            'WHERE link.hidden=false'
        );
        $links = $query->getResult();

        $output = [];
        foreach ($links as $l) {
            $output[] = $l->toArray();
        }
        echo json_encode($output);
	}

    // Получение всех дисциплин
    public static function readSubject() {
        $em = Database::getEm();
        $query = $em->createQuery(
            'SELECT subject FROM '.Subject::class.' subject '.
            'WHERE subject.hidden=false'
        );
        $subjects = $query->getResult();

        $output = [];
        foreach ($subjects as $s) {
            $output[] = $s->toArray();
        }
        echo json_encode($output);
    }
    
    // Получение всех типов работ
    public static function readWorkType() {
        $em = Database::getEm();
        $query = $em->createQuery(
            'SELECT workType FROM '.WorkType::class.' workType '
        );
        $work_types = $query->getResult();

        $output = [];
        foreach ($work_types as $wt) {
            $output[] = $wt->toArray();
        }
        echo json_encode($output);
    }
    
    // Получение всех паролей
    public static function readPassword() {
        $em = Database::getEm();
        $query = $em->createQuery(
            'SELECT password FROM '.Password::class.' password WHERE password.hidden=false'
        );
        $passwords = $query->getResult();

        $output = [];
        foreach ($passwords as $p) {
            $output[] = $p->toArray();
        }
        echo json_encode($output);
    }
    
    // Получение всех тем
    public static function readTheme() {
        $em = Database::getEm();
        $query = $em->createQuery(
            'SELECT theme FROM '.Theme::class.' theme'
        );
        $themes = $query->getResult();

        $output = [];
        foreach ($themes as $t) {
            $output[] = $t->toArray();;
        }
        echo json_encode($output);
    }
    
    // Получение всех отчётов по дисциплине
    public static function readReport($subject_id) {
        $em = Database::getEm();
        $subject = $em->find(Subject::class, $subject_id);

        $query = $em->createQuery(
            'SELECT report FROM '.Report::class.' report '.
            'WHERE report.subject=:subject AND report.hidden=false'
        );
        $query->setParameters(['subject'=>$subject]);
		$reports = $query->getResult();

        $output = [];
        foreach ($reports as $r) {
            $output[] = $r->toArray();
        }
        echo json_encode($output);
    }
	
	public static function getReportMarkup($report_id) {
		$em = Database::getEm();
		$report = $em->find(Report::class, $report_id);
		echo json_encode(['ok'=>true, 'markup'=>$report->getMarkup()]);
	}
	#endregion

	#region UPDATE
	// Обновление разметки отчёта
	public static function updateReportMarkup($report_id) {
		$input = json_decode(file_get_contents("php://input"), true);
        $em = Database::getEm();
		$report = $em->find(Report::class, $report_id);
		$report->setMarkup($input['markup']);
        $em->flush();
	}
	
	// Обновление отчёта
	public static function updateReport() {
        self::validateReport();
        $em = Database::getEm();
		$report = $em->find(Report::class, $_POST['reportId']);
        $report->setWorkNumber($_POST['workNumber']);
        $report->setComment($_POST['comment']);
        $report->setDateFor(new \DateTime($_POST['dateFor']));
        $em->flush();
        echo json_encode(['ok'=>true, 'obj'=>$report->toArray()]);
	}

	// Обновление ссылки
	public static function updateLink() {
        self::validateLink();
        $em = Database::getEm();
		$link = $em->find(Link::class, $_POST['linkId']);
        $link->setName($_POST['name']);
        $link->setHref($_POST['href']);
        $em->flush();
        echo json_encode(['ok'=>true, 'obj'=>$link->toArray()]);
	}

	// Обновление предмета
	public static function updateSubject() {
        self::validateSubject();
        if (!isset($_POST['myName']) || $_POST['myName'] === '') {
            $my_name = $_POST['name'];
        } else {
            $my_name = $_POST['myName'];
        }
        
		$em = Database::getEm();
        $subject = $em->find(Subject::class, $_POST['subjectId']);
        $teacher = $em->find(Teacher::class, $_POST['teacherId']);

        // Создание дисциплины
        $subject->setName($_POST['name']);
        $subject->setMyName($my_name);
        $subject->setCode($_POST['code']);
        $subject->setTeacher($teacher);
        $em->flush();

        echo json_encode(['ok'=>true, 'obj'=>$subject->toArray()]);
	}
	#endregion

	#region DELETE
	// Удаление предмета
	public static function deleteSubject() {
		$em = Database::getEm();
        $subject = $em->find(Subject::class, $_GET['id']);
        $subject->setHidden(true);
        $em->flush();
	}
	
	// Удаление ссылки
	public static function deleteLink() {
        $em = Database::getEm();
        $subject = $em->find(Link::class, $_GET['id']);
        $subject->setHidden(true);
        $em->flush();
	}
	
	// Удаление пароля
	public static function deletePassword() {
		$em = Database::getEm();
        $subject = $em->find(Password::class, $_GET['id']);
        $subject->setHidden(true);
        $em->flush();
	}

	// Удаление отчёта
	public static function deleteReport() {
		$em = Database::getEm();
        $report = $em->find(Report::class, $_GET['id']);
        $report->setHidden(true);
        $em->flush();
	}

    // Удаление темы
    public static function deleteTheme() {
        $em = Database::getEm();
        $theme = $em->find(Theme::class, $_GET['id']);
        $em->remove($theme);
        $em->flush();
    }
	#endregion

	#region THEMES
	public static function addThemeFromZip() {
		if (!isset($_FILES['themeFile'])) {
			self::echoError('Не выбран файл темы');
			exit();
		}
		
		if (!is_uploaded_file($_FILES['themeFile']['tmp_name'])) {
			$msg = getFileUploadErrorText($_FILES['themeFile']['error']);
			self::echoError($msg);
			exit();
		}

		$zip = new \ZipArchive;
		$result = $zip->open($_FILES['themeFile']['tmp_name']);
		if ($result !== true) {
			self::echoError('Не удалось открыть архив');
			exit();
		}

		// Получение файла с цветами
		$theme_config = $zip->getFromName('theme.json');
		if ($theme_config === false) {
			self::echoError('Не найден theme.json');
			$zip->close();
			exit();
		}

        $theme_spec = json_decode($theme_config, true);

        // -- Проверка необходимых ключей --
        $required_keys = [
            'color-scheme',
            'colors',
            'homeBgSource',
            'homeBgDest'];
        foreach ($required_keys as $key) {
            if (!isset($theme_spec[$key])) {
                self::echoError('Повреждён файл темы. Не найден ключ '.$key);
                exit();
            }
        }

        // -- Генерация CSS кода --
        $css = ':root{';
        $color_keys = [
            "accent", "crumbsBg", "crumbsFg", "pageBg", "pageFg",
            "cardBg", "cardBorder", "formInputBg", "formInputHighlight",
            "formInputFg", "formInputFocusedFg", "btnBg", "btnFg",
            "btnBorder", "btnHoverBg", "btnHoverFg", "btnFocusBg",
            "btnFocusFg", "btnDisabledBg", "btnDisabledFg", "btnDisabledBorder",
            "listBg", "listBorder", "suggestedBg", "suggestedFg",
            "suggestedHoverBg", "suggestedHoverFg", "suggestedFocusBg",
            "suggestedFocusFg", "suggestedBorder", "destructiveBg",
            "destructiveFg", "destructiveHoverBg", "destructiveHoverFg",
            "destructiveFocusBg", "destructiveFocusFg", "destructiveBorder",
            "sidebarBg", "sidebarWidth", "homeBgColor"
        ];
        // color-scheme
        $css .= 'color-scheme: '.$theme_spec['color-scheme'].';';
        // --var
        foreach ($theme_spec['colors'] as $variable => $color_code) {
            if (!in_array($variable, $color_keys)) {
                // Ключ в теме какой то странный, такого не должно быть
                // не продолжаем выполнение!
                self::echoError(
                    'Повреждён файл темы. Неизвестный ключ цвета '.
                    htmlspecialchars($variable));
                exit();
            }
            $css .= '--'.$variable.':'.$color_code.';';
        }

        // -- Изображение главной страницы --
        $home_bg = $zip->getFromName($theme_spec['homeBgSource']);
        file_put_contents(
            index_dir.'/wwwroot/img/home/'.$theme_spec['homeBgDest'],
            $home_bg);
        $css .= '--homeBgImage:url("/img/home/'.$theme_spec['homeBgDest'].'");';
        
        $zip->close();
        $css .= '}';

        // Добавление в базу данных
        $theme = new Theme();
        $theme->setName($theme_spec['name']);
        $theme->setAuthor($theme_spec['author']);
        $theme->setCss($css);
        $theme->setHomeBgLocation($theme_spec['homeBgDest']);
        $theme->setCanBeDeleted(true);

        $em = Database::getEm();
        $em->persist($theme);
        $em->flush();
        
        echo json_encode(['ok' => true, 'obj' => $theme->toArray()]);
	}

    // Активирует определённую тему
    public static function activateTheme($theme_id) {
        // Получаем CSS данные темы
        $em = Database::getEm();
        $theme = $em->find(Theme::class, $theme_id);

        if ($theme === null) {
            self::echoError('Тема не существует');
            exit();
        }

        // Перезаписываем CSS файл темы
        if ($theme !== false) {
            file_put_contents(
                index_dir . '/wwwroot/css/theme.css',
                $theme->getCss());
        }

        // Устанавливаем настройку текущей темы
        setSettingValue(SettingType::ActiveThemeId, $theme_id);

        // Отправляем обратно, либо выводим сообщение об успехе
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: '.$_SERVER['HTTP_REFERER']);
        }
        header('Content-Type: application/json');
        echo json_encode(['ok'=>true]);
    }
	#endregion

	#region UTILS
	private static function echoError(string $error_message) {
        header('Content-Type: application/json');
		echo json_encode(['ok'=>false, 'message'=>$error_message]);
	}
	#endregion
}
