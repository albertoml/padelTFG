<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\ObservationService as ObservationService;
use PadelTFG\GeneralBundle\Service\InscriptionService as InscriptionService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Observation;
use PadelTFG\GeneralBundle\Entity\Inscription;

class ObservationController extends FOSRestController
{
    var $util;
    var $observationService;

    function __construct(){ 
        $this->util = new Util();
        $this->observationService = new ObservationService();
    }

	public function allObservationAction(){
        $this->observationService->setManager($this->getDoctrine()->getManager());
        $observations = $this->observationService->allObservations();
        $dataToSend = json_encode(array('observation' => $observations));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getObservationAction($id){

        $this->observationService->setManager($this->getDoctrine()->getManager());
        $observation = $this->observationService->getObservation($id);

        if (!$observation instanceof Observation) {
            return $this->util->setResponse(404, Literals::ObservationNotFound);
        }
        $dataToSend = json_encode(array('observation' => $observation));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getObservationByInscriptionAction($idInscription){

        $this->observationService->setManager($this->getDoctrine()->getManager());
        $observations = $this->observationService->getObservationByInscription($idInscription);

        $inscriptionService = new InscriptionService();
        $inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscription = $inscriptionService->getInscription($idInscription);

        if(!$inscription instanceof Inscription){
            return $this->util->setResponse(400, Literals::InscriptionNotFound);
        }
        else{
            $dataToSend = json_encode(array('observation' => $observations));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function postObservationsAction(){
        $this->observationService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $observations = $this->observationService->saveObservationsPOST($params, $this);
        if($observations['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $observations['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('observations' => $observations['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

    public function postOneObservationsAction(){
        $this->observationService->setManager($this->getDoctrine()->getManager());
        $inscriptionService = new InscriptionService();
        $inscriptionService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $inscription = $inscriptionService->getInscription($params['inscription']);
        $observations = $this->observationService->saveObservation($params['observation'], $inscription, $this);
        if($observations['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $observations['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('observations' => $observations['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

    public function deleteObservationAction($id){

        $this->observationService->setManager($this->getDoctrine()->getManager());
        $observation = $this->observationService->getObservation($id);

        if ($observation instanceof Observation) {
            $observation = $this->observationService->deleteObservation($observation);
            return $this->util->setResponse(200, Literals::ObservationDeleted);
        } else {
            return $this->util->setResponse(404, Literals::ObservationNotFound);
        }
    }
}
