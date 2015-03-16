<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Sport extends \Kdyby\Doctrine\Entities\BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue()
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $unit;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $bigger_is_better;
	

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $is_global;

	/**
	 *
	 * @ORM\OneToMany(targetEntity="Result", mappedBy="sport")
	 */
	protected $results;

	/**
	 * @ORM\ManyToOne(targetEntity="School", inversedBy="results")
	 */
	protected $school;

	public function __construct() {
		$this->is_global = 0;
		$this->results = new ArrayCollection();
	}

}
