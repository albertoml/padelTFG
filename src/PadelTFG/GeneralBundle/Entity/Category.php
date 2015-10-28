<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
*/

class Category
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=50) */
	protected $name;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament", inversedBy="category") */
	protected $tournament;

	/** @ORM\OneToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Game", mappedBy="category") */
	protected $game;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Pair", mappedBy="category") */
	protected $pair;

	public function __construct() {
        $this->pair = new \Doctrine\Common\Collections\ArrayCollection();
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
	public function getPair(){
		return $this->pair;
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

	public function addPair(Pair $pair)
    {
        $pair->addCategory($this);
        $this->pair[] = $pair;
    }
    public function addGame(Game $game)
    {
        $game->addCategory($this);
        $this->game[] = $game;
    }
}
