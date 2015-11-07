<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;

class PairController extends FOSRestController
{

    var $util;

    function __construct(){ 
        $this->util = new Util();
    }

	public function allPairAction(){
        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Pair');
        $pairs = $repository->findAll();
        $dataToSend = json_encode(array('pair' => $pairs));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getPairAction($id){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Pair');
        $pair = $repository->find($id);

        if (!$pair instanceof Pair) {
            return $this->util->setResponse(404, Literals::PairNotFound);
        }
        $dataToSend = json_encode(array('pair' => $pair));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getPairByUserAction($idUser){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Pair');
        $pairs = $repository->findByUser1($idUser);
        $pairs2 = $repository->findByUser2($idUser);

        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:User');
        $user = $repositoryUser->find($idUser);

        if($user==null){
            return $this->util->setResponse(400, Literals::UserNotFound);
        }
        else{
            $pairsToSend = array_merge($pairs, $pairs2);
            $dataToSend = json_encode(array('pair' => $pairsToSend));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getPairByUsersAction($idUser1, $idUser2){

        $query = $this->getDoctrine()->getManager()->createQuery(
            'SELECT p FROM GeneralBundle:Pair p WHERE p.user1 = :user1 AND
            p.user2 = :user2'
        )->setParameters(array(
            'user1' => $idUser1,
            'user2'  => $idUser2,
        ));
        $pairs = $query->getResult();
        $query = $this->getDoctrine()->getManager()->createQuery(
            'SELECT p FROM GeneralBundle:Pair p WHERE p.user1 = :user1 AND
            p.user2 = :user2'
        )->setParameters(array(
            'user1' => $idUser2,
            'user2'  => $idUser1,
        ));
        $pairs2 = $query->getResult();

        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:User');
        $user = $repositoryUser->find($idUser1);
        $user1 = $repositoryUser->find($idUser2);

        if($user==null || $user1==null){
            return $this->util->setResponse(400, Literals::UserNotFound);
        }
        else{
            $pairsToSend = array_merge($pairs, $pairs2);
            $dataToSend = json_encode(array('pair' => $pairsToSend));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }
}
