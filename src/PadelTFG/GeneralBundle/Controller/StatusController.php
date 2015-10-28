<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response as Response;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\UserStatus;
use PadelTFG\GeneralBundle\Entity\TournamentStatus;
use PadelTFG\GeneralBundle\Entity\SponsorStatus;
use PadelTFG\GeneralBundle\Entity\RecordalStatus;
use PadelTFG\GeneralBundle\Entity\NotificationStatus;
use PadelTFG\GeneralBundle\Entity\GameStatus;
use PadelTFG\GeneralBundle\Entity\AnnotationStatus;

class StatusController extends FOSRestController
{

    var $util;

    function __construct(){ 
        $this->util = new Util();
    }

	public function allStatusAction($entity){
        $repository = $this->util->factoryStatusController($this->getDoctrine()->getManager(), $entity);
        if($repository!=null){
        	$status = $repository->findAll();
        	$dataToSend = json_encode(array('status' => $status));
            return $this->util->setJsonResponse(200, $dataToSend);
        } else {
            return $this->util->setResponse(404, Literals::StatusNotFound);
        } 
    }
}
