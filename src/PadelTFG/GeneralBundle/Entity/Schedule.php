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

	/** @ORM\Column(type="string", length=100000, nullable=true) */
	protected $scheduleJson;

	/** @ORM\Column(type="string", length=100000, nullable=true) */
	protected $scheduleResourcesJson;

	/** @ORM\Column(type="string", length=50, nullable=true) */
	protected $startDate;

	/** @ORM\Column(type="string", length=50, nullable=true) */
	protected $maxRange;

	/** @ORM\Column(type="string", length=50, nullable=true) */
	protected $minRange;

	public function getId(){
		return $this->id;
	}
	public function getTournament(){
		return $this->tournament;
	}
	public function getScheduleJson(){
		return $this->scheduleJson;
	}
	public function getScheduleResourcesJson(){
		return $this->scheduleResourcesJson;
	}
	public function getStartDate(){
		return $this->startDate;
	}
	public function getMaxRange(){
		return $this->maxRange;
	}
	public function getMinRange(){
		return $this->minRange;
	}

	public function setTournament($tournament){
		$this->tournament = $tournament;
	}
	public function setScheduleJson($scheduleJson)
	{
		$this->scheduleJson = $scheduleJson;
	}
	public function setScheduleResourcesJson($scheduleResourcesJson)
	{
		$this->scheduleResourcesJson = $scheduleResourcesJson;
	}
	public function setStartDate($startDate)
	{
		$this->startDate = $startDate;
	}
	public function setMaxRange($maxRange)
	{
		$this->maxRange = $maxRange;
	}
	public function setMinRange($minRange)
	{
		$this->minRange = $minRange;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'tournament' => !is_null($this->tournament) ? $this->tournament->getId() : null,
            'scheduleJson' => $this->scheduleJson,
            'scheduleResourcesJson' => $this->scheduleResourcesJson,
            'startDate' => $this->startDate,
            'maxRange' => $this->maxRange,
            'minRange' => $this->minRange
        );
    }
	
}
