<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
	 *
	 * @ORM\OneToMany(targetEntity="Result", mappedBy="sport")
	 */
	protected $results;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $is_global;

	
	public function __construct() {
		$this->results = new ArrayCollection();
	}
}
