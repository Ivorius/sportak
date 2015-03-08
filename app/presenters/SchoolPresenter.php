<?php

namespace App\Presenters;

use App;
use Kdyby;
use Nette;

/**
 * Schoolp resenter.
 */
class SchoolPresenter extends BasePresenter {

	/**
	 * @inject
	 * @var \App\SchoolsFacade
	 */
	public $schools;
	
	/**
	 * @inject
	 * @var App\Components\IEditSchoolControlFactory $controlFactor
	 */
	public $controlFactory;
	
	protected $school;
	

	public function renderDefault() {
		$this->template->schools = $this->schools->findAll();
	}

	public function actionEdit($id = NULL) {
		$this->school = ($id !== NULL) ? $this->schools->get($id) : new App\Entity\School();

		$this['editSchool']->onSave[] = function (App\Entity\School $school) {
			$this->flashMessage('Škola uložena.', 'success');
			$this->redirect('this', ['id' => $school->id]);
		};
	}

	public function actionImport() {
		$this->schools->addExample();
		$this->redirect("School:default");
	}

	protected function createComponentEditSchool() {
		return $this->controlFactory->create($this->school);
	}

}
