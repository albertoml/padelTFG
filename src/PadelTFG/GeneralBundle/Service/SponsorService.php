<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Sponsor;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;

class SponsorService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
    } 

	public function allSponsors(){
        $repository = $this->em->getRepository('GeneralBundle:Sponsor');
        $sponsor = $repository->findAll();
        return $sponsor;
    }

    public function getSponsor($id){
        $repository = $this->em->getRepository('GeneralBundle:Sponsor');
        $sponsor = $repository->find($id);
        return $sponsor;
    }

    private function setSponsorSave($sponsor, $params){
        $sponsor->setName(!empty($params['name']) ? $params['name'] : '');
        $sponsor->setCif(!empty($params['cif']) ? $params['cif'] : '');
        $sponsor->setStatus($this->statusService->getStatus($this->em, 'sponsor', 'active'));

        return $sponsor;
    }

    public function saveSponsor($params, $controller){
        $sponsor = new Sponsor();
        $sponsor = $this->setSponsorSave($sponsor, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($sponsor);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($sponsor);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $sponsor);
    }

    private function setSponsorModify($sponsor, $params){

        $sponsor->setName(isset($params['name']) ? $params['name'] : $sponsor->getName());
        $sponsor->setStatus(isset($params['status']) ? $params['status'] : $sponsor->getStatus());

        return $sponsor;
    }

    public function modifySponsor($sponsor, $params, $controller){
        $sponsor = $this->setSponsorModify($sponsor, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($sponsor);
        $this->em->persist($sponsor);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $sponsor);
    }

    public function deleteSponsor($sponsor){
        $this->em->remove($sponsor);
        $this->em->flush();
    }
}
