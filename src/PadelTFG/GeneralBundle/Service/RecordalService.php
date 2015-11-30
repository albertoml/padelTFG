<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Recordal;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;

class RecordalService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
    } 

	public function allRecordals(){
        $repository = $this->em->getRepository('GeneralBundle:Recordal');
        $recordals = $repository->findAll();
        return $recordals;
    }

    public function getRecordal($id){
        $repository = $this->em->getRepository('GeneralBundle:Recordal');
        $recordal = $repository->find($id);
        return $recordal;
    }

    private function setRecordalSave($recordal, $params){
        $recordal->setText(isset($params['text']) ? $params['text'] : '');
        $recordal->setRecordalDate(isset($params['recordalDate']) ? new \DateTime($params['recordalDate']) : null);
        $recordal->setStatus($this->statusService->getStatus($this->em, 'recordal', 'created'));
        return $recordal;
    }

    public function saveRecordal($params, $controller){
        $recordal = new Recordal();
        $recordal = $this->setRecordalSave($recordal, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($recordal);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($recordal);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $recordal);
    }

    private function setRecordalModify($recordal, $params){
        $recordal->setText(isset($params['text']) ? $params['text'] : $recordal->getText());
        $recordal->setStatus(isset($params['status']) ? $params['status'] : $recordal->getStatus());
        $recordal->setRecordalDate(isset($params['recordalDate']) ? new \DateTime($params['recordalDate']) : $recordal->getRecordalDate());
        return $recordal;
    }

    public function modifyRecordal($recordal, $params, $controller){
        $recordal = $this->setRecordalModify($recordal, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($recordal);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($recordal);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $recordal);
    }

    public function deleteRecordal($recordal){
        $this->em->remove($recordal);
        $this->em->flush();
    }
}
