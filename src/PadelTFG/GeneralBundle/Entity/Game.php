<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
*/

class Game
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=500) */
	protected $description;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Pair", mappedBy="game") */
	protected $pair;

	/** @ORM\OneToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Schedule", inversedBy="game") */
	protected $date;

	/** @ORM\Column(type="string", length=50) */
	protected $score;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament") */
	protected $tournament;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Category") */
	protected $category;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\GameStatus") */
	protected $status;

	public function __construct() {
        $this->pair = new \Doctrine\Common\Collections\ArrayCollection();
    }

	public function getId(){
		return $this->id;
	}
	public function getDescription(){
		return $this->description;
	}
	public function getPair(){
		return $this->pair;
	}
	public function getDate(){
		return $this->date;
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
	public function getStatus(){
		return $this->status;
	}

	public function setDescription($description){
		$this->description = $description;
	}
	public function setDate($date){
		$this->date = $date;
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
	public function setStatus($status){
		$this->status = $status;
	}

	public function addPair(Pair $pair)
    {
        $pair->addGame($this);
        $this->pair[] = $pair;
    }
}
