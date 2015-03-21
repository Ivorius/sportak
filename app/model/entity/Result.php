<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $value = null;

	/**
	 *  @ORM\ManyToOne(targetEntity="Student", inversedBy="results")
	 */
	protected $student;

	/**
	 *  @ORM\ManyToOne(targetEntity="Round", inversedBy="results")
	 */
	protected $round;
	
	public function setValue($value) {
		return $this->value = $value !== "" ? $value : NULL;
	}

}
