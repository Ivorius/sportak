<?php

namespace App\Entity;

use Nette\Security\Passwords;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class User extends \Kdyby\Doctrine\Entities\BaseEntity implements \Nette\Security\IIdentity {

	const MIN_PASSWORD = 6; //update length in column Password -> Assert too

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank()
	 */
	protected $firstname;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank()
	 */
	protected $lastname;

	/**
	 * @ORM\Column(type="string", length=100, unique=true)
	 * @Assert\Email()
	 */
	protected $email;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank()
	 * @Assert\Length(min = 6)
	 */
	protected $password;

	/** 
	 * @ORM\Column(type="string", length=20)
	 * @Assert\NotNull()
	 */
	protected $role;

	/**
	 * @ORM\ManyToOne(targetEntity="School", inversedBy="users")
	 * @Assert\Valid()
	 */
	protected $school;
	
	public function __construct() {
		parent::__construct();
		$this->role =  \App\Authorizator::TEACHER;
	}
	
	
	public function setPassword($password) {
		$this->password = \Nette\Security\Passwords::hash($password);
	}

	/* implementation of IIdentity */

	public function getId() {
		return $this->id;
	}

	public function getRoles() {
		return array($this->role);
	}

	
	public function verifyPassword($password) {
		return Passwords::verify($password, $this->password);
	}

	public function changePassword($password = NULL, $oldPassword = NULL) {
		if ($oldPassword !== NULL && !$this->verifyPassword($oldPassword)) {
			throw new Nette\Security\AuthenticationException("The old password doesn't match");
		}

		$this->password = Passwords::hash($password);
		return $this;
	}

}
