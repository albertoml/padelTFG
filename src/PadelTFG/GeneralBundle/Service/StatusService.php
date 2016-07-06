<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\UserStatus;
use PadelTFG\GeneralBundle\Entity\TournamentStatus;
use PadelTFG\GeneralBundle\Entity\SponsorStatus;
use PadelTFG\GeneralBundle\Entity\RecordalStatus;
use PadelTFG\GeneralBundle\Entity\NotificationStatus;
use PadelTFG\GeneralBundle\Entity\GameStatus;
use PadelTFG\GeneralBundle\Entity\AnnotationStatus;
use PadelTFG\GeneralBundle\Entity\InscriptionStatus;

class StatusService{

    var $em;

    public function setManager($em){ 
        $this->em = $em;
    } 

	public function factoryStatusController($entity){
        $repository = null;
        switch ($entity) {
            case 'user':
                $repository = $this->em->getRepository('GeneralBundle:UserStatus');
                break;
            case 'tournament':
                $repository = $this->em->getRepository('GeneralBundle:TournamentStatus');
                break;
            case 'sponsor':
                $repository = $this->em->getRepository('GeneralBundle:SponsorStatus');
                break;
            case 'recordal':
                $repository = $this->em->getRepository('GeneralBundle:RecordalStatus');
                break;
            case 'notification':
                $repository = $this->em->getRepository('GeneralBundle:NotificationStatus');
                break;
            case 'game':
                $repository = $this->em->getRepository('GeneralBundle:GameStatus');
                break;
            case 'annotation':
                $repository = $this->em->getRepository('GeneralBundle:AnnotationStatus');
                break;
            case 'inscription':
                $repository = $this->em->getRepository('GeneralBundle:InscriptionStatus');
                break;
        }
        return $repository;
    }

    public function getStatus($entity, $statusName){
        $repository = $this->factoryStatusController($entity);
        if($repository!=null){
            $status = $repository->findOneByValue($statusName);
            if($status != null){
                return $status; 
            }
        }
        return null; 
    }

    public function getAllStatus($entity){
        $repository = $this->factoryStatusController($entity);
        if($repository!=null){
            $status = $repository->findAll();
            return $status; 
        }
        return null; 
    }
}
