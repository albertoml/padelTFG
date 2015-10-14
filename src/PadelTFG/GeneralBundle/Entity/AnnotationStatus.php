<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
*/

class AnnotationStatus
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer") */
	protected $id;

	/** @ORM\Column(type="string", length=20) */
	protected $value;

	public function getId(){
		return $this->id;
	}
	public function getValue(){
		return $this->value;
	}

	public function setId($id){
		$this->id = $id;
	}
	public function setValue($value){
		$this->value = $value;
	}
}
