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
	
	
	public function handleDelete($sport) {
		$deleteEntity = $this->sports->findOneBy(["id" => $sport, "school" => $this->school, "is_global" => FALSE]);
		if($deleteEntity) {
			try {
			 $this->sports->delete($deleteEntity);
			 $this->flashMessage("Sport " . $deleteEntity->name . " byl vymazán.", "info");
			} catch(\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
				$this->flashMessage("Na tento sport jsou navázány další záznamy. Nelze jej odstranit.");
			}
		} else {
			$this->flashMessage("Tento sport již nemáte právo editovat", "error");
		}
		$this->redirect('this');
	}
	
	

	protected function createComponentEditSport() {
		return $this->controlFactory->create($this->sport, $this->school);
	}

}
