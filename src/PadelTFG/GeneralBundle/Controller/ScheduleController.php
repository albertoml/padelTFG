<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Schedule;
use PadelTFG\GeneralBundle\Service\ScheduleService;

class ScheduleController extends FOSRestController
{
	var $util;
    var $scheduleService;

    function __construct(){ 
        $this->util = new Util();
        $this->scheduleService = new ScheduleService();
    } 

	public function getScheduleAction($id){

        $this->scheduleService->setManager($this->getDoctrine()->getManager());
        $schedule = $this->scheduleService->getSchedule($id);

        if (!$schedule instanceof Schedule) {
            return $this->util->setResponse(404, Literals::ScheduleNotFound);
        }
        $dataToSend = json_encode($schedule);
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function postScheduleAction(){

        $this->scheduleService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();

        $params = json_decode($content, true);
        
        $schedule = $this->scheduleService->saveSchedule($params);
        $dataToSend = json_encode(array('schedule' => $schedule['message']));
        return $this->util->setJsonResponse(201, $dataToSend);   
    }
}
