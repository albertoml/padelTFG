<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
*/

class ScheduleRange implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string") */
	protected $fromHour;

	/** @ORM\Column(type="string") */
	protected $toHour;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\ScheduleRangeDate", inversedBy="scheduleRange")
	@ORM\JoinColumn(name="scheduleRangeDate_id", onDelete="cascade") */
	protected $scheduleRangeDate;

	public function getId(){
		return $this->id;
	}
	public function getFromHour(){
		return $this->fromHour;
	}
	public function getToHour(){
		return $this->toHour;
	}
	public function getScheduleRangeDate(){
		return $this->scheduleRangeDate;
	}

	public function setFromHour($fromHour){
		$this->fromHour = $fromHour;
	}
	public function setToHour($toHour){
		$this->toHour = $toHour;
	}
	public function setScheduleRangeDate($scheduleRangeDate){
		$this->scheduleRangeDate = $scheduleRangeDate;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'fromHour' => $this->fromHour,
            'toHour' => $this->toHour
        );
    }
	
}
