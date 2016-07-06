<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

/**
* @ORM\Entity
*/

class Game implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=500, nullable=true) */
	protected $description;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Pair", inversedBy="game")
		@ORM\JoinColumn(name="pair1_id", referencedColumnName="id", onDelete="SET NULL") */
	protected $pair1;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Pair", inversedBy="game")
		@ORM\JoinColumn(name="pair2_id", referencedColumnName="id", onDelete="SET NULL") */
	protected $pair2;

	/** @ORM\Column(type="string", length=50, nullable=true) */
	protected $score;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament", inversedBy="game")
		@ORM\JoinColumn(name="tournament_id", onDelete="cascade") */
	protected $tournament;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\GroupCategory", inversedBy="game")
		@ORM\JoinColumn(name="group_id", onDelete="cascade") */
	protected $group;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Category", inversedBy="game")
		@ORM\JoinColumn(name="category_id", onDelete="cascade") */
	protected $category;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\GameStatus") */
	protected $status;

	/** @ORM\Column(type="string", nullable=true) */
	protected $startDate;

	/** @ORM\Column(type="string", nullable=true) */
	protected $endDate;

	/** @ORM\Column(type="string", length=50, nullable=true) */
	protected $track;

	/** @ORM\Column(type="string", nullable=true) */
	protected $bgColor;

	/** @ORM\Column(type="string", nullable=true) */
	protected $numOfScheduleRange;

	/** @ORM\Column(type="boolean", nullable=true) */
	protected $isDrawGame;


	public function __construct() {
        $this->pair = new \Doctrine\Common\Collections\ArrayCollection();
    }

	public function getId(){
		return $this->id;
	}
	public function getDescription(){
		return $this->description;
	}
	public function getPair1(){
		return $this->pair1;
	}
	public function getPair2(){
		return $this->pair2;
	}
	public function getScore(){
		return $this->score;
	}
	public function getTournament(){
		return $this->tournament;
	}
	public function getCategory(){
		return $this->category;
	}
	public function getGroup(){
		return $this->group;
	}
	public function getStatus(){
		return $this->status;
	}
	public function getStartDate(){
		return $this->startDate;
	}
	public function getEndDate(){
		return $this->endDate;
	}
	public function getTrack(){
		return $this->track;
	}
	public function getBgColor(){
		return $this->bgColor;
	}
	public function getScheduleId(){
		return $this->numOfScheduleRange;
	}
	public function getIsDrawGame(){
		return $this->isDrawGame;
	}

	public function setDescription($description){
		$this->description = $description;
	}
	public function setPair1($pair1){
		$this->pair1 = $pair1;
	}
	public function setPair2($pair2){
		$this->pair2 = $pair2;
	}
	public function setScore($score){
		$this->score = $score;
	}
	public function setTournament($tournament){
		$this->tournament = $tournament;
	}
	public function setCategory($category){
		$this->category = $category;
	}
	public function setGroup($group){
		$this->group = $group;
	}
	public function setStatus($status){
		$this->status = $status;
	}
	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}
	public function setEndDate($endDate){
		$this->endDate = $endDate;
	}
	public function setTrack($track){
		$this->track = $track;
	}
	public function setBgColor($bgColor){
		$this->bgColor = $bgColor;
	}
	public function setScheduleId($numOfScheduleRange){
		$this->numOfScheduleRange = $numOfScheduleRange;
	}
	public function setIsDrawGame($isDrawGame){
		$this->isDrawGame = $isDrawGame;
	}

    public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'description' => $this->description,
            'pair1' => $this->pair1,
            'pair2' => $this->pair2,
            'score' => $this->score,
            'tournament' => $this->tournament,
            'category' => $this->category,
            'group' => $this->group,
            'status' => $this->status,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'track' => $this->track,
            'bgColor' => $this->bgColor,
            'isDrawGame' => $this->isDrawGame
        );
    }
}
