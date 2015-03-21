<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * 
 */
class School extends \Kdyby\Doctrine\Entities\BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", columnDefinition="CHAR(9)", unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Length(min=9, max=9)
	 */
	protected $izo;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 * @Assert\Length(min = 4)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 */
	protected $town;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 */
	protected $street;

	/**
	 * @ORM\Column(type="string", columnDefinition="CHAR(5)")
	 * @Assert\NotBlank()
	 * @Assert\Length(min=5, max=5)
	 */
	protected $postcode;

	/**
	 * @ORM\OneToMany(targetEntity="Group", mappedBy="school", cascade={"persist"}, indexBy="id")
	 */
	protected $groups;

	/**
	 * @ORM\OneToMany(targetEntity="Student", mappedBy="school", cascade={"persist"})
	 */
	protected $students;

	/**
	 * @ORM\OneToMany(targetEntity="Result", mappedBy="school", cascade={"persist"})
	 */
	protected $results;
	
	/**
	 * @ORM\OneToMany(targetEntity="User", mappedBy="school", cascade={"persist"})
	 */
	protected $users;
	
	
    public function __construct() {
        $this->groups = new ArrayCollection();
		$this->students = new ArrayCollection();
		$this->users = new ArrayCollection();
		$this->results = new ArrayCollection();
    }
}
