<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
*/

class Sponsor
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=100) */
	protected $name;

	/** @ORM\Column(type="string", length=20) */
	protected $cif;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\SponsorStatus") */
	protected $status;

	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getCif(){
		return $this->cif;
	}
	public function getStatus(){
		return $this->status;
	}

	public function setName($name){
		$this->name = $name;
	}
	public function setCif($cif){
		$this->cif = $cif;
	}
	public function setStatus($status){
		$this->status = $status;
	}
}
