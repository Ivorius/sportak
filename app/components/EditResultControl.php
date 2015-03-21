<?php

namespace App\Components;

use App;
use Kdyby;
use Nette;
use Nette\Application\UI;
use Nette\Forms\Container;

/**
 * @method void onSave(Nette\Application\UI\Form $form)
 */
class EditResultControl extends UI\Control {

	/** @var callable[] */
	public $onSave = [];
	private $sport;
	private $round;
	private $resultsFacade;
	private $group;
	private $students = NULL;
	private $results = NULL;

	public function __construct(App\Entity\Sport $sport, App\Entity\Round $round, \App\ResultsFacade $resultsFacade) {
		$this->sport = $sport;
		$this->round = $round;
		$this->resultsFacade = $resultsFacade;
	}

	public function setStudents($students) {
		$this->students = $students;
	}

	public function setResults($results) {
		$this->results = $results;
	}

	protected function createComponentForm() {
		$form = new UI\Form;

		$resultsContainer = $form->addContainer("results");
		if ($this->students) {
			foreach ($this->students AS $student) {
				$resultsContainer->addText($student->id, $student->name)
						->addCondition(UI\Form::FILLED, 'Zadejte číselnou hodnotu')
						->addRule(UI\Form::FLOAT);
			}
		}

		if ($this->results) {
			foreach ($this->results AS $result) {
				$resultsContainer->addText($result->student->id, $result->student->name)
						->addCondition(UI\Form::FILLED)
						->addRule(UI\Form::FLOAT, 'Zadejte číselnou hodnotu');
			}
		}
		$form->addSubmit('send', 'Uložit');


		$form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer);
		$form->onSubmit[] = $this->save;
		return $form;
	}

	/**
	 *
	 * @param \Nette\Application\UI\Form $form
	 */
	public function save(UI\Form $form) {
		$values = $form->getValues();
				
		if ($this->students) {			
			$student = $this->students[0];
	
			$this->round->sport = $this->sport;
			$this->round->school = $student->school;
			$this->round->group = $student->group;			
			$this->round->grade = $student->group->grade;
			$this->resultsFacade->saveRound($this->round);
			
			
			foreach ($this->students AS $student) {
				$ent = new App\Entity\Result;			
				$ent->student = $student;
				$ent->round = $this->round;
				$ent->value = $values->results[$student->id];
				$this->resultsFacade->add($ent);
			}
		}
		
		if ($this->results) {
			foreach ($this->results AS $ent) {
				$ent->value = $values->results[$ent->student->id];
			}
		}

		$this->resultsFacade->flush();
		$this->onSave($form);
	}

	public function render() {
		$this['form']->render();
	}

}

interface IEditResultControlFactory {

	/**
	 * @param App\Entity\Sport $sport
	 * @param App\Entity\Round $round
	 * @return EditResultControl
	 */
	function create(App\Entity\Sport $sport, App\Entity\Round $round);
}
