<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
*/

class GroupCategory implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=50) */
	protected $name;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $numPairs;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament")
	@ORM\JoinColumn(name="tournament_id", onDelete="cascade") */
	protected $tournament;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Category")
	@ORM\JoinColumn(name="category_id", onDelete="cascade") */
	protected $category;

	/** @ORM\OneToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Game", mappedBy="category") */
	protected $game;

	public function __construct() {
        $this->game = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getTournament(){
		return $this->tournament;
	}
	public function getCategory(){
		return $this->category;
	}
	public function getGame(){
		return $this->game;
	}

	public function setName($name){
		$this->name = $name;
	}
	public function setTournament($tournament){
		$this->tournament = $tournament;
	}
	public function setCategory($category){
		$this->category = $category;
	}
    public function addGame(Game $game)
    {
        $game->addCategory($this);
        $this->game[] = $game;
    }

    public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'name' => $this->name,
            'tournament' => isset($this->tournament) ? $this->tournament->getId() : null ,
            'category' => isset($this->category) ? $this->category->getId() : null ,
            'game' => $this->game
        );
    }
}
