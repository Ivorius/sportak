<?php

namespace App\Presenters;

trait NeedSchool {

	/**
	 * @var \App\SchoolsFacade 
	 */
	protected $schools;
	
	/**
	 * @var App/Entity/School
	 */
	protected $school;

	public function injectSchoolsFacade(\App\SchoolsFacade $schools) {
		$this->schools = $schools;
	}

	public function startup() {
		parent::startup();


		if ($this->user->isLoggedIn() && $this->user->isInRole(\App\Authorizator::TEACHER) && !$this->user->identity->school
		) {
			$this->flashMessage("K vašemu účtu učitele není zatím registrována žádna škola. Zaregistrujte si jí prosím nyní.");
			$this->redirect("School:edit");
		}
	}

}
