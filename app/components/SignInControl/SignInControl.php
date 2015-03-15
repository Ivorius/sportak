<?php

namespace App\Components;

use Nette;
use Nette\Application\UI;

class SignInControl extends UI\Control {

	/** @persistent */
	public $backlink = '';

	public function __construct() {
		parent::__construct();
	}

	public function render() {
		$this->template->setFile(__DIR__ . '/SignIn.latte');
		$this->template->render();
	}

	public function renderMinimal() {
		$this->template->setFile(__DIR__ . '/SignInMinimal.latte');
		$this->template->render();
	}

	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm() {
		$form = new UI\Form;
		$form->addText('email', 'Email:')
				->setRequired('Zadejte prosím svůj email.');
		$form->addPassword('password', 'Password:')
				->setRequired('Zadejte prosím správné heslo.');
		$form->addCheckbox('remember', 'Zapamatovat si přihlášení');
		$form->addSubmit('send', 'Přihlásit se');
		$form->onSuccess[] = $this->signInFormSucceeded;
		return $form;
	}

	public function signInFormSucceeded(UI\Form $form) {
		$values = $form->getValues();
		if ($values->remember) {
			$this->presenter->getUser()->setExpiration('+ 14 days', FALSE);
		} else {
			$this->presenter->getUser()->setExpiration('+ 20 minutes', TRUE);
		}
		try {
			$this->presenter->getUser()->login($values->email, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}
		$this->presenter->restoreRequest($this->backlink);
		$this->presenter->redirect('User:');
	}

}

interface ISignInControlFactory {
	
	/**
	 * @return SignInControl
	 */
	function create();

}
