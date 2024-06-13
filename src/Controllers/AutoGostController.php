<?php
namespace Pockit\Controllers;

// Контроллер автогоста

use Pockit\Models\Report;
use Pockit\Models\Subject;
use Pockit\Models\WorkType;
use Pockit\Models\Teacher;

use Pockit\Views\AutoGostArchiveView;
use Pockit\Views\AutoGostReportsView;
use Pockit\Views\AutoGostEditView;
use Pockit\Views\AutoGostNewReportView;
use Pockit\Views\AutoGostHelpView;

use Pockit\Views\AutoGostPages\AutoGostPage;

use Pockit\AutoGostSections\Section;
use Pockit\AutoGostSections\TitleSection;
use Pockit\AutoGostSections\PracticeTitleSection;
use Pockit\AutoGostSections\SubSection;

use Pockit\Common\Database;
use Pockit\Common\AgstException;

class AutoGostController {

	// Применение правил подписи рисунков
	// https://www.php.net/manual/ru/function.ucfirst.php#84122
	private static function makeValidPictureTitle(string $title) : string {
		return mb_strtoupper(mb_substr($title, 0, 1)) .
			mb_strtolower(mb_substr($title, 1));
	}

	// Помощь
	public static function help() {
		$view = new AutoGostHelpView([
			"crumbs" => ["Главная" => "/", "Автогост: помощь" => ""]
		]);
		$view->view();
	}

	// Загрузка изображений
	public static function uploadImage() {
		if (is_uploaded_file($_FILES['file']['tmp_name'])) {
			$mime_type = mime_content_type($_FILES['file']['tmp_name']);
			$filepath = index_dir."/wwwroot/img/autogost/agstupload".uniqid();

			if ($mime_type == "image/png") {
				// Конвертирование png в gif
				$png_image = imagecreatefrompng($_FILES['file']['tmp_name']);
				$gif_image = imagecreatetruecolor(imagesx($png_image), imagesy($png_image));
				imagecopy($gif_image, $png_image, 0, 0, 0, 0, imagesx($png_image), imagesy($png_image));
				imagegif($gif_image, $filepath);
			} else {
				// Просто перемещение файла
				move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
			}

			$output = [
				"ok" => true,
				"filename"=> basename($filepath),
				"clientName" => pathinfo($_FILES['file']['name'], PATHINFO_FILENAME)
			];
		} else {
			$output = ["ok"=>false];
		}
		echo json_encode($output);
	}

	// Список отчётов по дисциплине
	public static function listReports($subject_id) {
		$em = Database::getEm();

        // Поиск предмета
		$subject = $em->find(Subject::class, $subject_id);

		$view = new AutoGostReportsView([
			"page_title" => "Автогост: архив ".$subject->getMyName(),
			"crumbs" => [
                "Главная" => "/",
                "Автогост: дисциплины" => "/autogost/archive",
                $subject->getMyName() => ""],
			"subject" => $subject
		]);
		$view->view();
	}

	// Архив отчётов
	public static function archive() {
		$view = new AutoGostArchiveView([
			"page_title" => "Автогост: архив",
			"crumbs" => ["Главная" => "/", "Автогост: дисциплины" => "/"]
		]);
		$view->view();
	}

	// Редактирование отчёта
	public static function edit($report_id) {
        $em = Database::getEm();
		$report = $em->find(Report::class, $report_id);
        $subject = $report->getSubject();
		$filename =
        "Автогост - ".$subject->getName()." #".$report->getWorkNumber().
        " - ".$_ENV['autogost_surname'];

		$view = new AutoGostEditView([
			"page_title" => $filename,
			"crumbs" => [
                "Главная"=>"/",
                "Автогост: дисциплины" => "/autogost/archive/",
                $subject->getName() => "/autogost/archive/".$subject->getId(),
                "Редактирование"=>""
            ],
			"filename" => $filename,
			"report_id" => $report_id
		]);
		$view->view();
	}

	// Валидация создания отчёта
	private static function validateCreation() {
		if (mb_strlen($_POST["number"]) == 0) {
			return 'Не указан номер работы';
		}
		return true;
	}

