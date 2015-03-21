<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Round extends \Kdyby\Doctrine\Entities\BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue()
	 */
	protected $id;

	/**
	 * @ORM\Column(type="date")
	 */
	protected $created;

	/**
	 *  @ORM\ManyToOne(targetEntity="Sport", inversedBy="rounds")
	 */
	protected $sport;

	/**
	 * @ORM\ManyToOne(targetEntity="School", inversedBy="rounds")
	 */
	protected $school;

	/**
	 * @ORM\ManyToOne(targetEntity="Grade", inversedBy="rounds")
	 */
	protected $grade;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Group", inversedBy="rounds")
	 */
	protected $group;
	
	/**
	 * @ORM\OneToMany(targetEntity="Result", mappedBy="round")
	 */
	protected  $results;
	
	public function __construct() {
		$this->created = new \Nette\Utils\DateTime;
		$this->results = new ArrayCollection;
	}
 
}
