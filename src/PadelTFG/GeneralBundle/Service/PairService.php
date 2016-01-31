<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\UserService as UserService;

class PairService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
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

    public function setPairGender($gender1, $gender2){
        if($gender1 == $gender2){
            return $gender1;
        }
        else{
            return Literals::GenderMixed;
        }
    }

    public function modifyPairGender($userId){
        $pairs = $this->getPairByUser($userId);
        foreach ($pairs as $pair) {
            $pair->setGender($this->setPairGender($pair->getUser1()->getGender(), $pair->getUser2()->getGender()));
            $this->em->persist($pair);
            $this->em->flush();
        }
    }

    public function savePair($params, $controller){

        if(empty($this->getPairByUsers($params['user1'], $params['user2']))){

            $userService = new UserService();
            $userService->setManager($this->em);
            $user1 = $userService->getUser($params['user1']);
            $user2 = $userService->getUser($params['user2']);

            $pair = new Pair();
            $pair->setUser1($user1);
            $pair->setUser2($user2);
            $pair->setGender($this->setPairGender($user1->getGender(), $user2->getGender()));
            $validator = $controller->get('validator');
            $errors = $validator->validate($pair);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                return array('result' => 'fail', 'message' => $errorsString);
            }
            $this->em->persist($pair);
            $this->em->flush();
            return array('result' => 'ok', 'message' => $pair);
        }
        else{
            return array('result' => 'fail', 'message' => Literals::PairDuplicate);
        }
    }
}