	// Создание отчёта
	public static function newReport() {
        $em = Database::getEm();

        if (!empty($_POST)) {
			$response = self::validateCreation();
			if ($response === true) {
				// Валидация успешна
				// Создаём запись
                $subject = $em->find(Subject::class, $_POST['subject_id']);
				$work_type = $em->find(WorkType::class, $_POST['work_type']);

                $report = new Report();
                $report->setSubject($subject);
                $report->setComment($_POST['notice']);
                $report->setCreatedAt(new \DateTime('now'));
                $report->setWorkType($work_type);
                $report->setMarkup(
                "@titlepage\n".
                "@section:".$work_type->getNameNom()." №".$_POST['number']."\n".
                "@section:Ответы на контрольные вопросы"
                );
                $report->setWorkNumber($_POST['number']);
                $report->setDateFor(new \DateTime($_POST['date_for']));
                $report->setHidden(false);

                $em->persist($report);
                $em->flush();

                $report_id = $report->getId();

				// Перенаправляем на предпросмотр этого отчёта
				header("Location: /autogost/edit/".$report_id);
                exit();
				return;
			} else {
				// Валидация провалена
				$error_text = $response;
			}
		} else {
			$error_text = null;
		}

        // Поиск всех предметов
        $squery = $em->createQuery(
            'SELECT s FROM '.Subject::class.' s WHERE s.hidden=false'
        );
        $subjects = $squery->getResult();

        // Поиск всех типов работ
        $wtquery = $em->createQuery(
            'SELECT wt FROM '.WorkType::class.' wt'
        );
        $work_types = $wtquery->getResult();

		// Дисциплина по умолчанию
		if (isset($_GET['selected'])) {
			$selected = $_GET['selected'];
		} else {
			$selected = -1;
		}
		
		$view = new AutoGostNewReportView([
			"page_title" => "Автогост: создание отчёта",
			'subjects' => $subjects,
			'work_types' => $work_types,
			'error_text' => $error_text,
			"crumbs" => ["Главная"=>"/", "Автогост: создание отчёта" => ""],
			"selected" => $selected
		]);
		$view->view();
	}

	// Получение HTML
	public static function getHtml() {

		$input		= json_decode(file_get_contents("php://input"), true);
		$report 	= ReportModel::getById($input['report_id']);
		$subject 	= SubjectModel::getById($report["subject_id"]);
		$work_type	= WorkTypeModel::getById($report["work_type"]);
		$teacher	= TeacherModel::getById($subject["teacher_id"]);

		try {
			self::echoReportHTML($report, $subject, $work_type, $teacher);
		} catch (AgstException $ex) {
			http_response_code(400);
			echo json_encode([
				"line"=>$ex->getErrorLine(),
				"text"=>$ex->getUserMessage()
			]);
		}
	}

	// Возвращает файл HTML для скачивания
	public static function jsHTML($report_id) {

		$report 	= ReportModel::getById($report_id);
		$subject 	= SubjectModel::getById($report["subject_id"]);
		$work_type	= WorkTypeModel::getById($report["work_type"]);
		$teacher	= TeacherModel::getById($subject["teacher_id"]);
		$filename 	= "Автогост - ".$subject['name']." #".$report['work_number']." - ".$_ENV['autogost_surname'];

		header('Content-Type: text/html');
		header('Content-Disposition: attachment; filename="'.$filename.'"');

		// Читаем стили
		$styles = file_get_contents(index_dir.'/wwwroot/css/portable.css');

		// Добавляем структуру чтобы был нормальный HTML
		echo '
		<html>
			<head>
				<style>'.
				// Стили
				$styles
				.'</style>
			</head>
		<body>';

		self::echoReportHTML($report, $subject, $work_type, $teacher, true);

		echo '</body></html>';
	}

