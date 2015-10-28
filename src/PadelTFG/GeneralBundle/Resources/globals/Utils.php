<?php

namespace PadelTFG\GeneralBundle\Resources\globals;

use Symfony\Component\HttpFoundation\Response as Response;

class Utils
{
	public function setResponse($status, $content){
        $response = new Response();
        $response->setStatusCode($status);
        $response->headers->set('Content-Type', 'application/text');
        $response->setContent($content);
        return $response;
    }

    public function setJsonResponse($status, $content){
        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($status);
        return $response;
    }

    public function factoryStatusController($manager, $entity){
        $repository = null;
        switch ($entity) {
            case 'user':
                $repository = $manager->getRepository('GeneralBundle:UserStatus');
                break;
            case 'tournament':
                $repository = $manager->getRepository('GeneralBundle:TournamentStatus');
                break;
            case 'sponsor':
                $repository = $manager->getRepository('GeneralBundle:SponsorStatus');
                break;
            case 'recordal':
                $repository = $manager->getRepository('GeneralBundle:RecordalStatus');
                break;
            case 'notification':
                $repository = $manager->getRepository('GeneralBundle:NotificationStatus');
                break;
            case 'game':
                $repository = $manager->getRepository('GeneralBundle:GameStatus');
                break;
            case 'annotation':
                $repository = $manager->getRepository('GeneralBundle:AnnotationStatus');
                break;
        }
        return $repository;
    }

    public function getStatus($manager, $entity, $statusName){
        $repository = $this->factoryStatusController($manager, $entity);
        if($repository!=null){
            $status = $repository->findOneByValue($statusName);
            if($status != null){
                return $status; 
            }
        }
        return null; 
    }
}
