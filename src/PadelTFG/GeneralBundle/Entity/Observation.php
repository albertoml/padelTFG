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

	/** @ORM\Column(type="datetime")
		@Assert\DateTime()
		@Assert\GreaterThan("today") */
	protected $date;

	/** @ORM\Column(type="integer") */
	protected $fromHour;

	/** @ORM\Column(type="integer") */
	protected $toHour;


	public function getId(){
		return $this->id;
	}
	public function getDate(){
		return $this->date;
	}
	public function getFromHour(){
		return $this->fromHour;
	}
	public function getToHour(){
		return $this->toHour;
	}
	public function getInscription(){
		return $this->inscription;
	}

	public function setDate($date){
		$this->date = $date;
	}
	public function setFromHour($fromHour){
		$this->fromHour = $fromHour;
	}
	public function setToHour($toHour){
		$this->toHour = $toHour;
	}
	public function setInscription(Inscription $inscription){
		$this->inscription = $inscription;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'date' => $this->date,
            'fromHour' => $this->fromHour,
            'toHour' => $this->toHour,
            'inscription' => $this->inscription
        );
    }
	
}
