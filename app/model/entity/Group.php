<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Group extends \Kdyby\Doctrine\Entities\BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 */
	protected $name;

	/**
	 * @ORM\ManyToOne(targetEntity="Grade", inversedBy="groups")
	 * @Assert\Valid()
	 */
	protected $grade;

	/**
	 * @ORM\ManyToOne(targetEntity="School", inversedBy="groups")
	 * @Assert\Valid()
	 */
	protected $school;

}
