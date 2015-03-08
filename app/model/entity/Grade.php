<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
	 * @ORM\OneToMany(targetEntity="Group", mappedBy="grade")
	 */
	protected $groups;
	
	/**
	 * @ORM\OneToMany(targetEntity="Result", mappedBy="grade")
	 */
	protected $results;
}