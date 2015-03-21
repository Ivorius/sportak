<?php

namespace App\Presenters;

use App;

/**
 * @Secured
 */
class StudentPresenter extends BasePresenter {

	use NeedSchool;

	/**
	 * @inject
	 * @var App\Components\IEditStudentControlFactory $controlFactor
	 */
	public $controlFactory;

	/**
	 * @var App\Entity\Student
	 */
	protected $student;

	/**
	 * @inject
	 * @var \App\StudentsFacade
	 */
	public $students;
	
	/**
	 * @inject
	 * @var \App\GroupsFacade
	 */
	public $groups;
	
	/**
	 * @inject
	 * @var \App\Components\IFindStudentControlFactory
	 */
	public $findStudentFactory;
	
	public function actionDefault($group_id = NULL, $firstname=NULL, $lastname=NULL) {
		$arg = array("school" => $this->school, "archived" => 0);
		if($group_id) $arg["group"] = $group_id;
		if($firstname) $arg["firstname"] = $firstname;
		if($lastname) $arg["lastname"] = $lastname;
		
		$students = $this->students->findBy($arg, ["group" => "ASC"]);
		$this->template->students = $students;
		$this->template->group = $group_id  ? $this->groups->findOneBy(["id" => $group_id]) : NULL;
	}
	
	public function actionArchiv() {
		$this->template->students = $this->students->findBy(["school" => $this->school, "archived" => 1], ["birth" => "DESC"]);
	}

	public function actionEdit($id = NULL, $group_id = NULL ) {
		$this->student = $id === NULL ? new \App\Entity\Student() : $this->students->get($id);
		$this['editStudent']->onSave[] = function (App\Entity\Student $student, $form) {
			$this->flashMessage('Žák/yně ' . $student->name . ' uložen/a', 'success');
			
			if($form['save']->isSubmittedBy() === true) {
				$this->redirect('this', ['id' => $student->id]);
			} else {
				$this->redirect('Student:edit');
			}
			
		};
	}
	
	public function renderEdit($id = NULL, $group_id = NULL) {
		$this->template->student = $this->student;
		if(!$id && $group_id) {
			$this['editStudent-form-group']->setDefaultValue($group_id);
		}
	}
	
	public function actionFind() {
		
	}
	
	public function handleArchive($student) {
		$studentEntity = $this->students->findOneBy(["id" => $student, "school" => $this->school]);
		if($studentEntity) {
			$studentEntity->archived = true;
			$this->students->save($studentEntity);
			$this->flashMessage($studentEntity->name . " byl přesunut do archivu.", "info");
		} else {
			$this->flashMessage("Tohoto žáka nemáte právo editovat", "error");
		}
		$this->redirect('this');
	}
	
	public function handleLive($student) {
		$studentEntity = $this->students->findOneBy(["id" => $student, "school" => $this->school]);
		if($studentEntity) {
			$studentEntity->archived = false;
			$this->students->save($studentEntity);
			$this->flashMessage($studentEntity->name . " byl přesunut zpět z archivu", "info");
		} else {
			$this->flashMessage("Tohoto žáka nemáte právo editovat", "error");
		}
		$this->redirect('this');
	}
	
	public function handleDelete($student) {
		$studentEntity = $this->students->findOneBy(["id" => $student, "school" => $this->school]);
		if($studentEntity) {
			 $this->students->delete($studentEntity);
			 $this->flashMessage($studentEntity->name . " byl nenávratně smazán", "info");
		} else {
			$this->flashMessage("Tohoto žáka nemáte právo editovat", "error");
		}
		$this->redirect('this');
	}
	
	
	protected function createComponentEditStudent() {
		return $this->controlFactory->create($this->student, $this->school);
	}
	
	protected function createComponentFindStudentForm() {
		return $this->findStudentFactory->create($this->school);
	}

}
