<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Pair", inversedBy="inscription")
		@ORM\JoinColumn(name="pair_id", onDelete="cascade") 
		@Assert\NotBlank() */
	protected $pair;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Category", inversedBy="inscription")
		@ORM\JoinColumn(name="category_id", onDelete="cascade") 
		@Assert\NotBlank() */
	protected $category;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament", inversedBy="inscription")
		@ORM\JoinColumn(name="tournament_id", onDelete="cascade") 
		@Assert\NotBlank() */
	protected $tournament;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\GroupCategory", inversedBy="inscription")
		@ORM\JoinColumn(name="group_id", onDelete="cascade") */
	protected $group;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $seeded;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\InscriptionStatus") */
	protected $status;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $classifiedPositionInGroup;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $classifiedPositionByGroups;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $rankingMark;

	/** @ORM\Column(type="boolean", nullable=true) */
	protected $hasObservations;




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
	public function getClassifiedPositionInGroup(){
		return $this->classifiedPositionInGroup;
	}
	public function getClassifiedPositionByGroups(){
		return $this->classifiedPositionByGroups;
	}
	public function getRankingMark(){
		return $this->rankingMark;
	}
	public function getHasObservations(){
		return $this->hasObservations;
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
	public function setClassifiedPositionInGroup($classifiedPositionInGroup){
		$this->classifiedPositionInGroup = $classifiedPositionInGroup;
	}
	public function setClassifiedPositionByGroups($classifiedPositionByGroups){
		$this->classifiedPositionByGroups = $classifiedPositionByGroups;
	}
	public function setRankingMark($rankingMark){
		$this->rankingMark = $rankingMark;
	}
	public function setHasObservations($hasObservations){
		$this->hasObservations = $hasObservations;
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
            'classifiedPositionInGroup' => $this->classifiedPositionInGroup,
            'classifiedPositionByGroups' => $this->classifiedPositionByGroups,
            'rankingMark' => $this->rankingMark,
            'hasObservations' => $this->hasObservations
        );
    }
}
