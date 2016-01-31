<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

/**
* @ORM\Entity
*/

class Category implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=50)
		@Assert\NotBlank() */
	protected $name;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $registeredLimitMax;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $registeredLimitMin;

	/** @ORM\Column(type="string", nullable=true)
		@Assert\Choice(callback = {"PadelTFG\GeneralBundle\Resources\globals\Utils", "getGenders"}, message = "Choose a valid gender.") */
	protected $gender;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament", inversedBy="category")
	@ORM\JoinColumn(name="tournament_id", onDelete="cascade") */
	protected $tournament;

    public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getTournament(){
		return $this->tournament;
	}
	public function getRegisteredLimitMax(){
		return $this->registeredLimitMax;
	}
	public function getRegisteredLimitMin(){
		return $this->registeredLimitMin;
	}
	public function getGender(){
		return $this->gender;
	}

	public function setName($name){
		$this->name = $name;
	}
	public function setTournament($tournament){
		$this->tournament = $tournament;
	}
	public function setRegisteredLimitMax($registeredLimitMax){
		$this->registeredLimitMax = $registeredLimitMax;
	}
	public function setRegisteredLimitMin($registeredLimitMin){
		$this->registeredLimitMin = $registeredLimitMin;
	}
	public function setGender($gender){
		$this->gender = $gender;
	}

    public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'name' => $this->name,
            'tournament' => isset($this->tournament) ? $this->tournament->getId() : null,
            'gender' => $this->gender
        );
    }
}
