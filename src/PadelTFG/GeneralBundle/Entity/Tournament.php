<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

/**
* @ORM\Entity
*/

class Tournament implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\User", inversedBy="tournament")
	@ORM\JoinColumn(name="admin_id", onDelete="cascade")
	@Assert\NotBlank() */
	protected $admin;

	/** @ORM\Column(type="string", length=100)
	@Assert\NotBlank() */
	protected $name;

	/** @ORM\Column(type="datetime") */
	protected $creationDate;

	/** @ORM\Column(type="datetime", nullable=true)
		@Assert\DateTime()
		@Assert\GreaterThanOrEqual("today") */
	protected $startInscriptionDate;

	/** @ORM\Column(type="datetime", nullable=true)
		@Assert\DateTime()
		@Assert\GreaterThan("today") */
	protected $endInscriptionDate;

	/** @ORM\Column(type="datetime", nullable=true)
		@Assert\DateTime()
		@Assert\GreaterThan("today") */
	protected $startGroupDate;

	/** @ORM\Column(type="datetime", nullable=true)
		@Assert\DateTime()
		@Assert\GreaterThan("today") */
	protected $endGroupDate;

	/** @ORM\Column(type="datetime", nullable=true)
		@Assert\DateTime()
		@Assert\GreaterThan("today") */
	protected $startFinalDate;

	/** @ORM\Column(type="datetime", nullable=true) */
	protected $endFinalDate;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\TournamentStatus") */
	protected $status;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Sponsor", mappedBy="tournament") */
	protected $sponsor;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $registeredLimit;

	/** @ORM\Column(type="blob", nullable=true) */
	protected $image;

	/** @ORM\OneToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Schedule", inversedBy="tournament")
	@ORM\JoinColumn(name="schedule_id", onDelete="SET NULL") */
	protected $schedule;

	public function __construct() {
        $this->sponsor = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creationDate = new \DateTime();
    }

	public function getId(){
		return $this->id;
	}
	public function getAdmin(){
		return $this->admin;
	}
	public function getName(){
		return $this->name;
	}
	public function getCreationDate(){
		return $this->creationDate;
	}
	public function getStartInscriptionDate(){
		return $this->startInscriptionDate;
	}
	public function getEndInscriptionDate(){
		return $this->endInscriptionDate;
	}
	public function getStartGroupDate(){
		return $this->startGroupDate;
	}
	public function getEndGroupDate(){
		return $this->endGroupDate;
	}
	public function getStartFinalDate(){
		return $this->startFinalDate;
	}
	public function getEndFinalDate(){
		return $this->endFinalDate;
	}
	public function getStatus(){
		return $this->status;
	}
	public function getSponsor(){
		return $this->sponsor;
	}
	public function getRegisteredLimit(){
		return $this->registeredLimit;
	}
	public function getImage(){
		return $this->image;
	}
	public function getSchedule(){
		return $this->schedule;
	}

	public function setAdmin($admin){
		$this->admin = $admin;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function setCreationDate($creationDate){
		$this->creationDate = $creationDate;
	}
	public function setStartInscriptionDate($startInscriptionDate){
		$this->startInscriptionDate = $startInscriptionDate;
	}
	public function setEndInscriptionDate($endInscriptionDate){
		$this->endInscriptionDate = $endInscriptionDate;
	}
	public function setStartGroupDate($startGroupDate){
		$this->startGroupDate = $startGroupDate;
	}
	public function setEndGroupDate($endGroupDate){
		$this->endGroupDate = $endGroupDate;
	}
	public function setStartFinalDate($startFinalDate){
		$this->startFinalDate = $startFinalDate;
	}
	public function setEndFinalDate($endFinalDate){
		$this->endFinalDate = $endFinalDate;
	}
	public function setStatus($status){
		$this->status = $status;
	}
	public function setSponsor($sponsor){
		$this->sponsor = $sponsor;
	}
	public function setRegisteredLimit($registeredLimit){
		$this->registeredLimit = $registeredLimit;
	}
	public function setImage($image){
		$this->image = $image;
	}
	public function setSchedule($schedule){
		$this->schedule = $schedule;
	}

    public function addSponsor(Sponsor $sponsor)
    {
        $sponsor->addTournament($this);
        $this->sponsor[] = $sponsor;
    }

    public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'admin' => $this->admin,
            'name' => $this->name,
            'creationDate' => $this->creationDate,
            'startInscriptionDate' => $this->startInscriptionDate,
            'endInscriptionDate' => $this->endInscriptionDate,
            'startGroupDate' => $this->startGroupDate,
            'endGroupDate' => $this->endGroupDate,
            'startFinalDate' => $this->startFinalDate,
            'endFinalDate'=> $this->endFinalDate,
            'status' => $this->status,
            'sponsor' => $this->sponsor,
            'registeredLimit' => $this->registeredLimit,
            'schedule' => $this->schedule
        );
    }
}
