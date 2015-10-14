<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
*/

class Pair
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\User") */
	protected $user;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament", mappedBy="pair") */
	protected $tournament;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Category", mappedBy="pair") */
	protected $category;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Game", mappedBy="pair") */
	protected $game;

	public function __construct() {
        $this->tournament = new \Doctrine\Common\Collections\ArrayCollection();
        $this->game = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();

    }

    public function getId(){
		return $this->id;
	}
	public function getUser(){
		return $this->user;
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

	public function addUser(User $user)
    {
        $user->addPair($this);
        $this->user[] = $user;
    }
}
