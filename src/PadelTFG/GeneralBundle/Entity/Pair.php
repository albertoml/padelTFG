<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
*/

class Pair implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\User", inversedBy="pair")
		@ORM\JoinColumn(name="user1_id", onDelete="cascade") */
	protected $user1;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\User", inversedBy="pair")
		@ORM\JoinColumn(name="user2_id", onDelete="cascade") */
	protected $user2;

	/** @ORM\Column(type="string", nullable=true) */
	protected $gender;


    public function getId(){
		return $this->id;
	}
	public function getUser1(){
		return $this->user1;
	}
	public function getUser2(){
		return $this->user2;
	}
	public function getGender(){
		return $this->gender;
	}

	public function setUser1($user1){
		$this->user1 = $user1;
	}
	public function setUser2($user2){
		$this->user2 = $user2;
	}
	public function setGender($gender){
		$this->gender = $gender;
	}

    public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'user1' => $this->user1,
            'user2' => $this->user2,
            'gender' => $this->gender
        );
    }
}
