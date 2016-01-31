<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;

class GroupService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allGroups(){
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $groups = $repository->findAll();
        return $groups;
    }

    public function getGroup($id){
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->find($id);
        return $group;
    }
}
