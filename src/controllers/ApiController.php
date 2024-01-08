<?php
// Контроллер API

class ApiController extends Controller {

	// Добавление предмета
	public function createSubject() {
		$id = SubjectModel::create($_POST['name'], $_POST['code'], $_POST['teacher_id']);
		$subject = SubjectModel::getById($id);
		echo json_encode($subject);
	}

	// Обновление предмета
	public function updateSubject() {
		$subject = SubjectModel::getById($_POST['id']);
		$subject['name'] = $_POST['name'];
		$subject['code'] = $_POST['code'];
		$subject['teacher_id'] = $_POST['teacher_id'];
		SubjectModel::update($subject);
		echo json_encode($subject);
	}

	// Удаление предмета
	public function deleteSubject() {
		SubjectModel::deleteById($_GET['id']);
	}

	// Обновление отчёта
	public function updateReport() {
		$report = ReportModel::getById($_POST['id']);

		$fields = ['work_number', 'work_type', 'notice', 'markup'];
		foreach ($fields as $field) {
			if (isset($_POST[$field])) {
				$report[$field] = $_POST[$field];
			}
		}
		ReportModel::update($report);
		echo json_encode($report);
	}

	// Удаление отчёта
	public function deleteReport() {
		ReportModel::deleteById($_GET['id']);
	}

	// Получение всех преподавателей
	public function getTeachers() {
		$teachers = TeacherModel::all();
		$output = [];
		while ($teacher = $teachers->fetchArray(SQLITE3_ASSOC)) {
			$teacher['repr'] = $teacher['surname'];
			$output[] = $teacher;
		}
		echo json_encode($output);
	}

	// Получение всех типов работ
	public function getWorkTypes() {
		$worktypes = WorkTypeModel::all();
		$output = [];
		while ($worktype = $worktypes->fetchArray(SQLITE3_ASSOC)) {
			$worktype['repr'] = $worktype['name_nom'];
			$output[] = $worktype;
		}
		echo json_encode($output);
	}
}
