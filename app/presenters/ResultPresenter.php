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

		$this->round  = $round = $this->results->findOneRound(["id" => $id, "school" => $this->school]);

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
	
	public function handleDeleteRound($round) {
		$entity = $this->results->findOneRound(["id" => $round, "school" => $this->school]);
		if($entity) {
			 $this->results->deleteRound($entity);
			 $this->flashMessage("Výsledky byly nenávratně smazány", "info");
		} else {
			$this->flashMessage("Toto nemáte právo editovat", "error");
		}
		$this->redirect('this');
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
