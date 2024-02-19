<?php
namespace Pockit\Controllers;

// Контроллер автогоста

use Pockit\Models\ReportModel;
use Pockit\Models\SubjectModel;
use Pockit\Models\WorkTypeModel;
use Pockit\Models\TeacherModel;

use Pockit\Views\AutoGostArchiveView;
use Pockit\Views\AutoGostReportsView;
use Pockit\Views\AutoGostEditView;
use Pockit\Views\AutoGostNewReportView;

use Pockit\Views\AutoGostPages\AutoGostPage;

use Pockit\AutoGostSections\TitleSection;
use Pockit\AutoGostSections\SubSection;

class AutoGostController {

	// Загрузка изображений
	public static function uploadImage() {

		if (is_uploaded_file($_FILES['file']['tmp_name'])) {
			$mime_type = mime_content_type($_FILES['file']['tmp_name']);
			$filepath = tempnam(rootdir."/img/autogost", "rgnupload");

			if ($mime_type == "image/png") {
				// Конвертирование png в gif
				$png_image = imagecreatefrompng($_FILES['file']['tmp_name']);
				$gif_image = imagecreatetruecolor(imagesx($png_image), imagesy($png_image));
				imagecopy($gif_image, $png_image, 0, 0, 0, 0, imagesx($png_image), imagesy($png_image));
				imagegif($gif_image, $filepath);
			} else {
				// Просто перемещение файла
				$filepath = tempnam(rootdir."/img/autogost", "rgnupload");
				move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
			}

			$output = ["ok"=>true, "filename"=>basename($filepath)];
		} else {
			$output = ["ok"=>false];
		}
		echo json_encode($output);
	}

	// Список отчётов по дисциплине
	public static function listReports($subject_id) {
		
		$reports = ReportModel::where("subject_id", $subject_id);
		$subject = SubjectModel::getById($subject_id);

		$view = new AutoGostReportsView([
			"page_title" => "Автогост: архив ".$subject['name'],
			"crumbs" => ["Главная" => "/", "Автогост: дисциплины" => "/autogost/archive", $subject['name'] => ""],
			"reports" => $reports,
			"subject" => $subject
		]);
		$view->view();
	}

	// Архив отчётов
	public static function archive() {
		$subjects = SubjectModel::all();

		$view = new AutoGostArchiveView([
			"page_title" => "Автогост: архив",
			"crumbs" => ["Главная" => "/", "Автогост: дисциплины" => "/"],
			"subjects" => $subjects
		]);
		$view->view();
	}

	// Редактирование отчёта
	public static function edit($report_id) {
		$report = ReportModel::getById($report_id);
		$subject = SubjectModel::getById($report['subject_id']);
		$filename = "Автогост - ".$subject['name']." #".$report['work_number']." - Королёв";

		$view = new AutoGostEditView([
			"page_title" => $filename,
			"crumbs" => ["Главная"=>"/", "Автогост: дисциплины" => "/autogost/archive/", $subject['name'] => "/autogost/archive/".$subject['id'], "Редактирование"=>"/"],
			"markup" => $report['markup'],
			"filename" => $filename,
			"report_id" => $report_id
		]);
		$view->view();
	}

	// Валидация создания отчёта
	private static function validateCreation() {
		if (empty($_POST["number"])) {
			// Запрос на создание
			return 'Не указан номер работы';
		}
		return true;
	}

	// Создание отчёта
	public static function newReport() {
		$subjects = SubjectModel::all();
		$worktypes = WorkTypeModel::all();

		if (!empty($_POST)) {
			$response = self::validateCreation();
			if ($response === true) {
				// Валидация успешна

				// Создаём запись
				$work_type = WorkTypeModel::getById($_POST['work_type']);
				
				$report_id = ReportModel::create(
					$_POST["subject_id"],
					$_POST['work_type'],
					$_POST['number'],
					$_POST['notice'],
					"!-\n!\n#{$work_type['name_nom']} №{$_POST['number']}\n"
				);

				// Перенаправляем на предпросмотр этого отчёта
				header("Location: /autogost/edit/".$report_id);
				return;
			} else {
				// Валидация провалена
				$error_text = $response;
			}
		} else {
			$error_text = null;
		}
		
		$view = new AutoGostNewReportView([
			"page_title" => "Автогост: создание отчёта",
			'subjects' => $subjects,
			'worktypes' => $worktypes,
			'error_text' => $error_text,
			"crumbs" => ["Главная"=>"/", "Автогост: создание отчёта" => "/"]
		]);
		$view->view();
	}

