<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 * 	name="result",
 *  indexes={
 *     @ORM\Index(name="value_idx", columns={"value"})
 *  }
 * )
 */
class Result extends \Kdyby\Doctrine\Entities\BaseEntity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", columnDefinition="BIGINT")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="date")
	 */
	protected $created;

	/**
	 * @ORM\Column(type="float")
	 */
	protected $value;

	/**
	 *  @ORM\ManyToOne(targetEntity="Student", inversedBy="results")
	 */
	protected $student;

	/**
	 *  @ORM\ManyToOne(targetEntity="Sport", inversedBy="results")
	 */
	protected $sport;

	/**
	 * @ORM\ManyToOne(targetEntity="School", inversedBy="results")
	 */
	protected $school;

	/**
	 * @ORM\ManyToOne(targetEntity="Grade", inversedBy="results")
	 */
	protected $grade;

}
