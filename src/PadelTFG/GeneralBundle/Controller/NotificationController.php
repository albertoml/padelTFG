<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Notification;
use PadelTFG\GeneralBundle\Entity\Tournament;

class NotificationController extends FOSRestController{

    var $util;

    function __construct(){ 
        $this->util = new Util();
    } 

	public function allNotificationAction(){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Notification');
        $notifications = $repository->findAll();
        $dataToSend = json_encode(array('notification' => $notifications));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getNotificationAction($id){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Notification');

        $notification = $repository->find($id);

        if (!$notification instanceof Notification) {
            return $this->util->setResponse(404, Literals::NotificationNotFound);
        }
        $dataToSend = json_encode(array('notification' => $notification));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    private function setNotificationPost($notification, $params, $tournament){
        $notification->setText($params['text']);
        $notification->setTournament($tournament);
        $notification->setNotificationDate(new \DateTime($params['notificationDate']));
        $notification->setStatus($this->util->getStatus($this->getDoctrine()->getManager(), 'notification', 'created'));

        return $notification;
    }

    private function checkNotification($params){
        $isFail = false;
        $message = "";
        if(empty($params['text'])){
            $isFail = true;
            $message .= Literals::TextEmpty;
        }
        if(empty($params['tournament'])){
            $isFail = true;
            $message .= Literals::TournamentEmpty;
        }
        if(empty($params['notificationDate'])){
            $isFail = true;
            $message .= Literals::NotificationDateEmpty;
        }
        else{
            if(new \DateTime() > new \DateTime($params['notificationDate'])){
                $isFail = true;
                $message .= Literals::NotificationDateIncorrect;
            }
        }

        if($isFail){
            return $message;
        }
        else{
            return null;
        }
    }

    public function postNotificationAction(){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Notification');
        $repositoryTournament = $em->getRepository('GeneralBundle:Tournament');

    	$params = array();
    	$content = $this->get("request")->getContent();

    	if (!empty($content)){

	    	$params = json_decode($content, true);
            $checked = $this->checkNotification($params);
            if($checked != null){
                return $this->util->setResponse(400, $checked);
            }

            $tournament = $repositoryTournament->find(trim($params['tournament']));
            if($tournament != null){

                $notification = new Notification();
                $notification = $this->setNotificationPost($notification, $params, $tournament);

                $em->persist($notification);
                $em->flush();

                $dataToSend = json_encode(array('notification' => $notification));
                return $this->util->setJsonResponse(201, $dataToSend);
            }
            else{
                return $this->util->setResponse(400, Literals::TournamentIncorrect);
            }

        } else {
            return $this->util->setResponse(400, Literals::EmptyContent);
        }
    }

    private function setNotificationPut($notification, $params, $tournament){
        if($tournament != null){
            $notification->setTournament($tournament);
        }
        $notification->setText(isset($params['text']) ? $params['text'] : $notification->getText());
        $notification->setNotificationDate(isset($params['notificationDate']) ? new \DateTime($params['notificationDate']) : $notification->getNotificationDate());
        $notification->setStatus(isset($params['status']) ? $params['status'] : $notification->getStatus());

        return $notification;
    }

    public function putNotificationAction($id){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Notification');
        $notification = $repository->find($id);

        if ($notification instanceof Notification) {
            $params = array();
            $content = $this->get("request")->getContent();

            if (!empty($content)){

                $params = json_decode($content, true);

                if(empty($params['notificationDate']) || new \DateTime() < new \DateTime($params['notificationDate'])){

                    if(!empty($params['tournament'])){
                    
                        $repositoryTournament = $em->getRepository('GeneralBundle:Tournament');
                        $tournament = $repositoryTournament->find($params['tournament']);
                        if($tournament == null){
                            return $this->util->setResponse(400, Literals::TournamentIncorrect);
                        }
                    } else {
                        $tournament = null;
                    }
                    $notification = $this->setNotificationPut($notification, $params, $tournament);

                    $em->persist($notification);
                    $em->flush();

                    $dataToSend = json_encode(array('notification' => $notification));
                    return $this->util->setJsonResponse(200, $dataToSend);
                } else {
                    return $this->util->setResponse(400, Literals::NotificationDateIncorrect);
                }
            } else {
                return $this->util->setResponse(400, Literals::EmptyContent);
            }
        } else {
            return $this->util->setResponse(404, Literals::NotificationNotFound);
        }
    }

    public function deleteNotificationAction($id){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Notification');
        $notification = $repository->find($id);

        if ($notification instanceof Notification) {
            $em->remove($notification);
            $em->flush();

            return $this->util->setResponse(200, Literals::NotificationDeleted);
        } else {
            return $this->util->setResponse(404, Literals::NotificationNotFound);
        }
    }
}
