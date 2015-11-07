<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
*/

class Notification implements JsonSerializable
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
	protected $notificationDate;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\NotificationStatus") */
	protected $status;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\tournament")
	@ORM\JoinColumn(name="tournament_id", onDelete="cascade") */
	protected $tournament;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\User", inversedBy="notification") */
	protected $user;

	public function __construct(){
		$this->user = new \Doctrine\Common\Collections\ArrayCollection();
		$this->creationDate = new \DateTime();
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
	public function getNotificationDate(){
		return $this->notificationDate;
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
	public function setNotificationDate($notificationDate){
		$this->notificationDate = $notificationDate;
	}
	public function setStatus($status){
		$this->status = $status;
	}
	public function setTournament($tournament){
		$this->tournament = $tournament;
	}

	public function addUser(User $user)
    {
        $user->addNotification($this);
        $this->user[] = $user;
    }

    public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'text' => $this->text,
            'creationDate' => $this->creationDate,
            'notificationDate' => $this->notificationDate,
            'status' => $this->status,
            'tournament' => $this->tournament,
            'user' => $this->user
        );
    }
}
