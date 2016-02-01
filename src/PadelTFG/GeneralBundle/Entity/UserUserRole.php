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
    * @ORM\Column(type="integer") */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\UserRole", inversedBy="id") */
    protected $role;

	public function getId(){
		return $this->id;
	}
	public function getRole(){
		return $this->role;
	}

    public function setId($id){
        $this->id = $id;
    }
    public function setRole(UserRole $role){
        $this->role = $role;
    }

	public function jsonSerialize(){
        return array(
        	'id' => $this->id,
            'role' => $this->role
        );
    }
}
