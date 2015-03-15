<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="`group`")
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
	 */
	protected $grade;

	/**
	 * @ORM\ManyToOne(targetEntity="School", inversedBy="groups")
	 * @Assert\Valid()
	 */
	protected $school;

}
