<?php

namespace App\Components;

use App;

class AttachedSchoolControl extends \Nette\Application\UI\Control {

	private $schooler;

	public function __construct(App\SchoolsFacade $schooler) {
		$this->schooler = $schooler;
	}
	
	public function render() {
		$template = $this->template;
		$template->setFile(__DIR__ . '/list.latte');
		$template->schools = $this->schooler->findAll();
		$template->render();
	}
}

	interface IAttachedSchoolControlFactory {
	/**
	 * @return AttachedSchoolControl
	 */
	function create();
}
