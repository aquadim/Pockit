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
}
