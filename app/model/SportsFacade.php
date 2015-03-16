<?php

namespace App;

use Doctrine;
use Kdyby;
use Nette;
use Kdyby\Doctrine\ResultSet;

class SportsFacade extends Nette\Object {

	/** @var \Kdyby\Doctrine\EntityDao */
	private $dao;

	/** @var Kdyby\Doctrine\EntityManager */
	private $em;

	public function __construct(\Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->dao = $em->getRepository(Entity\Sport::getClassName());
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
		$this->em->persist($entity)->flush();
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
		$this->dao->delete($entity, $relations, $flush);
	}
	
	
	public function findGlobalAndLocal(\App\Entity\School $school) {
		$qb = $this->dao->createQueryBuilder('s')
				->where('s.is_global = TRUE')->orWhere('s.is_global = FALSE AND s.school = :school')->setParameter('school', $school->getId());
		return new ResultSet($qb->getQuery());		
		
	}

}
