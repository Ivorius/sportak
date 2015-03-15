<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Grade extends \Kdyby\Doctrine\Entities\BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", columnDefinition="TINYINT(2)")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=5)
	 * @Assert\NotBlank()
	 */
	protected $name;
	
	/**
	 * @ORM\OneToMany(targetEntity="Group", mappedBy="grade")
	 */
	protected $groups;
	
	/**
	 * @ORM\OneToMany(targetEntity="Result", mappedBy="grade")
	 */
	protected $results;
	
	public function __construct() {
		$this->groups = new ArrayCollection();
		$this->results = new ArrayCollection();
	}
}