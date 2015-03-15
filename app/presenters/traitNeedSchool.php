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


		if ($this->user->isLoggedIn() && $this->user->isInRole(\App\Authorizator::TEACHER) && !$this->user->identity->school_id
		) {			
			$this->flashMessage("K vašemu účtu učitele není zatím registrována žádna škola. Zaregistrujte si jí prosím nyní.");
			$this->redirect("School:edit");
		} else {
			$this->school = $this->schools->findOneBy(["id" => $this->user->identity->school_id]);
		}
	}

}
