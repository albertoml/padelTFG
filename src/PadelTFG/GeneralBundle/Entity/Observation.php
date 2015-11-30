<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

/**
* @ORM\Entity
*/

class Observation implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Inscription", inversedBy="observation")
	@ORM\JoinColumn(name="inscription_id", onDelete="cascade") */
	protected $inscription;

	/** @ORM\Column(type="datetime") */
	protected $startDate;

	/** @ORM\Column(type="datetime") */
	protected $endDate;

	/** @ORM\Column(type="boolean") 
		@Assert\NotBlank() */
	protected $available;


	public function getId(){
		return $this->id;
	}
	public function getStartDate(){
		return $this->startDate;
	}
	public function getEndDate(){
		return $this->endDate;
	}
	public function getAvailable(){
		return $this->available;
	}
	public function getInscription(){
		return $this->inscription;
	}

	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}
	public function setEndDate($endDate){
		$this->endDate = $endDate;
	}
	public function setAvailable($available){
		$this->available = $available;
	}
	public function setInscription(Inscription $inscription){
		$this->inscription = $inscription;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'available' => $this->available,
            'inscription' => $this->inscription
        );
    }
	
}
