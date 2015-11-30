<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Observation;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;

class ObservationService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
    } 

	public function allObservations(){
        $repository = $this->em->getRepository('GeneralBundle:Observation');
        $observations = $repository->findAll();
        return $observations;
    }

    public function getObservation($id){
        $repository = $this->em->getRepository('GeneralBundle:Observation');
        $observation = $repository->find($id);
        return $observation;
    }

    public function getObservationByInscription($inscription){
        $repository = $this->em->getRepository('GeneralBundle:Observation');
        $observations = $repository->findByInscription($inscription);
        return $observations;
    }

    private function setObservationSave($observation, $params, $inscription){
        $observation->setStartDate(new \DateTime($params['startDate']));
        $observation->setEndDate(new \DateTime($params['endDate']));
        $observation->setAvailable($params['available'] == 'si' ? true : false);
        $observation->setInscription($inscription);
        return $observation;
    }

    public function saveObservation($params, $inscription, $controller){
        $observation = new Observation();
        $observation = $this->setObservationSave($observation, $params, $inscription);
        $validator = $controller->get('validator');
        $errors = $validator->validate($observation);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($observation);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $observation);
    }
}
