<?php
namespace Pockit\Controllers;

// Контроллер API

use Pockit\Common\Database;

use Pockit\Models\Subject;
use Pockit\Models\Report;
use Pockit\Models\Teacher;
use Pockit\Models\WorkType;
use Pockit\Models\Password;
use Pockit\Models\Link;

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
	// Получение отчёта
	public static function getReport() {
		$data = json_decode(file_get_contents("php://input"), true);
		$report = ReportModel::getById($data['id']);
		echo json_encode($report);
	}

	// Получение всех преподавателей
	public static function getTeachers() {
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

	// Получение всех типов работ
	public static function getWorkTypes() {
		//~ $worktypes = WorkTypeModel::all();
		//~ $output = [];
		//~ while ($worktype = $worktypes->fetchArray(SQLITE3_ASSOC)) {
			//~ $worktype['repr'] = $worktype['name_nom'];
			//~ $output[] = $worktype;
		//~ }
		//~ echo json_encode($output);
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
        ThemeModel::deleteById($_GET['id']);
    }
	#endregion

	#region THEMES
	public static function addThemeFromZip() {
		if (!isset($_FILES['themeFile'])) {
			self::echoError('Не выбран файл темы');
			exit();
		}
		
		if (!is_uploaded_file($_FILES['themeFile']['tmp_name'])) {
			switch ($_FILES['themeFile']['error']) {
				case 0:
					$msg = "Обнаружена проблема с вашим файлом.";
					break;
				case 1:
				case 2:
					$msg = "Слишком большой файл";
					break;
				case 3:
					$msg = "Файл загружен только частично";
					break;
				case 4:
					$msg = "Вы должны загрузить файл";
					break;
				default:
					$msg = "Обнаружена проблема с вашим файлом";
					break;
			}
			self::echoError($msg);
			exit();
		}

		$zip = new \ZipArchive;
		$result = $zip->open($_FILES['themeFile']['tmp_name']);
		if ($result !== true) {
			self::echoError('Не удалось открыть архив');
			$zip->close();
			exit();
		}

		// Получение файла с цветами
		$theme_config = $zip->getFromName('theme.json');
		if ($theme_config === false) {
			self::echoError('Не найден theme.json');
			$zip->close();
			exit();
		}

		// TODO: Чтение изображений домашней страницы
		// TODO: Чтение изображений действий
		// TODO: Чтение шрифтов
        $zip->close();

        // -- Генерация CSS кода --
        $theme_spec = json_decode($theme_config, true);
        $css = ':root{';
        // color-scheme
        $css .= 'color-scheme: '.$theme_spec['color-scheme'].';';
        // --var
        foreach ($theme_spec['colors'] as $variable => $color_code) {
            $css .= '--'.$variable.':'.$color_code.';';
        }
        $css .= '}';

        // Добавление в базу данных
        $theme_id = ThemeModel::create(
            $theme_spec['name'],
            $theme_spec['author'],
            $css);
        $created_theme = ThemeModel::getById($theme_id);
        echo json_encode(['ok' => true, 'object' => $created_theme]);
	}

    // Активирует определённую тему
    public static function activateTheme($theme_id) {
        // Получаем CSS данные темы
        $theme = ThemeModel::getById($theme_id);

        // Перезаписываем CSS файл
        if ($theme !== false) {
            file_put_contents(
                index_dir . '/wwwroot/css/theme.css',
                $theme['css']);
        }

        header("Location: /settings/themes");
    }
	#endregion

	#region UTILS
	private static function echoError(string $error_message) {
		echo json_encode(['ok'=>false, 'message'=>$error_message]);
	}
	#endregion
}
