<?php

namespace App\Presenters;

use Nette,
	App;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter {

	/** 
	 * @var \App\Components\ISignInControlFactory 
	 * @inject 
	 */
	public $signInFormFactory;


	protected function createComponentSignInForm() {
		return $this->signInFormFactory->create();
	}



	public function actionOut() {
		$this->getUser()->logout();
		$this->flashMessage('Úspěšně jste byl odhlášen.');
		$this->redirect('in');
	}

}
