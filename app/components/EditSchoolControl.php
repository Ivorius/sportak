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
	private $em;
	private $formFactory;
	private $school;

	public function __construct(App\Entity\School $school, Kdyby\Doctrine\EntityManager $em, App\Forms\IEntityFormFactory $formFactory) {
		$this->school = $school;
		$this->em = $em;
		$this->formFactory = $formFactory;
	}

	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('izo', 'IZO školy');
		$form->addText('name', 'Název školy');
		$form->addText('street', 'Ulice a číslo');
		$form->addText('town', 'Město');
		$form->addText('postcode', 'PSČ');
		$form->addSubmit('save', 'Uložit');
		
		$form->onSuccess[] = function (App\Forms\EntityForm $form) {
			$this->em->persist($school = $form->getEntity())->flush();
			$this->onSave($school);
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
