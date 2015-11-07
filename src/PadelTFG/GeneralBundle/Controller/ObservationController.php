<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Observation;

class ObservationController extends FOSRestController
{
    var $util;

    function __construct(){ 
        $this->util = new Util();
    }

	public function allObservationAction(){
        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Observation');
        $observations = $repository->findAll();
        $dataToSend = json_encode(array('observation' => $observations));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getObservationAction($id){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Observation');
        $observation = $repository->find($id);

        if (!$observation instanceof Observation) {
            return $this->util->setResponse(404, Literals::ObservationNotFound);
        }
        $dataToSend = json_encode(array('observation' => $observation));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getObservationByInscriptionAction($idInscription){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Observation');
        $observations = $repository->findByInscription($idInscription);

        $repositoryInscription = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Inscription');
        $inscription = $repositoryInscription->find($idInscription);

        if($inscription==null){
            return $this->util->setResponse(400, Literals::InscriptionNotFound);
        }
        else{
            $dataToSend = json_encode(array('observation' => $observations));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }
}
