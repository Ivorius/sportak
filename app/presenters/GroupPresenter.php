<?php

namespace App\Presenters;

use App;

/**
 * @Secured
 */
class GroupPresenter extends BasePresenter {

	use NeedSchool;

	/**
	 * @inject
	 * @var \App\GroupsFacade
	 */
	public $groups;

	/**
	 * @inject
	 * @var App\Components\IEditGroupControlFactory $controlFactor
	 */
	public $controlFactory;
	
	/**
	 * @inject
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	public $em;
	
	/**
	 * @var App\Entity\Group
	 */
	protected $group;

	public function actionDefault($id = NULL) {
		$school = $this->school;
		$this->template->school = $school;
		$this->template->groups = $school->groups;

		$this->group = ($id !== NULL) ? $this->groups->get($id) : new App\Entity\Group();
		$this->template->group = $this->group;

		$this['editGroup']->onSave[] = function (App\Entity\Group $group) {
			$this->flashMessage('Třída uložena.', 'success');
			$this->redirect('this', ['id' => $group->id]);
		};
		
	}
	
	public function handleDelete($group) {
		$deleteEntity = $this->groups->findOneBy(["id" => $group, "school" => $this->school]);
		if($deleteEntity) {
			try {
			 $this->groups->delete($deleteEntity);
			 $this->flashMessage("Třída " . $deleteEntity->name . " byla smazána.", "info");
			} catch(\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
				$this->flashMessage("Na tuto třídu jsou navázány další záznamy - žáci. Nelze jí odstranit.");
			}
		} else {
			$this->flashMessage("Tuto třídu nemáte právo editovat", "error");
		}
		$this->redirect('this');
	}

	protected function createComponentEditGroup() {
		return $this->controlFactory->create($this->group, $this->school);
	}

}
