<?php

namespace App\Forms;

use Kdyby;
use Nette;

class EntityForm extends Nette\Application\UI\Form {

	use Kdyby\DoctrineForms\EntityForm;

	public function render() {		
		$this->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer);
		parent::render();
	}
}

interface IEntityFormFactory {

	/** @return EntityForm */
	function create();
}
