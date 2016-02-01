<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\UserUserRole;
use PadelTFG\GeneralBundle\Service\UserRoleService as UserRoleService;

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

    public function saveUserUserRole($id){
        $userUserRole = new UserUserRole();
        $userUserRole->setId($id);
        $userRoleService = new UserRoleService();
        $userRoleService->setManager($this->em);
        $userRole = $userRoleService->getPlayerRole();
        $userUserRole->setRole($userRole);
        $this->em->persist($userUserRole);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $userUserRole);
    }
}
