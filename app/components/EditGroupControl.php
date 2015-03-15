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
	private $userIdentity;

	public function __construct(App\Entity\Group $group, Kdyby\Doctrine\EntityManager $em, App\Forms\IEntityFormFactory $formFactory, Nette\Security\User $user) {
		$this->group = $group;
		$this->em = $em;
		$this->formFactory = $formFactory;
		$this->userIdentity = $user->identity;
	}

	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('name', 'Název třídy');

		$form->addSelect('grade', 'Ročník')
				->setPrompt('(vyberte ročník)')
				->setOption(Kdyby\DoctrineForms\IComponentMapper::ITEMS_TITLE, 'name');
	

		$form->addSubmit('save', 'Uložit');
		
		$form->onSuccess[] = function (App\Forms\EntityForm $form) {
			$group = $form->getEntity();
			$group->school = $this->userIdentity->school;
			$this->em->persist($group)->flush();
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
