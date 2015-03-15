<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Student extends \Kdyby\Doctrine\Entities\BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 */
	protected $firstname;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 */
	protected $lastname;

	/**
	 * @ORM\Column(type="date")
	 * @Assert\NotBlank()
	 */
	protected $birth;

	/**
	 * @ORM\Column(type="boolean") 
	 */
	protected $is_male;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $hash;
	
	/**
	 * @ORM\Column(type="boolean") 
	 */
	protected $archived;


	/**
	 *  @ORM\ManyToOne(targetEntity="Group", inversedBy="students")
	 */
	protected $group;

	/**
	 *  @ORM\ManyToOne(targetEntity="School", inversedBy="students", cascade={"persist"})
	 */
	protected $school;

	/**
	 * @ORM\OneToMany(targetEntity="Result", mappedBy="student", cascade={"persist"})
	 */
	protected $results;

	public function __construct() {
		$this->results = new ArrayCollection();
		$this->archived = 0;
	}
	
	public function getName() {
		return $this->firstname . " " . $this->lastname;
	}
	
	public function generateHash() {
		return md5($this->getName() . $this->birth);
	}

}
