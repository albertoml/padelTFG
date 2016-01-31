<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Observation;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\InscriptionService as InscriptionService;

class ObservationService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
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
        $observation->setDate(new \DateTime($params['date']));
        $observation->setFromHour($params['fromHour']);
        $observation->setToHour($params['toHour']);
        $observation->setInscription($inscription);
        return $observation;
    }

    public function saveObservations($observations, $inscription, $controller){
        
        $resumeToObservationsInsert = null;
        $resumeToObservationsInsert['message'] = "";
        $resumeToObservationsInsert['result'] = "ok";
        foreach ($observations as $objObs) {
            $result = $this->saveObservation($objObs, $inscription, $controller);
            if($result['result'] == 'fail'){
                $resumeToObservationsInsert['message'] .= $inscription->getId() . '|' . $result['message'] . ';';
                $resumeToObservationsInsert['result'] = 'fail';
            }
        }
        return $resumeToObservationsInsert;
    }

    public function saveObservationsPOST($params, $controller){
        
        $resumeToObservationsInsert = null;
        $resumeToObservationsInsert['message'] = "";
        $resumeToObservationsInsert['result'] = "ok";
        $inscriptionService = new InscriptionService();
        $inscriptionService->setManager($this->em);
        $inscription = $inscriptionService->getInscription($params['inscription']);
        $observations = $this->getObservationByInscription($params['inscription']);
        foreach ($observations as $obs) {
            $this->deleteObservation($obs);
        }
        foreach ($params['observations'] as $objObs) {
            $result = $this->saveObservation($objObs, $inscription, $controller);
            if($result['result'] == 'fail'){
                $resumeToObservationsInsert['message'] .= $inscription->getId() . '|' . $result['message'] . ';';
                $resumeToObservationsInsert['result'] = 'fail';
            }
        }
        return $resumeToObservationsInsert;
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
        return array('result' => 'ok', 'message' => 'Ok');
    }

    public function deleteObservation($observation){
        $this->em->remove($observation);
        $this->em->flush();
    }
}
