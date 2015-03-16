<?php

namespace App\Presenters;
use App;

class SportPresenter extends BasePresenter {

	use NeedSchool;

	/**
	 * @inject
	 * @var App\Components\IEditSportControlFactory $controlFactor
	 */
	public $controlFactory;

	/**
	 * @var App\Entity\Sport
	 */
	protected $sport;

	/**
	 * @inject
	 * @var \App\SportsFacade
	 */
	public $sports;

	public function actionDefault() {
		$this->template->sports = $this->sports->findGlobalAndLocal($this->school);
	}
	
	public function actionEdit($id = NULL) {
		$this->sport = $id === NULL ? new \App\Entity\Sport() : $this->sports->get($id);
		$this['editSport']->onSave[] = function (App\Entity\Sport $sport, $form) {
			$this->flashMessage('Sport ' . $sport->name . ' byl uložen', 'success');
			
			if($form['save']->isSubmittedBy() === true) {
				$this->redirect('this', ['id' => $sport->id]);
			} else {
				$this->redirect('Sport:edit');
			}
		};
	}
	
	public function renderEdit($id =  NULL) {
		$this->template->sport = $this->sport;
	}
	
	
	

	protected function createComponentEditSport() {
		return $this->controlFactory->create($this->sport, $this->school);
	}

}
