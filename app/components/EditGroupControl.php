<?php

namespace App\Components;

use App;
use Kdyby;
use Nette;

/**
 * @method void onSave(App\Entity\Group $group)
 */
class EditGroupControl extends Nette\Application\UI\Control {

	/** @var callable[] */
	public $onSave = [];
	private $em;
	private $formFactory;
	private $group;

	public function __construct(App\Entity\Group $group, Kdyby\Doctrine\EntityManager $em, App\Forms\IEntityFormFactory $formFactory) {
		$this->school = $group;
		$this->em = $em;
		$this->formFactory = $formFactory;
	}

	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('name', 'Název třídy');
		$form->addSubmit('save', 'Uložit');
		
		$form->onSuccess[] = function (App\Forms\EntityForm $form) {
			$this->em->persist($group = $form->getEntity())->flush();
			$this->onSave($group);
		};

		$form->bindEntity($this->group);
		return $form;
	}

	public function render() {
		$this['form']->render();
	}

}

interface IEditGroupControlFactory {

	/**
	 * @param App\Entity\Group $group
	 * @return EditGroupControl
	 */
	function create(App\Entity\Group $group);
}

