<?php

namespace App\Presenters;

use App;
use App\Query\ResultsQuery;

/**
 * @Secured
 */
class ResultPresenter extends BasePresenter {

	use NeedSchool;

	/**
	 * @var App\Entity\Result
	 */
	protected $result;

	/**
	 * @var App\Entity\Sport
	 */
	protected $sport;

	/**
	 * @var App\Entity\Round
	 */
	protected $round;

	/**
	 * @var App\Entity\Student
	 */
	protected $student;

	/**
	 * @inject
	 * @var \App\ResultsFacade
	 */
	public $results;

	/**
	 * @inject
	 * @var \App\StudentsFacade
	 */
	public $students;

	/**
	 * @inject
	 * @var \App\SportsFacade
	 */
	public $sports;

	/**
	 * @inject
	 * @var \App\GroupsFacade
	 */
	public $groups;
	protected $group;

	/**
	 * @inject
	 * @var \App\Components\IResultControlFormFactory
	 */
	public $resultControlFormFactory;

	/**
	 * @inject
	 * @var App\Components\IEditResultControlFactory $controlFactor
	 */
	public $controlFactory;

	public function actionDefault($group = NULL, $sport = NULL) {

		if ($group !== NULL && $sport !== NULL) {

			$this["selectForm-form"]->setDefaults(array("sport" => $sport, "group" => $group));

			$group = $this->groups->findOneBy(["id" => $group, "school" => $this->school]);
			$sport = $this->getSportEntity($sport);

			if ($sport instanceof \App\Entity\Sport && $group instanceof \App\Entity\Group) {
				$this->group = $this->template->group = $group;
				$this->sport = $this->template->sport = $sport;

				$query = (new ResultsQuery())
						->byGroup($group)
						->bySport($sport)
						->addOrder(['ro.created' => 'DESC'])
						->groupBy();

				$this->template->listResults = $resultsList = $this->results->fetch($query);
				$this->template->resultsCount = count($resultsList);

				$this->round = new App\Entity\Round;
				$this['editForm']->setStudents($this->group->students);
				$this['editForm-form-created']->setDefaultValue($this->round->created);

				$this['editForm']->onSave[] = function (\Nette\Application\UI\Form $form) {
					$this->flashMessage('Nové výsledky uloženy', 'success');
					$this->redirect('this');
				};
			} else {
				$this->flashMessage('Někde se stala chyba, systém nemůže načíst potřebnou skupinu a sport.');
			}
		}
	}

	public function actionEdit($id) {

		if (!is_numeric($id))
			throw new \Kdyby\Doctrine\InvalidArgumentException;

		$this->round = $round = $this->results->findOneRound(["id" => $id, "school" => $this->school]);

		if ($round instanceof App\Entity\Round) {
			$this->group = $group = $round->group;
			$this->sport = $sport = $round->sport;

			$this->template->round = $round;
			$this->template->group = $group;
			$this->template->sport = $sport;

			$this['editForm']->setResults($round->results);

			foreach ($round->results AS $res) {
				$defaults[$res->student->id] = $res->value;
			}
			$this['editForm-form-results']->setDefaults($defaults);
			$this['editForm-form-created']->setDefaultValue($round->created);

			$this['editForm']->onSave[] = function (\Nette\Application\UI\Form $form) use ($sport, $group) {
				$this->flashMessage('Výsledky byly uloženy', 'success');
				$this->redirect('Result:', array('sport' => $sport->id, 'group' => $group->id));
			};
		} else {
			$this->flashMessage('Bohužel, požadované záznamy nebyly nalezeny.', 'error');
		}
	}

	public function actionStudent($id) {
		$this->student = $this->students->findOneBy(["school" => $this->school, "id" => $id]);
		if (!$this->student instanceof \App\Entity\Student) {
			throw new \Nette\InvalidArgumentException;
		}
		$resultsQuery = (new ResultsQuery())
				->byStudent($this->student)
				->notNull()
				->addOrder(["ro.sport" => "ASC", "ro.created" => "ASC"]);
		$resultas = $this->results->fetch($resultsQuery);
		$sportas = $sportResults = array();
		foreach ($resultas AS $res) {
			$sportas[$res->round->sport->id] = $res->round;
			$sportResults[$res->round->sport->id][] = $res;
		}

		$this->template->sportas = $sportas;
		$this->template->sportResults = $sportResults;
		$this->template->student = $this->student;
	}

