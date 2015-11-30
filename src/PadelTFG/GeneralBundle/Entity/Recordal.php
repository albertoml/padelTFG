<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

/**
* @ORM\Entity
*/

class Recordal implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=500) 
		@Assert\NotBlank() */
	protected $text;

	/** @ORM\Column(type="datetime") 
		@Assert\NotBlank()
		@Assert\DateTime() */
	protected $creationDate;

	/** @ORM\Column(type="datetime", nullable=true) 
		@Assert\NotNull()
		@Assert\DateTime()
		@Assert\GreaterThan("today") */
	protected $recordalDate;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\RecordalStatus") */
	protected $status;

	public function __construct(){
		$this->creationDate = new \DateTime();
	}

	public function getId(){
		return $this->id;
	}
	public function getText(){
		return $this->text;
	}
	public function getCreationDate(){
		return $this->creationDate;
	}
	public function getRecordalDate(){
		return $this->recordalDate;
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
	public function setRecordalDate($recordalDate){
		$this->recordalDate = $recordalDate;
	}
	public function setStatus($status){
		$this->status = $status;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'text' => $this->text,
            'creationDate' => $this->creationDate,
            'recordalDate' => $this->recordalDate,
            'status' => $this->status
        );
    }
}
