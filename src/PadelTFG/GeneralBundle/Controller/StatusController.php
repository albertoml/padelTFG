<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

class StatusController extends FOSRestController
{

    var $util;
    var $statusService;

    function __construct(){ 
        $this->util = new Util();
        $this->statusService = new StatusService();
    } 

	public function allStatusAction($entity){
        $this->statusService->setManager($this->getDoctrine()->getManager());
        $status = $this->statusService->getAllStatus($entity);
        if($status == null){
            return $this->util->setResponse(404, Literals::StatusNotFound);
        }
        else{
            $dataToSend = json_encode(array('status' => $status));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }
}
