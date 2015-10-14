<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
*/

class Schedule
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="datetime") */
	protected $date;

	/** @ORM\Column(type="string", length=50) */
	protected $track;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Game") */
	protected $game;

	public function getId(){
		return $this->id;
	}
	public function getDate(){
		return $this->date;
	}
	public function getTrack(){
		return $this->track;
	}
	public function getGame(){
		return $this->game;
	}

	public function setDate($date){
		$this->date = $date;
	}
	public function setTrack($track){
		$this->track = $track;
	}
	public function setGame($game){
		$this->game = $game;
	}
	
}
