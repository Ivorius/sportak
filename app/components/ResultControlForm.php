<?php

namespace App\Components;

use App;
use Nette;
use Nette\Application\UI\Form;

/**
 * @method void onSave(Nette\Application\UI\Form $form)
 */
class ResultControlForm extends Nette\Application\UI\Control {

	/** @var callable[] */
	public $onSave = [];
	private $sports;
	private $school;

	public function __construct(App\Entity\School $school, App\SportsFacade $sports) {
		$this->school = $school;
		$this->sports = $sports;
	}

	protected function createComponentForm() {
		$form = new Form;
		$form->setMethod('GET');

		$groups = $this->school->groups;
		$groupsArray = array();
		foreach ($groups AS $entity) {
			$groupsArray[$entity->id] = $entity->name;
		}

		$sportsResult = $this->sports->findGlobalAndLocal($this->school);
		$sportsArray = array();
		foreach ($sportsResult AS $entity) {
			$sportsArray[$entity->id] = $entity->name;
		}

		$form->addSelect('group', 'Třída', $groupsArray);

		$form->addSelect('sport', 'Sport', $sportsArray)
				->setPrompt('(vyberte sport)');

		$form->addSubmit('save', 'Vybrat');
		$form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer);
		$form->getElementPrototype()->addClass('form-inline');

		$form->onSuccess[] = function (Form $form) {
			try {
				$values = $form->getValues();
				$this->onSave($form);
			} catch (\Doctrine\DBAL\DBALException $e) {
				\Tracy\Debugger::log($e);
				$this->presenter->flashMessage("Nastala neočekávaná chyba, dotaz nemohl být proveden.", "error");
			}
		};

		return $form;
	}

	public function render() {
		$this['form']->render();
	}

}

interface IResultControlFormFactory {

	/**
	 * @param App\Entity\School $school
	 * @return ResultControlForm
	 */
	function create(App\Entity\School $school);
}
