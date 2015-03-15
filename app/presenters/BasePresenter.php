<?php

namespace App\Presenters;

use Nette,
	App\Model;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

	/**
	 * Kontrola oprávnění v anotacích
	 * example: @ Secured @ Resource("User")
	 *  
	 */
	public function checkRequirements($element) {
		$user = $this->user;

		if ($element->hasAnnotation("Secured")) {
			if (!$user->isLoggedIn()) {
				if ($user->getLogoutReason() == \Nette\Security\User::INACTIVITY) {
					$this->flashMessage('Byli jste odhlášeni z důvodu nečinnosti. Přihlaste se prosím znovu.', 'error');
				}
				$this->flashMessage('Přístup byl odepřen. Nemáte oprávnění k zobrazení této stránky.', 'error');
				$this->redirect(":Sign:in", array("backlink" => $this->storeRequest()));
			}

			if ($element->hasAnnotation("Resource")) {
				if (!$user->isAllowed($element->getAnnotation("Resource"), $this->action)) {
					throw new ForbiddenRequestException();
				}
			}
		}
	}

}
