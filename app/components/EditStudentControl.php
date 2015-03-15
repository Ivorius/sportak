<?php

namespace App\Components;

use App;
use Kdyby;
use Nette;

/**
 * @method void onSave(App\Entity\Student $student)
 */
class EditStudentControl extends Nette\Application\UI\Control {

	/** @var callable[] */
	public $onSave = [];
	private $em;
	private $formFactory;
	private $student;
	private $school;

	public function __construct(App\Entity\Student $student, App\Entity\School $school, Kdyby\Doctrine\EntityManager $em, App\Forms\IEntityFormFactory $formFactory) {
		$this->student = $student;
		$this->school = $school;
		$this->em = $em;
		$this->formFactory = $formFactory;
		
	}

	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('firstname', 'Jméno');
		$form->addText('lastname', 'Příjmení');
		
		$form->addSelect('group', 'Třída')
				->setPrompt('(vyberte třídu)')
				->setOption(Kdyby\DoctrineForms\IComponentMapper::ITEMS_TITLE, 'name')
				->setOption(Kdyby\DoctrineForms\IComponentMapper::ITEMS_FILTER, ['school' => $this->school]);
		
		$form->addComponent(new App\Forms\Controls\DateTimeInput('Narozen'), 'birth');
		//$form->addText('birth', 'Datum narození');
		$form->addCheckbox('is_male', 'Muž');		
		$form->addSubmit('save', 'Uložit');
		$form->addSubmit('save_new', 'Uložit a přidat další');
		
		$form->onSuccess[] = function (App\Forms\EntityForm $form) {
			$student = $form->getEntity();
			$student->school = $this->school;
			$student->hash = $student->generateHash();
			$this->em->persist($student)->flush();
			$this->onSave($student, $form);
		};

		$form->bindEntity($this->student);
		return $form;
	}

	public function render() {
		$this['form']->render();
	}

}

interface IEditStudentControlFactory {

	/**
	 * @param App\Entity\Student $student
	 * @param App\Entity\School $school
	 * @return EditStudentControl
	 */
	function create(App\Entity\Student $student, App\Entity\School $school);
}
