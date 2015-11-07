<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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

	/** @ORM\Column(type="string", length=50) */
	protected $name;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $registeredLimitMax;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $registeredLimitMin;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament", inversedBy="category")
	@ORM\JoinColumn(name="tournament_id", onDelete="cascade") */
	protected $tournament;

	/** @ORM\OneToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Group", mappedBy="category") */
	protected $group;

	public function __construct() {
        $this->group = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getTournament(){
		return $this->tournament;
	}
	public function getGroup(){
		return $this->group;
	}
	public function getRegisteredLimitMax(){
		return $this->registeredLimitMax;
	}
	public function getRegisteredLimitMin(){
		return $this->registeredLimitMin;
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
    public function addGroup(Group $group)
    {
        $group->addGroup($this);
        $this->group[] = $group;
    }

    public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'name' => $this->name,
            'tournament' => isset($this->tournament) ? $this->tournament->getId() : null ,
            'group' => $this->group
        );
    }
}
