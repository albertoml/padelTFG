<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;

class PairService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
    } 

	public function allPairs(){
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pairs = $repository->findAll();
        return $pairs;
    }

    public function getPair($id){
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->find($id);
        return $pair;
    }

    public function getPairByUser($user){
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pairs = $repository->findByUser1($user);
        $pairs2 = $repository->findByUser2($user);
        return array_merge($pairs, $pairs2);
    }

    public function getPairByUsers($user1, $user2){
        $query = $this->em->createQuery(
            'SELECT p FROM GeneralBundle:Pair p WHERE p.user1 = :user1 AND
            p.user2 = :user2'
        )->setParameters(array(
            'user1' => $user1,
            'user2'  => $user2,
        ));
        $pairs = $query->getResult();
        $query = $this->em->createQuery(
            'SELECT p FROM GeneralBundle:Pair p WHERE p.user1 = :user1 AND
            p.user2 = :user2'
        )->setParameters(array(
            'user1' => $user2,
            'user2'  => $user1,
        ));
        $pairs2 = $query->getResult();
        return array_merge($pairs, $pairs2);
    }
}
