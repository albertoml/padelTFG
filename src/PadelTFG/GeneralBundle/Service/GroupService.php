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

    private function setGroupSave($group, $params){
        $group->setName(!empty($params['name']) ? $params['name'] : '');
        $group->setCategory(!empty($params['category']) ? $params['category'] : null);
        $group->setTournament(!empty($params['tournament']) ? $params['tournament'] : null);
        return $group;
    }

    public function saveGroup($params){
        $group = new GroupCategory();
        $group = $this->setGroupSave($group, $params);        
        $this->em->persist($group);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $group);
    }
}
