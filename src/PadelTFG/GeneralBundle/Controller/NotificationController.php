<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\NotificationService as NotificationService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Notification;
use PadelTFG\GeneralBundle\Entity\Tournament;

class NotificationController extends FOSRestController{

    var $util;
    var $notificationService;

    function __construct(){ 
        $this->util = new Util();
        $this->notificationService = new NotificationService();
    } 

	public function allNotificationAction(){

        $this->notificationService->setManager($this->getDoctrine()->getManager());
        $notifications = $this->notificationService->allNotifications();
        $dataToSend = json_encode(array('notification' => $notifications));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getNotificationAction($id){

        $this->notificationService->setManager($this->getDoctrine()->getManager());
        $notification = $this->notificationService->getNotification($id);

        if (!$notification instanceof Notification) {
            return $this->util->setResponse(404, Literals::NotificationNotFound);
        }
        $dataToSend = json_encode(array('notification' => $notification));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function postNotificationAction(){

        $this->notificationService->setManager($this->getDoctrine()->getManager());

    	$params = array();
    	$content = $this->get("request")->getContent();

    	$params = json_decode($content, true);

        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->getDoctrine()->getManager());
        if(!empty($params['tournament'])){
            $tournament = $tournamentService->getTournament(trim($params['tournament']));
            if(!$tournament instanceof Tournament){
                return $this->util->setResponse(404, Literals::TournamentNotFound);
            }
            $notification = $this->notificationService->saveNotification($params, $tournament, $this);
            if($notification['result'] == 'fail'){
                $dataToSend = json_encode(array('error' => $notification['message']));
                return $this->util->setResponse(400, $dataToSend);
            }
            $dataToSend = json_encode(array('notification' => $notification['message']));
            return $this->util->setJsonResponse(201, $dataToSend);
        }
        return $this->util->setResponse(404, Literals::TournamentNotFound);
    }

    public function putNotificationAction($id){

        $this->notificationService->setManager($this->getDoctrine()->getManager());
        $notification = $this->notificationService->getNotification($id);

        if ($notification instanceof Notification) {

            $params = array();
            $content = $this->get("request")->getContent();
            $params = json_decode($content, true);
            $notification = $this->notificationService->modifyNotification($notification, $params, $this);
            if($notification['result'] == 'fail'){
                $dataToSend = json_encode(array('error' => $notification['message']));
                return $this->util->setResponse(400, $dataToSend);
            }
            $dataToSend = json_encode(array('notification' => $notification['message']));
            return $this->util->setJsonResponse(200, $dataToSend);
            
        } else {
            return $this->util->setResponse(404, Literals::NotificationNotFound);
        }
    }

    public function deleteNotificationAction($id){

        $this->notificationService->setManager($this->getDoctrine()->getManager());
        $notification = $this->notificationService->getNotification($id);

        if ($notification instanceof Notification) {
            
            $notification = $this->notificationService->deleteNotification($notification);
            return $this->util->setResponse(200, Literals::NotificationDeleted);
        } else {
            return $this->util->setResponse(404, Literals::NotificationNotFound);
        }
    }
}
