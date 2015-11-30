<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\RecordalService as RecordalService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Recordal;

class RecordalController extends FOSRestController{

    var $util;
    var $recordalService;

    function __construct(){ 
        $this->util = new Util();
        $this->recordalService = new RecordalService();
    }  

	public function allRecordalAction(){

        $this->recordalService->setManager($this->getDoctrine()->getManager());
        $recordals = $this->recordalService->allRecordals();
        $dataToSend = json_encode(array('recordal' => $recordals));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getRecordalAction($id){

        $this->recordalService->setManager($this->getDoctrine()->getManager());
        $recordal = $this->recordalService->getRecordal($id);

        if (!$recordal instanceof Recordal) {
            return $this->util->setResponse(404, Literals::RecordalNotFound);
        }
        $dataToSend = json_encode(array('recordal' => $recordal));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function postRecordalAction(){

        $this->recordalService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $recordal = $this->recordalService->saveRecordal($params, $this);
        if($recordal['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $recordal['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('recordal' => $recordal['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

    public function putRecordalAction($id){

        $this->recordalService->setManager($this->getDoctrine()->getManager());
        $recordal = $this->recordalService->getRecordal($id);

        if ($recordal instanceof Recordal) {

            $params = array();
            $content = $this->get("request")->getContent();
            $params = json_decode($content, true);
            $recordal = $this->recordalService->modifyRecordal($recordal, $params, $this);
            if($recordal['result'] == 'fail'){
                $dataToSend = json_encode(array('error' => $recordal['message']));
                return $this->util->setResponse(400, $dataToSend);
            }
            $dataToSend = json_encode(array('recordal' => $recordal['message']));
            return $this->util->setJsonResponse(200, $dataToSend);
            
        } else {
            return $this->util->setResponse(404, Literals::RecordalNotFound);
        }
    }

    public function deleteRecordalAction($id){

        $this->recordalService->setManager($this->getDoctrine()->getManager());
        $recordal = $this->recordalService->getRecordal($id);

        if ($recordal instanceof Recordal) {
            
            $recordal = $this->recordalService->deleteRecordal($recordal);
            return $this->util->setResponse(200, Literals::RecordalDeleted);
        } else {
            return $this->util->setResponse(404, Literals::RecordalNotFound);
        }
    }
}
