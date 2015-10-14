<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
*/

class Annotation
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=500) */
	protected $text;

	/** @ORM\Column(type="datetime") */
	protected $creationDate;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\AnnotationStatus") */
	protected $status;

	public function getId(){
		return $this->id;
	}
	public function getText(){
		return $this->text;
	}
	public function getCreationDate(){
		return $this->creationDate;
	}
	public function getStatus(){
		return $this->status;
	}

	public function setText($text){
		$this->text = $text;
	}
	public function setCreationDate($creationDate){
		$this->creationDate = $creationDate;
	}
	public function setStatus($status){
		$this->status = $status;
	}
}
