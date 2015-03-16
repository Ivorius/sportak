<?php

namespace App\Components;

use App;
use Kdyby;
use Nette;

/**
 * @method void onSave(App\Entity\User $user)
 */
class EditUserControl extends Nette\Application\UI\Control {

	/** @var callable[] */
	public $onSave = [];

	/** @var callable[] */
	public $onGenerateForm = [];
	private $em;
	private $formFactory;
	private $user;

	public function __construct(App\Entity\User $user, Kdyby\Doctrine\EntityManager $em, App\Forms\IEntityFormFactory $formFactory) {
		$this->user = $user;
		$this->em = $em;
		$this->formFactory = $formFactory;
	}

	protected function createComponentForm() {
		$form = $this->formFactory->create();

		$form->addText('firstname', 'Jméno')
				->setRequired('Zadejte prosím své jméno');
		$form->addText('lastname', 'Příjmení')
				->setRequired('Zadejte své příjmení');
		$form->addText('email', 'Email')
				->setType('email')
				->addRule($form::EMAIL, 'Zadejte správně svou emailovou adresu');
		$this->onGenerateForm($form);
		$form->addSubmit('save', 'Uložit');

		$form->onSuccess[] = function (App\Forms\EntityForm $form) {
			try {
				$user = $form->getEntity();
				$this->em->persist($user)->flush();
				$this->onSave($user);
			} catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
				$this->presenter->flashMessage("Tento email již je registrován. Přihlaste se prosím.", "info");
			} catch (\Doctrine\DBAL\DBALException $e) {
				\Tracy\Debugger::log($e);
				$this->presenter->flashMessage("Nastala neočekávaná chyba, učitel nemohl být uložen.", "error");
			}
		};

		$form->bindEntity($this->user);
		return $form;
	}

	public function render() {
		$this['form']->render();
	}

}

interface IEditUserControlFactory {

	/**
	 * @param App\Entity\User $user
	 * @return EditUserControl
	 */
	function create(App\Entity\User $user);
}
