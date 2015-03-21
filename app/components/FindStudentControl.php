<?php

namespace App\Components;

use App;
use Nette;
use Nette\Application\UI\Form;

/**
 * @method void onSave(Nette\Application\UI\Form $form)
 * @method void onSearh($search)
 */
class FindStudentControl extends Nette\Application\UI\Control {

	/** @var callable[] */
	public $onSave = [];

	/** @var callable[] */
	public $onSearch = [];
	private $students;
	private $school;

	public function __construct(App\Entity\School $school, App\StudentsFacade $students) {
		$this->school = $school;
		$this->students = $students;
	}

	protected function createComponentForm() {
		$form = new Form;

		$form->addText('firstname', 'Jméno');
		$form->addText('lastname', 'Příjmení');

		$form->addSubmit('save', 'Najít');
		$form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer);

		$form->onSuccess[] = function (Form $form) {

			$values = $form->getValues();
			$this->onSave($form);
			
			$this->presenter->redirect("Student:", array("firstname" => $values->firstname, "lastname" => $values->lastname));
		};

		return $form;
	}

	public function render() {
		$this['form']->render();
	}

}

interface IFindStudentControlFactory {

	/**
	 * @param App\Entity\School $school
	 * @return FindStudentControl
	 */
	function create(App\Entity\School $school);
}
