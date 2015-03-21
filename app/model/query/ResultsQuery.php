<?php

namespace App\Query;

use App\Entity\Result;
use App\Entity\School;
use App\Entity\Group;
use App\Entity\Sport;
use Doctrine\ORM\Query\Expr\Join;
use Kdyby;
use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Persistence\Queryable;
use Nette;

class ResultsQuery extends Kdyby\Doctrine\QueryObject {

	/**
	 * @var array|\Closure[]
	 */
	private $filter = [];

	/**
	 * @var array|\Closure[]
	 */
	private $select = [];

	public function bySchool(School $school) {

		$this->filter[] = function (QueryBuilder $qb) use ($school) {
			$qb->andWhere('ro.school = :school', $school->getId());
		};
		return $this;
	}

	public function byGroup(Group $group = NULL) {
		$this->filter[] = function (QueryBuilder $qb) use ($group) {
			$qb->andWhere('ro.group = :group', $group->getId());
		};
		return $this;
	}
	
	public function bySport(Sport $sport = NULL) {
		$this->filter[] = function (QueryBuilder $qb) use ($sport) {
			$qb->andWhere('ro.sport = :sport', $sport->getId());
		};
		return $this;
	}
	
	public function byDate($date) {
		if(!$date instanceof \DateTime) {
			$date = Nette\Utils\DateTime::from($date);
		}
		
		$this->filter[] = function (QueryBuilder $qb) use ($date) {
			$qb->andWhere('ro.created = :date', $date);
		};
		return $this;
	}
	
	
	
	public function groupBy($column = "r.round") {
		$this->filter[] = function(QueryBuilder $qb) use ($column) {
			$qb->groupBy($column);
		};
		return $this;
	}

	
	/**
	 * @param \Kdyby\Persistence\Queryable $repository
	 * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Queryable $repository) {
		$qb = $this->createBasicDql($repository);

		foreach ($this->select as $modifier) {
			$modifier($qb);
		}

		return $qb->addOrderBy('ro.created', 'DESC');
	}

	protected function doCreateCountQuery(Queryable $repository) {
		return $this->createBasicDql($repository)->select('COUNT(r.id)');
	}

	private function createBasicDql(Queryable $repository) {
		$qb = $repository->createQueryBuilder()
				->select('r')->from(Result::class, 'r')
				->innerJoin('r.student', 'st')
				->innerJoin('r.round', 'ro');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

}
