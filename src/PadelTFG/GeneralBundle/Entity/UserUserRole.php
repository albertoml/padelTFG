<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
*/

class UserUserRole implements JsonSerializable
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\User", inversedBy="role") */
    protected $user;

    /** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\UserRole", inversedBy="id") */
    protected $role;

	public function getUser(){
		return $this->user;
	}
	public function getRole(){
		return $this->role;
	}

    public function setUser(User $user){
        $this->user = $user;
    }
    public function setRole(UserRole $role){
        $this->role = $role;
    }

	public function jsonSerialize(){
        return array(
        	'user' => $this->user,
            'role' => $this->role
        );
    }
}
