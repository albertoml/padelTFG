<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
*/

class ScheduleTrack implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=50) */
	protected $name;

	/** @ORM\Column(type="integer") */
	protected $number;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Schedule", inversedBy="scheduleTrack")
	@ORM\JoinColumn(name="schedule_id", onDelete="cascade") */
	protected $schedule;

	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getNumber(){
		return $this->number;
	}
	public function getSchedule(){
		return $this->schedule;
	}

	public function setName($name){
		$this->name = $name;
	}
	public function setNumber($number){
		$this->number = $number;
	}
	public function setSchedule($schedule){
		$this->schedule = $schedule;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'schedule' => $this->schedule
        );
    }
	
}
