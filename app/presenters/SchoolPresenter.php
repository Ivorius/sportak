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
	
	/**
	 * @var App/Entity/School
	 */
	protected $school;
	
	public function startup() {
		parent::startup();
		$this->school = $this->schools->findOneBy(["id" => $this->user->identity->school_id]);
	}
	

	public function renderDefault() {
		$this->template->school = $this->school;
	}

	public function actionEdit() {
		$this->school = ($this->school !== NULL) ? $this->school : new App\Entity\School();

		$this['editSchool']->onSave[] = function (App\Entity\School $school) {
			$userEntity = $this->users->get($this->user->id);
			$userEntity->school = $school;			
			$this->users->add($userEntity);
			$this->users->flush();
			$this->user->identity->school_id = $school->id;
			$this->flashMessage('Škola uložena.', 'success');
			$this->redirect('this');
		};
	}
	
	public function renderEdit() {
		$this->template->school = $this->school;
		$this->template->title =  $this->school ? "Editovat školu" : "Přidat školu";
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
		$userEntity = $this->users->findOneBy(array('email' => $values->email, 'school' => null));
		if($userEntity) {
			$userEntity->school = $this->school;
			
			try {
				$this->users->save($userEntity);
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
