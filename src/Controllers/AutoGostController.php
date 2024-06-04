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
use Pockit\AutoGostSections\PracticeTitleSection;
use Pockit\AutoGostSections\SubSection;

class AutoGostController {

	// Применение правил подписи рисунков
	// https://www.php.net/manual/ru/function.ucfirst.php#84122
	private static function makeValidPictureTitle(string $title) : string {
		return mb_strtoupper(mb_substr($title, 0, 1)) .
			mb_strtolower(mb_substr($title, 1));
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
		
		$reports = ReportModel::where("subject_id", $subject_id, true);
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
		$subjects = SubjectModel::all(true);

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
		$filename = "Автогост - ".$subject['name']." #".$report['work_number']." - ".$_ENV['autogost_surname'];

		$markup = str_replace("\n", '\n', $report['markup']);
		$markup = str_replace('"', '\"', $markup);

		$view = new AutoGostEditView([
			"page_title" => $filename,
			"crumbs" => ["Главная"=>"/", "Автогост: дисциплины" => "/autogost/archive/", $subject['name'] => "/autogost/archive/".$subject['id'], "Редактирование"=>"/"],
			"markup" => $markup,
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
		
		if (SubjectModel::countAll() == 0 || TeacherModel::countAll() == 0) {
			return 'В базе данных нет либо предметов, либо преподавателей. '.
			'Выполните стартовую настройку карманного сервера';
		}
		
		return true;
	}

	// Создание отчёта
	public static function newReport() {

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
					"@titlepage\n@section:{$work_type['name_nom']} №{$_POST['number']}\n@section:Ответы на контрольные вопросы",
					$_POST['date_for']
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
		
		$subjects = SubjectModel::all(true);
		$worktypes = WorkTypeModel::all();

		// Дисциплина по умолчанию
		if (isset($_GET['selected'])) {
			$selected = $_GET['selected'];
		} else {
			$selected = -1;
		}
		
		$view = new AutoGostNewReportView([
			"page_title" => "Автогост: создание отчёта",
			'subjects' => $subjects,
			'worktypes' => $worktypes,
			'error_text' => $error_text,
			"crumbs" => ["Главная"=>"/", "Автогост: создание отчёта" => "/"],
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

		self::echoReportHTML($report, $subject, $work_type, $teacher);
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
		$document 			= [];		// Секции документа
		$current_page 		= 1;		// Текущая страница
		$page_count			= 1;		// 
		$current_img 		= 1; 		// Номер текущего рисунка
		$expr_is_raw_html 	= false; 	// Выражение - чистый HTML?

		$lines 		= explode("\n", $report['markup']);
		$line_num 	= 0;

		foreach ($lines as $expr) {
			$line_num++;
			if (mb_strlen($expr) == 0) {
				continue;
			}

			if ($expr[0] != "@") {
				// Выражение - обычный текст
				if ($current_page == 1) {
					// Страниц ещё не было!
					http_response_code(400);
					echo json_encode([
						"line"=>$line_num,
						"Обычный текст перед маркерами страниц"
					]);
					exit();
				}
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

				case "@practicetitle":
					// Титульный лист практики
					$document[] = new PracticeTitleSection($current_page);
					$current_page++;
					break;

				case "@section":
					// Секция основной части
					$document[] = new SubSection($command[1]);
					$current_page = 1;
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
						$imgwidth = "";
					}

					$pictitle = self::makeValidPictureTitle($command[2]);

					if ($img_as_b64) {
						$path = index_dir.'/wwwroot/img/autogost/'.$command[1];
						$type = pathinfo($path, PATHINFO_EXTENSION);
						$data = file_get_contents($path);
						$src = 'data:image/' . $type . ';base64,' . base64_encode($data);
					} else {
						$src = '/img/autogost/'.$command[1];
					}
					
					end($document)->addHTML(
						"<figure>
							<img ".$imgwidth." src='".$src."'>
							<figcaption>Рисунок ".$current_img." - ".$pictitle."</figcaption>
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

				case "@@":
					// Комментарий
					break;

				case "@/":
				case "@-":
					// Разрыв страницы
					end($document)->pageBreak($current_page);
					$current_page++;
					break;
				
				default:
					throw new \Exception("Unknown command: ".$command_name);
					break;
			}
		}

		AutoGostPage::init(
			$subject,
			$teacher,
			$work_type,
			$current_page - 1,
			$report
		);
		foreach ($document as $section) {
			$section->output();
		}
	}
}
