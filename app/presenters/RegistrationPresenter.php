<?php

namespace App\Presenters;

use App;
use Kdyby;
use Nette;


class RegistrationPresenter extends BasePresenter {

	/**
	 * @inject
	 * @var \App\UsersFacade
	 */
	public $users;
	
	/**
	 * @inject
	 * @var App\Components\IEditUserControlFactory $controlFactor
	 */
	public $controlFactory;
	
	/**
	 *
	 * @var App\Entity\User
	 */
	protected $userEntity;
	
	

	public function renderDefault($id) {
		$this->userEntity =  new App\Entity\User();

		$component = $this['editUser'];

		$component->onGenerateForm[] = function (App\Forms\EntityForm $form) {			
			$form->addPassword('password', 'Heslo')
					->setRequired('Zvolte si heslo')
					->addRule($form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', App\Entity\User::MIN_PASSWORD);
			$form->addPassword('verifyPassword', 'Heslo znovu')
				->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
				->addRule($form::EQUAL, 'Hesla se neshodují', $form['password']);		
		};
		$component->onSave[] = function (App\Entity\User $userEntity) {
			$this->flashMessage('Registrace proběhla úspěšně. Nyní se můžete přihlásit s emailem ' . $userEntity->email, 'success');
			$this->redirect('Sign:in');
		};
	}


	protected function createComponentEditUser() {
		return $this->controlFactory->create($this->userEntity);
	}

}
