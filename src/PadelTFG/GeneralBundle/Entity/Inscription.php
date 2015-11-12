<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
*/

class Inscription implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Pair")
	@ORM\JoinColumn(name="pair_id", onDelete="cascade") */
	protected $pair;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Category")
	@ORM\JoinColumn(name="category_id", onDelete="cascade") */
	protected $category;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament")
	@ORM\JoinColumn(name="tournament_id", onDelete="cascade") */
	protected $tournament;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\GroupCategory")
	@ORM\JoinColumn(name="group_id", onDelete="cascade") */
	protected $group;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $seeded;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\InscriptionStatus") */
	protected $status;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $classifiedPosition;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $rankingMark;


    public function getId(){
		return $this->id;
	}
	public function getPair(){
		return $this->pair;
	}
	public function getCategory(){
		return $this->category;
	}
	public function getTournament(){
		return $this->tournament;
	}
	public function getGroup(){
		return $this->group;
	}
	public function getSeeded(){
		return $this->seeded;
	}
	public function getStatus(){
		return $this->status;
	}
	public function getClassifiedPosition(){
		return $this->classifiedPosition;
	}
	public function getRankingMark(){
		return $this->rankingMark;
	}

	public function setPair(Pair $pair){
		$this->pair = $pair;
	}
	public function setCategory(Category $category){
		$this->category = $category;
	}
	public function setTournament(Tournament $tournament){
		$this->tournament = $tournament;
	}
    public function setGroup(GroupCategory $group){
        $this->group = $group;
    }
    public function setSeeded($seeded){
		$this->seeded = $seeded;
	}
	public function setStatus($status){
		$this->status = $status;
	}
	public function setClassifiedPosition($classifiedPosition){
		$this->classifiedPosition = $classifiedPosition;
	}
	public function setRankingMark($rankingMark){
		$this->rankingMark = $rankingMark;
	}

    public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'pair' => $this->pair,
            'category' => $this->category,
            'tournament' => $this->tournament,
            'group' => $this->group,
            'seeded' => $this->seeded,
            'status' => $this->status,
            'classifiedPosition' => $this->classifiedPosition,
            'rankingMark' => $this->rankingMark
        );
    }
}
