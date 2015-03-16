<?php

namespace App\Components;

use App;
use Kdyby;
use Nette;

/**
 * @method void onSave(App\Entity\Sport $sport)
 */
class EditSportControl extends Nette\Application\UI\Control {

	/** @var callable[] */
	public $onSave = [];

	private $school;
	private $em;
	private $formFactory;
	private $sport;

	public function __construct(App\Entity\Sport $sport, App\Entity\School $school, Kdyby\Doctrine\EntityManager $em, App\Forms\IEntityFormFactory $formFactory) {
		$this->sport = $sport;
		$this->school = $school;
		$this->em = $em;
		$this->formFactory = $formFactory;
	}

	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('name', 'Název sportu')
				->setRequired('Zadejte prosím název sportu');
		$form->addText('unit', 'Jednotka měření')
				->setOption("description", "Např. m pro hod, cm pro skok daleký, s pro sprint apod.");
		$form->addCheckbox('bigger_is_better', 'Řadit od největšího')
				->setOption("description", "Čím větší číslo ve výsledku, tím lepší výsledek => hodí se pro skok daleký, nikoliv však pro běhy apod.");

		$form->addSubmit('save', 'Uložit');
		$form->addSubmit('save_new', 'Uložit a přidat další');

		$form->onSuccess[] = function (App\Forms\EntityForm $form) {
			try {
				$sport = $form->getEntity();
				$sport->school = $this->school;
				$this->em->persist($sport)->flush();
				$this->onSave($sport, $form);
			}  catch (\Doctrine\DBAL\DBALException $e) {
				\Tracy\Debugger::log($e);
				$this->presenter->flashMessage("Nastala neočekávaná chyba, sport nemohl být uložen.", "error");
			}
		};

		$form->bindEntity($this->sport);
		return $form;
	}

	public function render() {
		$this['form']->render();
	}

}

interface IEditSportControlFactory {

	/**
	 * @param App\Entity\Sport $sport
	 * @param App\Entity\School $school
	 * @return EditSportControl
	 */
	function create(App\Entity\Sport $sport, App\Entity\School $school);
}
