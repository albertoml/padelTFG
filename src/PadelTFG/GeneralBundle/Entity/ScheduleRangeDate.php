<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
*/

class ScheduleRangeDate implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Schedule", inversedBy="scheduleRangeDate")
	@ORM\JoinColumn(name="schedule_id", onDelete="cascade") */
	protected $schedule;

	public function getId(){
		return $this->id;
	}
	public function getSchedule(){
		return $this->schedule;
	}

	public function setSchedule($schedule){
		$this->schedule = $schedule;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'date' => $this->date,
            'schedule' => $this->schedule
        );
    }
	
}
