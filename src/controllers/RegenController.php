<?php
// Контроллер Regen

class RegenController extends Controller {

	// Загрузка изображений
	public static function uploadImage() {

		if (is_uploaded_file($_FILES['file']['tmp_name'])) {
			$mime_type = mime_content_type($_FILES['file']['tmp_name']);
			$filepath = tempnam(rootdir."/img/regen", "rgnupload");

			if ($mime_type == "image/png") {
				// Конвертирование png в gif
				$png_image = imagecreatefrompng($_FILES['file']['tmp_name']);
				$gif_image = imagecreatetruecolor(imagesx($png_image), imagesy($png_image));
				imagecopy($gif_image, $png_image, 0, 0, 0, 0, imagesx($png_image), imagesy($png_image));
				imagegif($gif_image, $filepath);
			} else {
				// Просто перемещение файла
				$filepath = tempnam(rootdir."/img/regen", "rgnupload");
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

		$view = new RegenReportsView([
			"page_title" => "Regen: архив ".$subject['name'],
			"crumbs" => ["Главная" => "/", "Regen: архив" => "/regen/archive", $subject['name'] => ""],
			"reports" => $reports,
			"subject" => $subject
		]);
		$view->view();
	}

	// Архив отчётов
	public static function archive() {
		$subjects = SubjectModel::all();

		$view = new RegenArchiveView([
			"page_title" => "Regen: архив",
			"crumbs" => ["Главная" => "/", "Regen: архив" => "/"],
			"subjects" => $subjects
		]);
		$view->view();
	}

	// Редактирование отчёта
	public static function edit($report_id) {
		$report = ReportModel::getById($report_id);
		$subject = SubjectModel::getById($report['subject_id']);

		$view = new RegenEditView([
			"page_title" => "Regen: редактирование отчёта",
			"crumbs" => ["Главная"=>"/", "Regen: архив" => "/regen/archive/", $subject['name'] => "/regen/archive/".$subject['id'], "Редактирование"=>"/"],
			"markup" => $report['markup'],
			"filename" => $subject['name']." #".$report['work_number']." - Королёв",
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
				header("Location: /regen/edit/".$report_id);
				return;
			} else {
				// Валидация провалена
				$error_text = $response;
			}
		} else {
			$error_text = null;
		}
		
		$view = new RegenNewReportView([
			"page_title" => "Regen: создание отчёта",
			'subjects' => $subjects,
			'worktypes' => $worktypes,
			'error_text' => $error_text,
			"crumbs" => ["Главная"=>"/", "Regen: создание отчёта" => "/"]
		]);
		$view->view();
	}

	// Получение HTML
	public static function getHtml() {
		$report = ReportModel::getById($_POST['report_id']);
		$subject = SubjectModel::getById($report["subject_id"]);
		$work_type = WorkTypeModel::getById($report["work_type"]);
		$teacher = TeacherModel::getById($subject["teacher_id"]);
		
		$markup = $_POST['markup'];
		$lines = explode("\n", $markup);

		// Определение количества страниц
		$pages_count = 0;
		foreach ($lines as $l) {
			if (self::isPageMarker($l)) {
				$has_pages = true;
				if (self::isCountablePage($l)) {
					$pages_count++;
				}
			}
		}

		// Основные переменные
		$current_line_index = -1;
		$end_line_index = count($lines);
		$output = "";

		// Нумераторы
		$current_page_number = 1;			// Страницы
		$current_image_number = 1;			// Изображения
		$current_table_number = 1;			// Таблицы
		$current_application_number = 1;	// Приложения
		$current_table_row = 0;				// Строка текущей таблицы

		// Флаги
		$is_generating_table = false; // Генерируем ли сейчас таблицу

		// Генерируем (X)HTML!
		while ($current_line_index < $end_line_index - 1) {
			// Ищем маркер страницы. Пропускам всё, что не маркер
			$current_line_index++;
			if (!self::isPageMarker($lines[$current_line_index])) {
				continue;
			}
			$current_page_marker = $lines[$current_line_index];
			$current_line_index++;

			// Генерация контента страницы
			$page_content = "";
			if ($is_generating_table == true) {
				// Если мы генерируем таблицу, а сейчас начинается новая страница, то на предыдущей странице мы уже начинали
				// генерировать и произошёл разрыв таблицы.
				$page_content .= "<p class='tt'>Продолжение таблицы {$current_table_number}</p>
				<table class='t{$current_table_number}'>";
			}
			while ($current_line_index < $end_line_index) {

				if (self::isPageMarker($lines[$current_line_index])) {
					// Эта строка - маркер страницы!
					// Заканчиваем генерировать эту страницу и переходим к другой
					$current_line_index--;
					break;
				}

				$str = $lines[$current_line_index];
				
				// Все короткие тире в строке заменяются длинными
				$str = str_replace("-", "&mdash;", $str);

				// Интерпретация строки
				if ($str == "") {
					// Пустая строка
					$line_content = "";

				} else if ($str[0] == "#") {
					// Выровененный по центру текст
					$line_content = "<p class='title'>".trim(substr($str, 1))."</p>";

				} else if ($str[0] == "?") {
					// Изображение
					list($image_path, $image_title) = explode(":", substr($str, 1));
					$picture_title = "Рисунок {$current_image_number} - {$image_title}";
					$line_content = "<img src='/img/regen/$image_path' title='$picture_title'><p class='title'>$picture_title</p>";

					$current_image_number++;

				} else if (substr($str, 0, 6) === "@table") {
					// Начало таблицы

					$table_class = "t" . strval($current_table_number);
					$parts = explode(":", substr($str, 1));
					$style = "<style>";
					for ($i = 1; $i < count($parts); $i++) {
						list($width, $align) = explode("_", $parts[$i]);
						$style .= ".{$table_class} td:nth-child({$i}),.{$table_class} th:nth-child({$i}){width:{$width}px;text-align:{$align}}";
					}
					$line_content .= "</style><p class='tt'>Таблица $current_table_number - {$parts[0]}</p><table class='$table_class'>";

					$is_generating_table = true;

				} else if ($str[0] === "|") {
					// Продолжение таблицы
					// Если это начало таблицы, то используем тэг th, иначе td
					if ($current_table_row == 0) {
						$tag_name = "th";
					} else {
						$tag_name = "td";
					}

					$line_content = "<tr>";
					$parts = explode("|", $str);
					for ($i = 1; $i < count($parts) - 1; $i++) {
						$part = trim($parts[$i]);
						$line_content .= "<{$tag_name}>$part</{$tag_name}>";
					}
					$line_content .= "</tr>";

					$current_table_row += 1;

					// Смотрим в будущее и ищем разрыв таблицы или конец всего отчёта
					if ($current_line_index < $end_line_index - 1) {
						if (self::isPageMarker($lines[$current_line_index + 1])) {
							// Таблица разделена на 2 страницы
							$line_content .= "</table>";
						}
					} else {
						// Это конец отчёта! Нарушение разметки, но так уж и быть, поправим по-тихому
						$line_content .= "</table>";
					}

				} else if ($str === "@endtable") {
					// Конец таблицы
					$line_content = "</table><br/>";
					$is_generating_table = false;
					$current_table_number++;
					$current_table_row = 0;

				} else {
					// Обычный текст
					$line_content = "<p>".$str."</p>";
				}

				$page_content .= $line_content;
				$current_line_index++;
			}

			// Всё то, что нагенерировали засовываем в страницу
			$page_wrapped = new RegenPageView([
				'work_code' => $subject['code'].regen_code,
				'teacher_full' => $teacher["surname"]." ".mb_substr($teacher['name'],0,1).'. '.mb_substr($teacher['patronymic'],0,1).'.',
				'author_surname' => regen_surname,
				'author_full' => regen_full,
				'subject' => $subject,
				'work_type' => $work_type,
				'pages_count' => $pages_count,
				'author_group' => regen_group,
				'work_number' => $report['work_number'],
				'teacher_surname' => $teacher['surname'],
				'current_page_number' => $current_page_number,
				'current_page_marker' => $current_page_marker,
				'page_content' => $page_content
			]);

			$output .= $page_wrapped->render();

			if (self::isCountablePage($current_page_marker) != "!-") {
				$current_page_number++;
			}
		}

		// $output возвращаем
		echo $output;
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
