<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JsonSerializable;

/**
* @ORM\Entity
* @UniqueEntity("cif")
*/

class Sponsor implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=100)
		@Assert\NotBlank() */
	protected $name;

	/** @ORM\Column(type="string", length=20)
		@Assert\NotBlank() */
	protected $cif;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\SponsorStatus") */
	protected $status;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament", inversedBy="sponsor") */
	protected $tournament;

	public function __construct() {
        $this->tournament = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
	public function getTournament(){
		return $this->tournament;
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
	public function setTournament($tournament){
		$this->tournament = $tournament;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'name' => $this->name,
            'cif' => $this->cif,
            'status' => $this->status,
            'tournament' => $this->tournament
        );
    }
}