	public function actionGroup($id) {
		$this->group = $this->groups->findOneBy(["school" => $this->school, "id" => $id]);
		if (!$this->group instanceof \App\Entity\Group)
			throw new \Nette\InvalidArgumentException;

		$resultsQuery = (new ResultsQuery())
				->byGroup($this->group)
				->activeStudent()
				->addOrder(["ro.sport" => "ASC", "ro.created" => "ASC"]);

		$resultas = $this->results->fetch($resultsQuery);
		$sportas = $sportResults = array();
		foreach ($resultas AS $res) {
			$sportas[$res->round->sport->id] = $res->round;
			$sportResults[$res->round->sport->id][$res->student->id][] = $res;
		}

		$this->template->sportas = $sportas;
		$this->template->sportResults = $sportResults;
		$this->template->group = $this->group;
	}

	public function actionBest($id = NULL) {
		if (is_numeric($id)) {
			$groups = $this->groups->findBy(["grade" => $id]);
			$this["gradeForm-grade"]->setDefaultValue($id);

			$resultas = array();
			$sports = $this->sports->findGlobalAndLocal($this->school);
			foreach ($sports AS $sport) {
				$queryObject = (new ResultsQuery())->getBest($sport->bigger_is_better, $sport, $groups);

				$resultas[$sport->id]["male"] = $this->results->fetch($queryObject->byGender('male'));
				$resultas[$sport->id]["female"] = $this->results->fetch($queryObject->byGender('female'));
			}
			$this->template->sports = $sports;
			$this->template->resultas = $resultas;
		}
	}
	
	public function actionTop() {
			$resultas = array();
			$sports = $this->sports->findGlobalAndLocal($this->school);
			foreach ($sports AS $sport) {	
				$resultas[$sport->id]["male"] = $this->results->findTopForSport($sport, "male", $this->school)->setMaxResults(1)->getOneOrNullResult();
				$resultas[$sport->id]["female"] = $this->results->findTopForSport($sport, "female", $this->school)->setMaxResults(1)->getOneOrNullResult();
			}
			$this->template->sports = $sports;
			$this->template->resultas = $resultas;
	}

	public function handleDeleteRound($round) {
		$entity = $this->results->findOneRound(["id" => $round, "school" => $this->school]);
		if ($entity) {
			$this->results->deleteRound($entity);
			$this->flashMessage("Výsledky byly nenávratně smazány", "info");
		} else {
			$this->flashMessage("Toto nemáte právo editovat", "error");
		}
		$this->redirect('this');
	}

	protected function createComponentGradeForm() {
		$form = new \Nette\Application\UI\Form;
		$grades = array();
		foreach ($this->school->groups AS $group) {
			$grades[$group->grade->id] = $group->grade->name;
		}
		$form->addSelect('grade', 'ročník', $grades);
		$form->addSubmit('save', 'Vybrat');
		$form->onSuccess[] = function( \Nette\Application\UI\Form $form) {
			$this->redirect("this", $form->values->grade);
		};
		$form->setRenderer(new \Nextras\Forms\Rendering\Bs3FormRenderer());
		$form->getElementPrototype()->addClass('form-inline');
		return $form;
	}

	protected function createComponentSelectForm() {
		return $this->resultControlFormFactory->create($this->school);
	}

	protected function createComponentEditForm() {
		return $this->controlFactory->create($this->sport, $this->round);
	}

	/**
	 * 
	 * @param int $sport_id
	 * @return App\Entity\Sport|null
	 */
	private function getSportEntity($sport_id) {
		$sportArray = $this->sports->findGlobalAndLocal($this->school, (int) $sport_id)->toArray();

		if (isset($sportArray[0]))
			$sport = $sportArray[0];
		else
			$sport = null;

		return $sport;
	}

}
