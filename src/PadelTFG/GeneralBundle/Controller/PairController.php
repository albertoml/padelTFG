<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\PairService as PairService;
use PadelTFG\GeneralBundle\Service\UserService as UserService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\User;

class PairController extends FOSRestController{

    var $util;
    var $pairService;

    function __construct(){ 
        $this->util = new Util();
        $this->pairService = new PairService();
    }

	public function allPairAction(){
        $this->pairService->setManager($this->getDoctrine()->getManager());
        $pairs = $this->pairService->allPairs();
        $dataToSend = json_encode(array('pair' => $pairs));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getPairAction($id){

        $this->pairService->setManager($this->getDoctrine()->getManager());
        $pair = $this->pairService->getPair($id);

        if (!$pair instanceof Pair) {
            return $this->util->setResponse(404, Literals::PairNotFound);
        }
        $dataToSend = json_encode(array('pair' => $pair));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getPairByUserAction($idUser){

        $this->pairService->setManager($this->getDoctrine()->getManager());
        $pairs = $this->pairService->getPairByUser($idUser);

        $userService = new UserService();
        $userService->setManager($this->getDoctrine()->getManager());
        $user = $userService->getUser($idUser);

        if($user==null){
            return $this->util->setResponse(400, Literals::UserNotFound);
        }
        else{
            $dataToSend = json_encode(array('pair' => $pairs));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getPairByUsersAction($idUser1, $idUser2){

        $this->pairService->setManager($this->getDoctrine()->getManager());
        $pairs = $this->pairService->getPairByUsers($idUser1, $idUser2);

        $userService = new UserService();
        $userService->setManager($this->getDoctrine()->getManager());
        $user = $userService->getUser($idUser1);
        $user1 = $userService->getUser($idUser2);

        if($user==null || $user1==null){
            return $this->util->setResponse(400, Literals::UserNotFound);
        }
        else{     
            $dataToSend = json_encode(array('pair' => $pairs));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function postPairAction(){
        $this->pairService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $pair = $this->pairService->savePair($params);
        if($pair['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $pair['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('pair' => $pair['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

    public function checkAndCreatePairsByUsersAction(){

        $this->pairService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $checkPairs = array();

        foreach ($params['pairs'] as $pair) {
            $pairsEntity = $this->pairService->getPairByUsers($pair['user1'], $pair['user2']);
            if(empty($pairsEntity)){
                $newPair = $this->pairService->savePair($pair);
                if($newPair['result'] == 'ok'){
                    $addPair = $newPair['message'];
                    $checkPairs[] = $addPair->getId();
                }
            }
            else{
                $checkPairs[] = $pairsEntity[0]->getId();
            }
        }  
        $dataToSend = json_encode(array('pair' => $checkPairs));
        return $this->util->setJsonResponse(200, $dataToSend);
    }
}
