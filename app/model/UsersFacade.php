<?php

namespace App;

use Kdyby;
use Nette;

class UsersFacade extends Nette\Object {

	/** @var \Kdyby\Doctrine\EntityDao */
	private $dao;

	/** @var Kdyby\Doctrine\EntityManager */
	private $em;

	public function __construct(\Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->dao = $em->getRepository(Entity\User::getClassName());
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

	/**
	 * @param null $entity
	 * @param null $relations
	 * @return 
	 */
	public function save($entity) {
		return $this->em->persist($entity)->flush();
	}

	public function findAll() {
		return $this->dao->findAll();
	}
	
	public function flush() {
		return $this->em->flush();
	}

	/**
	 * 
	 * @param int $id
	 * @return mixed
	 */
	public function get($id) {
		return $this->findOneBy(['id' => $id]);
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
	 * @Secure\Read(allow="guest")
	 */
	public function findOneBy(array $criteria, array $orderBy = null) {
		return $this->dao->findOneBy($criteria, $orderBy);
	}

	/**
	 * @param array $criteria
	 * @return mixed
	 *
	 * @Secure\Read(allow="guest")
	 */
	public function countBy(array $criteria = []) {
		return $this->dao->countBy($criteria);
	}

	/**
	 * @param $entity
	 * @param null $relations
	 * @param bool $flush
	 */
	public function delete($entity) {
		return $this->em->remove($entity)->flush();
	}

}
