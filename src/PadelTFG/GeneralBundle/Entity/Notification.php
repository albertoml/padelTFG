<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
*/

class Notification
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=500) */
	protected $text;

	/** @ORM\Column(type="datetime") */
	protected $creationDate;

	/** @ORM\Column(type="datetime") */
	protected $recordalDate;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\NotificationStatus") */
	protected $status;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament") */
	protected $tournament;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\User") */
	protected $user;

	public function __construct(){
		$this->user = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId(){
		return $this->id;
	}
	public function getText(){
		return $this->text;
	}
	public function getCreationDate(){
		return $this->creationDate;
	}
	public function getRecordalDate(){
		return $this->recordalDate;
	}
	public function getStatus(){
		return $this->status;
	}
	public function getTournament(){
		return $this->tournament;
	}
	public function getUser(){
		return $this->user;
	}

	public function setText($text){
		$this->text = $text;
	}
	public function setCreationDate($creationDate){
		$this->creationDate = $creationDate;
	}
	public function setRecordalDate($recordalDate){
		$this->recordalDate = $recordalDate;
	}
	public function setStatus($status){
		$this->status = $status;
	}
	public function setTournament($tournament){
		$this->status = $tournament;
	}

	public function addUser(User $user)
    {
        $user->addNotification($this);
        $this->user[] = $user;
    }
}
