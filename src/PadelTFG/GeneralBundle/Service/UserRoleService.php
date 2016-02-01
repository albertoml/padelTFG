<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\UserRole;

class UserRoleService{

    var $em;

    public function setManager($em){ 
        $this->em = $em;
    } 

    public function getAllUserRole(){
        $repository = $this->em->getRepository('GeneralBundle:UserRole');
        $roles = $repository->findAll();
        return $roles;
    }

    public function getPlayerRole(){
    	$repository = $this->em->getRepository('GeneralBundle:UserRole');
        $role = $repository->findOneByValue('Player');
        return $role;
    }
}