	// Печатает HTML отчёта
	// $img_as_b64 -- кодировать ли изображения в base64
	private static function echoReportHTML(
		$report, $subject, $work_type, $teacher, $img_as_b64=false)
	{
		$document 				= [];		// Секции документа
		$current_img 			= 1; 		// Номер текущего рисунка
		$current_section_index 	= -1;		// Индекс текущей секции в документе
		$lines 					= explode("\n", $report['markup']);
		$line_num 				= 0;		// Номер обрабатываемой строки
		$page_added				= false;	// Добавлена ли какая-либо страница?

		// Является ли текущее выражение строкой таблицы?
		$expr_is_table			= false;
		// Номер текущей таблицы
		$current_table			= 1;
		// Разделитель данных текущей таблицы
		$current_table_delim	= '';
		// Номер строки, на которой объявлена текущая таблица
		$current_table_line_num	= 0;
		// Идентификатор текущей таблицы
		$current_table_class	= '';

		foreach ($lines as $expr) {
			$line_num++;
			if (mb_strlen($expr) == 0) {
				continue;
			}

			if (!$page_added && !self::isSectionCommand($expr)) {
				// Ни одной страницы не было добавлено
				// Сейчас добавляется НЕ страница
				// Это ошибка
				throw new AgstException(
					"Необходимо добавить секции прежде чем писать разметку",
					$line_num);
			}

			if ($expr[0] != "@") {
				// Выражение - обычный текст
				if ($expr_is_table) {
					// Текущая строка - данные колонок таблиц
					// Разделить текст на колонки в соответствии с
					// current_table_delim
					$columns = str_getcsv($expr, $current_table_delim);
					$HTML = "<tr>";
					foreach ($columns as $col) {
						$HTML .= "<td>".$col."</td>";
					}
					$HTML .= "</tr>";
				} else {
					// Текущая строка - абзац текста
					$HTML = "<p class='report-text'>".$expr."</p>";
				}

				$document[$current_section_index]->addHTML(
					$HTML,
					$line_num
				);
				
				continue;
			} else {
				// Выражение - ключевое слово

				if ($expr_is_table && $expr != "@endtable" && $expr != "@-") {
					// Ключевое слово в таблице - нельзя
					// Кроме: разрыв страницы (@-)
					throw new AgstException(
						"В разметке таблицы запрещены ключевые слова кроме ".
						"разрыва таблицы (@-)",
						$line_num
					);
				}
			}

			$command = explode(":", $expr);
			$command_name = $command[0];
			switch ($command_name) {
				case "@titlepage":
					// Титульный лист
					$document[] = new TitleSection();
					$current_section_index++;
					$page_added = true;
					break;

				case "@practicetitle":
					// Титульный лист практики
					$document[] = new PracticeTitleSection();
					$current_section_index++;
					$page_added = true;
					break;

				case "@section":
					// Секция основной части
					$document[] = new SubSection($command[1]);
					$current_section_index++;
					$page_added = true;
					break;

				case "@img":
					// Изображение

					// Проверить количество аргументов - должно быть минимум 2
					if (count($command) < 3) {
						throw new AgstException(
							"Недостаточно параметров для создания изображения. ".
							"Укажите как минимум источник и подпись ".
							"данных. Например: @img:источник:подпись",
							$line_num
						);
					}

					if (mb_strlen($command[1]) == 0) {
						throw new AgstException(
							"Не указан источник изображения",
							$line_num);
					}

					if (mb_strlen($command[2]) == 0) {
						throw new AgstException(
							"Не указана подпись изображения",
							$line_num);
					}
					
					if (count($command) >= 4) {
						$imgwidth = "width='".$command[3]."'";
					} else {
						$imgwidth = "";
					}

					$pictitle = self::makeValidPictureTitle($command[2]);

					$path = index_dir.'/wwwroot/img/autogost/'.$command[1];
					if (!file_exists($path)) {
						throw new AgstException(
							"Изображение по пути \"".$path."\" не найдено",
							$line_num
						);
					}

					if ($img_as_b64) {
						$type = pathinfo($path, PATHINFO_EXTENSION);
						$data = file_get_contents($path);
						$src = 'data:image/' . $type . ';base64,' . base64_encode($data);
					} else {
						$src = '/img/autogost/'.$command[1];
					}

					$document[$current_section_index]->addHTML(
						"<figure>
							<img ".$imgwidth." src='".$src."'>
							<figcaption>Рисунок ".$current_img." - ".$pictitle."</figcaption>
						</figure>",
						$line_num
					);
					$current_img++;
					break;

				case "@@":
					// Комментарий
					break;

				case "@/":
				case "@-":
					// Разрыв страницы

					if ($expr_is_table) {
						// Разрыв страницы в таблице
						// Надо закрыть тэг таблицы, а после добавления страницы
						// Добавить абзац с текстом продолжения
						$document[$current_section_index]->addHTML(
							"</table>",
							$line_num);
					}

					$document[$current_section_index]->pageBreak($line_num);
					$page_added = true;

					if ($expr_is_table) {
						// Продолжение таблицы
						$document[$current_section_index]->addHTML(
							'<p>Продолжение таблицы '.$current_table.'</p>'.
							'<table class="'.$current_table_class.'">',
							$line_num);
					}
					break;

				case "@table":
					// Начало таблицы

					// Проверить количество аргументов - должно быть минимум 2
					if (count($command) < 3) {
						throw new AgstException(
							"Недостаточно параметров для создания таблицы. ".
							"Укажите как минимум название и разделитель ".
							"данных. Например: @table:подпись:,",
							$line_num
						);
					}

					if (mb_strlen($command[1]) == 0) {
						throw new AgstException(
							"Не указана подпись таблицы",
							$line_num);
					}

					if (mb_strlen($command[2]) == 0) {
						throw new AgstException(
							"Не указан разделитель данных в таблице",
							$line_num);
					}
					
					$expr_is_table = true;
					$current_table_delim = $command[2];
					$current_table_line_num = $line_num;
					$current_table_class = uniqid('table');

					$document[$current_section_index]->addHTML(
						"<p>Таблица ".$current_table.' - '.
						self::makeValidPictureTitle($command[1])."</p>".
						"<table class=".$current_table_class.">",
						$line_num
					);
					break;

				case "@endtable";
					// Конец таблицы
					$expr_is_table = false;

					$document[$current_section_index]->addHTML(
						"</table>",
						$line_num
					);
					break;
				
				default:
					throw new AgstException(
						"Неизвестная команда: ".$command_name,
						$line_num
					);
					break;
			}
		}

		if ($expr_is_table) {
			// Отчёт закончился, а таблица не закрыта!
			throw new AgstException(
				"Таблица, объявленная на строке ".$current_table_line_num.
				" не закрыта",
				$line_num
			);
		}

		AutoGostPage::init(
			$subject,
			$teacher,
			$work_type,
			$report
		);
		foreach ($document as $section) {
			$section->output();
		}
	}

	// Проверка: является ли строка командой добавления секции
	private static function isSectionCommand(string $expr) : bool {
		return preg_match('/^@(titlepage|practicetitle|section)/', $expr) === 1;
	}
}
