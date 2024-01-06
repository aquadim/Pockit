<?php
// Контроллер API

class ApiController extends Controller {

	// Удаление предмета
	public function deleteSubject() {
		SubjectModel::deleteById($_GET['id']);
	}
	
	// Добавление предмета
	public function createSubject() {
		$id = SubjectModel::create($_POST['name'], $_POST['code'], $_POST['teacher_id']);
		$subject = SubjectModel::getById($id);
		echo json_encode($subject);
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
