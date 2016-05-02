<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
*/

class Schedule implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\OneToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament", inversedBy="schedule")
	@ORM\JoinColumn(name="tournament_id", onDelete="cascade") */
	protected $tournament;

	public function getId(){
		return $this->id;
	}
	public function getTournament(){
		return $this->tournament;
	}

	public function setTournament($tournament){
		$this->tournament = $tournament;
		//$tournament->setSchedule($this); debe de ir pero da fallo
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'tournament' => $this->tournament
        );
    }
	
}