	// Получение HTML
	public static function getHtml() {
		$report 	= ReportModel::getById($_GET['report_id']);
		$subject 	= SubjectModel::getById($report["subject_id"]);
		$work_type	= WorkTypeModel::getById($report["work_type"]);
		$teacher	= TeacherModel::getById($subject["teacher_id"]);

		// TODO подсчёт количества страниц
		$pages_count = -999;

		$document = [];
		$current_page = 1;
		AutoGostPage::init(
			$subject,
			$teacher,
			$work_type,
			$pages_count,
			$report
		);

		$current_img = 1; // Номер текущего рисунка
		$expr_is_raw_html = false; // Выражение - чистый HTML?

		$lines = explode("\n", $report['markup']);

		foreach ($lines as $expr) {
			if (mb_strlen($expr) == 0) {
				continue;
			}

			if ($expr[0] != "@") {
				// Выражение - обычный текст
				// FIXME: end($document) может быть false
				if ($expr_is_raw_html) {
					end($document)->addHTML($expr);
				} else {
					end($document)->addHTML("<p class='report-text'>".$expr."</p>");
				}
				continue;
			}

			$command = explode(":", $expr);
			$command_name = $command[0];
			switch ($command_name) {
				case "@titlepage":
					// Титульный лист
					$document[] = new TitleSection($current_page);
					$current_page++;
					break;

				case "@section":
					// Секция основной части
					$document[] = new SubSection($current_page, "ЛАБОРАТОРНАЯ РАБОТА №1");
					$current_page++;
					break;

				case "@\\":
					// Перенос строки
					end($document)->addHTML("<br/>");
					break;

				case "@img":
					// Изображение
					if (count($command) >= 4) {
						$imgwidth = "width='".$command[3]."'";
					} else {
						$imgwidh = "";
					}
					end($document)->addHTML(
						"<figure>
							<img ".$imgwidth." src='/img/autogost/".$command[1]."'>
							<figcaption>Рисунок ".$current_img." - ".$command[2]."</figcaption>
						</figure>"
					);
					$current_img++;
					break;

				case "@raw":
					// Чистый HTML
					$expr_is_raw_html = true;
					break;

				case "@endraw":
					// /Чистый HTML
					$expr_is_raw_html = false;
					break;

				case "@/":
					// Разрыв страницы
					end($document)->pageBreak($current_page);
					$current_page++;
					break;
				
				default:
					throw new \Exception("Unknown command: ".$command_name);
					break;
			}
		}

		echo "<!DOCTYPE html>";
		echo "<html>";
		echo "<head><link rel='stylesheet' href='/css/autogost-report.css'></head>";
		echo "<body><div id='preview'>";
		foreach ($document as $section) {
			$section->output();
		}
		echo "</div></body>";
		echo "</html>";

		//~ // Полное имя преподавателя
		//~ $teacher_full = $teacher["surname"]." ".mb_substr($teacher['name'],0,1).'. '.mb_substr($teacher['patronymic'],0,1).'.';
		//~ $markup = $report['markup'];
		//~ $lines = explode("\n", $markup);

		//~ // Определение количества страниц
		//~ $pages_count = 0;
		//~ foreach ($lines as $l) {
			//~ if (self::isPageMarker($l)) {
				//~ $has_pages = true;
				//~ if (self::isCountablePage($l)) {
					//~ $pages_count++;
				//~ }
			//~ }
		//~ }

		//~ // Основные переменные
		//~ $current_line_index = -1;
		//~ $end_line_index = count($lines) - 1;
		//~ $output = "";

		//~ // Нумераторы
		//~ $current_page_number = 1;			// Страницы
		//~ $current_image_number = 1;			// Изображения
		//~ $current_table_number = 1;			// Таблицы
		//~ $current_application_number = 1;	// Приложения
		//~ $current_table_row = 0;				// Строка текущей таблицы

		//~ // Флаги
		//~ $is_generating_table = false; // Генерируем ли сейчас таблицу

		//~ // Генерация HTML
		//~ while ($current_line_index < $end_line_index) {
			//~ // Ищем маркер страницы. Пропускам всё, что не маркер
			//~ $current_line_index++;
			//~ if (!self::isPageMarker($lines[$current_line_index])) {
				//~ continue;
			//~ }
			//~ $current_page_marker = $lines[$current_line_index];
			//~ $current_line_index++;

			//~ // Генерация контента страницы
			//~ $page_content = "";
			//~ if ($is_generating_table == true) {
				//~ // Если мы генерируем таблицу, а сейчас начинается новая страница, то на предыдущей странице мы уже начинали
				//~ // генерировать и произошёл разрыв таблицы.
				//~ $page_content .= "<p class='tt'>Продолжение таблицы {$current_table_number}</p>
				//~ <table class='t{$current_table_number}'>";
			//~ }
			//~ while ($current_line_index < $end_line_index) {

				//~ if (self::isPageMarker($lines[$current_line_index])) {
					//~ // Эта строка - маркер страницы!
					//~ // Заканчиваем генерировать эту страницу и переходим к другой
					//~ $current_line_index--;
					//~ break;
				//~ }

				//~ $str = $lines[$current_line_index];

				//~ // Интерпретация строки
				//~ if ($str == "") {
					//~ // Пустая строка
					//~ $line_content = "";
				
				//~ } else if ($str == "\\") {
					//~ // Перенос строки
					//~ $line_content = '<br/>';
				//~ } else if ($str[0] == "#") {
					//~ // Выровененный по центру текст
					//~ $line_content = "<p class='title'>".trim(substr($str, 1))."</p>";

				//~ } else if ($str[0] == "?") {
					//~ // Изображение
					//~ list($image_path, $image_title) = explode(":", substr($str, 1));
					//~ $picture_title = "Рисунок {$current_image_number} - {$image_title}";
					//~ $line_content = "<br/><img src='/img/autogost/$image_path' title='$picture_title'><p class='title'>$picture_title</p><br/>";

					//~ $current_image_number++;

				//~ } else if (substr($str, 0, 6) === "@table") {
					//~ // Начало таблицы

					//~ $table_class = "t" . strval($current_table_number);
					//~ $parts = explode(":", substr($str, 1));
					//~ $style = "<style>";
					//~ for ($i = 1; $i < count($parts); $i++) {
						//~ list($width, $align) = explode("_", $parts[$i]);
						//~ $style .= ".{$table_class} td:nth-child({$i}),.{$table_class} th:nth-child({$i}){width:{$width}px;text-align:{$align}}";
					//~ }
					//~ $line_content .= "</style><p class='tt'>Таблица $current_table_number - {$parts[0]}</p><table class='$table_class'>";

					//~ $is_generating_table = true;

				//~ } else if ($str[0] === "|") {
					//~ // Продолжение таблицы
					//~ // Если это начало таблицы, то используем тэг th, иначе td
					//~ if ($current_table_row == 0) {
						//~ $tag_name = "th";
					//~ } else {
						//~ $tag_name = "td";
					//~ }

					//~ $line_content = "<tr>";
					//~ $parts = explode("|", $str);
					//~ for ($i = 1; $i < count($parts) - 1; $i++) {
						//~ $part = trim($parts[$i]);
						//~ $line_content .= "<{$tag_name}>$part</{$tag_name}>";
					//~ }
					//~ $line_content .= "</tr>";

					//~ $current_table_row += 1;

					//~ // Смотрим в будущее и ищем разрыв таблицы или конец всего отчёта
					//~ if ($current_line_index < $end_line_index - 1) {
						//~ if (self::isPageMarker($lines[$current_line_index + 1])) {
							//~ // Таблица разделена на 2 страницы
							//~ $line_content .= "</table>";
						//~ }
					//~ } else {
						//~ // Это конец отчёта! Нарушение разметки, но так уж и быть, поправим по-тихому
						//~ $line_content .= "</table>";
					//~ }

				//~ } else if ($str === "@endtable") {
					//~ // Конец таблицы
					//~ $line_content = "</table><br/>";
					//~ $is_generating_table = false;
					//~ $current_table_number++;
					//~ $current_table_row = 0;

				//~ } else {
					//~ // Обычный текст
					//~ $line_content = "<p>".$str."</p>";
				//~ }

				//~ $page_content .= $line_content;
				//~ $current_line_index++;
			//~ }

			//~ // Всё то, что нагенерировали засовываем в страницу
			//~ $page_wrapped = new AutoGostPageView([
				//~ 'work_code' => $subject['code'].autogost_code,
				//~ 'teacher_full' => $teacher["surname"]." ".mb_substr($teacher['name'],0,1).'. '.mb_substr($teacher['patronymic'],0,1).'.',
				//~ 'author_surname' => autogost_surname,
				//~ 'author_full' => autogost_full,
				//~ 'subject' => $subject,
				//~ 'work_type' => $work_type,
				//~ 'pages_count' => $pages_count,
				//~ 'author_group' => autogost_group,
				//~ 'work_number' => $report['work_number'],
				//~ 'teacher_surname' => $teacher['surname'],
				//~ 'current_page_number' => $current_page_number,
				//~ 'current_page_marker' => $current_page_marker,
				//~ 'page_content' => $page_content
			//~ ]);

			//~ $output .= $page_wrapped->render();

			//~ if (self::isCountablePage($current_page_marker) != "!-") {
				//~ $current_page_number++;
			//~ }
		//~ }

		//~ // $output возвращаем
		//~ echo $output;
	}

	// Возвращает true если строка - это маркер страниц
	private static function isPageMarker($line) {
		return preg_match("/^(?:!|!!|!0|!-|!--)$/", $line);
	}

	// Возвращает true если строка - маркер страницы, которую можно включать в объём всех страниц
	private static function isCountablePage($line) {	
		return preg_match("/^(?:!|!!|!0|!-)$/", $line);
	}
}
