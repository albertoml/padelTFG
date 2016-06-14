<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
*/

class ScheduleDate implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="datetime") */
	protected $date;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\ScheduleRangeDate", inversedBy="scheduleDate")
	@ORM\JoinColumn(name="scheduleRangeDate_id", onDelete="cascade") */
	protected $scheduleRangeDate;

	public function getId(){
		return $this->id;
	}
	public function getDate(){
		return $this->date;
	}
	public function getScheduleRangeDate(){
		return $this->scheduleRangeDate;
	}

	public function setDate($date){
		$this->date = $date;
	}
	public function setScheduleRangeDate($scheduleRangeDate){
		$this->scheduleRangeDate = $scheduleRangeDate;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'date' => $this->date
        );
    }
	
}
