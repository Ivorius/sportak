<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User extends \Kdyby\Doctrine\Entities\BaseEntity implements \Nette\Security\IIdentity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/** @ORM\Column(type="string", length=100) */
	protected $username;

	/** @ORM\Column(type="string", length=100) */
	protected $password;

	/* implementation of IIdentity */

	public function getId() {
		return $this->id;
	}

	public function getRoles() {
		return array();
	}

}
