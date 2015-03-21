<?php

namespace App;

use Doctrine;
use Kdyby;
use Nette;
use App\Query\ResultsQuery;

class ResultsFacade extends Nette\Object {

	/** @var \Kdyby\Doctrine\EntityDao */
	private $dao;

	/** @var Kdyby\Doctrine\EntityManager */
	private $em;

	public function __construct(\Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->dao = $em->getRepository(Entity\Result::getClassName());
	}

	/**
	 * 
	 * @param object|array|\Traversable $entity
	 * @param object|array|\Traversable $relations
	 * @throws InvalidArgumentException
	 * @return array
	 */
	public function add($entity, $relations = NULL) {
		return $this->dao->add($entity, $relations);
	}

	public function save($entity, $relations = NULL) {
		return $this->em->persist($entity)->flush();
	}
	
	public function findAll() {
		return $this->dao->findAll();
	}

	public function get($id) {
		return $this->dao->findOneBy(['id' => $id]);
	}

	/**
	 * @param array $criteria
	 * @param array $orderBy
	 * @param null $limit
	 * @param null $offset
	 * @return array
	 */
	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
		return $this->dao->findBy($criteria, $orderBy, $limit, $offset);
	}

	/**
	 * @param array $criteria
	 * @param array $orderBy
	 * @return mixed|null|object
	 *
	 */
	public function findOneBy(array $criteria, array $orderBy = null) {
		return $this->dao->findOneBy($criteria, $orderBy);
	}

	/**
	 * @param $entity
	 * @param null $relations
	 * @param bool $flush
	 */
	public function delete($entity, $relations = NULL, $flush = Kdyby\Persistence\ObjectDao::FLUSH) {
		return $this->dao->delete($entity, $relations, $flush);
	}
	
	public function flush() {
		return $this->em->flush();
	}
	
	public function fetch(Kdyby\Doctrine\QueryObject $queryObject) {
		return $this->dao->fetch($queryObject);
	}
	
	
	public function findOneRound(array $criteria, array $orderBy = null) {
		return $this->em->getRepository(Entity\Round::class)->findOneBy($criteria, $orderBy);
	}
	
	public function saveRound(Entity\Round $entity) {
		return $this->em->persist($entity)->flush();
	}
	
	public function deleteRound(Entity\Round $entity) {
		return $this->em->getRepository(Entity\Round::class)->delete($entity, $entity->results);
	}

	
}
