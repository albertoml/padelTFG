<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Notification;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;

class NotificationService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allNotifications(){
        $repository = $this->em->getRepository('GeneralBundle:Notification');
        $notifications = $repository->findAll();
        return $notifications;
    }

    public function getNotification($id){
        $repository = $this->em->getRepository('GeneralBundle:Notification');
        $notification = $repository->find($id);
        return $notification;
    }

    private function setNotificationSave($notification, $params, $tournament){
        $notification->setText(isset($params['text']) ? $params['text'] : '');
        $notification->setNotificationDate(!empty($params['notificationDate']) ? \DateTime::createFromFormat('d/m/Y', $params['notificationDate']) : null);
        $notification->setTournament($tournament);
        $notification->setStatus($this->statusService->getStatus($this->em, 'notification', 'created'));
        return $notification;
    }

    public function saveNotification($params, $tournament, $controller){
        $notification = new Notification();
        $notification = $this->setNotificationSave($notification, $params, $tournament);
        $validator = $controller->get('validator');
        $errors = $validator->validate($notification);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($notification);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $notification);
    }

    private function setNotificationModify($notification, $params){
        $notification->setText(isset($params['text']) ? $params['text'] : $notification->getText());
        $notification->setStatus(isset($params['status']) ? $params['status'] : $notification->getStatus());
        $notification->setNotificationDate(!empty($params['notificationDate']) ? \DateTime::createFromFormat('d/m/Y', $params['notificationDate']) : $notification->getNotificationDate());
        return $notification;
    }

    public function modifyNotification($notification, $params, $controller){
        $notification = $this->setNotificationModify($notification, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($notification);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($notification);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $notification);
    }

    public function deleteNotification($notification){
        $this->em->remove($notification);
        $this->em->flush();
    }
}
