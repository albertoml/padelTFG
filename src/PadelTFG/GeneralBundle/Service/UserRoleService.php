<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

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
}
