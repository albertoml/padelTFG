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
	protected $title;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Schedule", inversedBy="scheduleTrack")
	@ORM\JoinColumn(name="schedule_id", onDelete="cascade") */
	protected $schedule;

	public function getId(){
		return $this->id;
	}
	public function getTitle(){
		return $this->title;
	}
	public function getSchedule(){
		return $this->schedule;
	}

	public function setTitle($title){
		$this->title = $title;
	}
	public function setSchedule($schedule){
		$this->schedule = $schedule;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'title' => $this->title
        );
    }
	
}
