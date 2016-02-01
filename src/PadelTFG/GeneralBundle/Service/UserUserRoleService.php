<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

class UserUserRoleService{

    var $em;

    public function setManager($em){ 
        $this->em = $em;
    } 

    public function getAllUserUserRole(){
        $repository = $this->em->getRepository('GeneralBundle:UserUserRole');
        $roles = $repository->findAll();
        return $roles;
    }

    public function getUserUserRole($id){
    	$repository = $this->em->getRepository('GeneralBundle:UserUserRole');
        $roles = $repository->find($id);
        return $roles;	
    }
}
