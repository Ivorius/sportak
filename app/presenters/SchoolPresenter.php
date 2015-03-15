<?php

namespace App\Presenters;

use App;
use Kdyby;
use Nette;

/**
 * @Secured
 */
class SchoolPresenter extends BasePresenter {

	/**
	 * @inject
	 * @var \App\SchoolsFacade
	 */
	public $schools;
	
	/**
	 * @inject
	 * @var \App\UsersFacade
	 */
	public $users;
	
	/**
	 * @inject
	 * @var App\Components\IEditSchoolControlFactory $controlFactor
	 */
	public $controlFactory;
	
	protected $school;
	

	public function renderDefault() {
		$this->template->school = $this->user->identity->school;
	}

	public function actionEdit() {
		$this->school = ($this->user->identity->school !== NULL) ? $this->user->identity->school : new App\Entity\School();

		$this['editSchool']->onSave[] = function (App\Entity\School $school) {
			$this->user->identity->school = $school;
			$this->users->add($this->user->identity);
			$this->users->flush();
			$this->flashMessage('Škola uložena.', 'success');
			$this->redirect('this');
		};
	}
	
	public function renderEdit() {
		$this->template->school = $this->user->identity->school;
		$this->template->title =  $this->user->identity->school ? "Editovat školu" : "Přidat školu";
	}

	protected function createComponentEditSchool() {
		return $this->controlFactory->create($this->school);
	}
	
	protected function createComponentAddUserForm() {
		$component = new Nette\Application\UI\Form();
		$component->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer);
		$component->addText('email', 'Email učitele')
				->setType('email')
				->addRule($component::EMAIL, 'Zadejte správný email učitele');
		$component->addSubmit('send', 'Přidat');
		$component->onSubmit[] = $this->addUserFormSubmitted;
		return $component;
	}
	
	public function addUserFormSubmitted(Nette\Application\UI\Form $form) {
		$values = $form->getValues();
		$usr = $this->users->findOneBy(array('email' => $values->email, 'school' => null));
		if($usr) {
			$usr->school = $this->user->identity->school;
			
			try {
				$this->users->save($usr);
				$this->flashMessage("Učitel byl spárován se školou");								
			} catch (\Exception $e) {
				$this->flashMessage("Nastala neočekávaná chyba. Omlouváme se.");
			}
			$this->redirect('this');
			
		} else {
			$form->addError("Bohužel učitel " . $values->email . " nebyl dosud registrován, nebo je propojen s jinou školou.");
		}
	}

}
