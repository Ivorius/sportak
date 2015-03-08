<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Student extends \Kdyby\Doctrine\Entities\BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", columnDefinition="BIGINT")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $firstname;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $lastname;

	/**
	 * @ORM\Column(type="date")
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
	 *  @ORM\ManyToOne(targetEntity="School", inversedBy="students")
	 */
	protected $school;
	
	
	/**
	 * @ORM\OneToMany(targetEntity="Result", mappedBy="student")
	 */
	protected $results;
}
