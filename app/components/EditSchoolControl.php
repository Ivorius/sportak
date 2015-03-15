<?php

namespace App\Components;

use App;
use Kdyby;
use Nette;

/**
 * @method void onSave(App\Entity\School $school)
 */
class EditSchoolControl extends Nette\Application\UI\Control {

	/** @var callable[] */
	public $onSave = [];
	private $formFactory;
	private $school;
	private $schools;

	public function __construct(App\Entity\School $school, App\SchoolsFacade $schools, App\Forms\IEntityFormFactory $formFactory) {
		$this->school = $school;
		$this->formFactory = $formFactory;
		$this->schools = $schools;
	}

	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('izo', 'IZO školy')
				->addRule($form::PATTERN, 'IZO musí mít 9 číslic', '([0-9]\s*){9}');
		$form->addText('name', 'Název školy')
				->setRequired('Název školy musí být vyplněn');
		$form->addText('street', 'Ulice a číslo')
				->setRequired('Ulice a číslo musí být vyplněno');
		$form->addText('town', 'Město')
				->setRequired('Město musí být vyplněno');
		$form->addText('postcode', 'PSČ')
				->addRule($form::PATTERN, 'PSČ musí mít 5 číslic', '([0-9]\s*){5}');
		$form->addSubmit('save', 'Uložit');

		$form->onSuccess[] = function (App\Forms\EntityForm $form) {
			try {
				$this->schools->add($school = $form->getEntity());
				$this->onSave($school);
			} catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
				$usedSchool = $this->schools->findOneBy(array('izo' => $school->izo));
				
				if(!empty($usedSchool->users)) {
					$this->presenter->flashMessage("Tato škola - IZO:" . $school->izo . " již je v naší evidenci. Požádejte jejího správce " . $usedSchool->users[0]->email . " o přiřazení vašeho účtu ke škole.");
				} else {
					$this->presenter->flashMessage("Tato škola - IZO:" . $school->izo . " již je v naší evidenci. Nemá však žádného správce, kontaktujte nás");
				}
			}
		};

		$form->bindEntity($this->school);
		return $form;
	}

	public function render() {
		$this['form']->render();
	}

}

interface IEditSchoolControlFactory {

	/**
	 * @param App\Entity\School $school
	 * @return EditSchoolControl
	 */
	function create(App\Entity\School $school);
}
