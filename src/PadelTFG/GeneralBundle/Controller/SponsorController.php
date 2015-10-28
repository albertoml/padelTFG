<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Sponsor;

class SponsorController extends FOSRestController
{
	var $util;

    function __construct(){ 
        $this->util = new Util();
    } 

	public function allSponsorAction(){
		$repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Sponsor');
        $sponsors = $repository->findAll();
        $dataToSend = json_encode(array('sponsor' => $sponsors));
        return $this->util->setJsonResponse(200, $dataToSend);
	}

	public function getSponsorAction($id){

		$repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Sponsor');

        $sponsor = $repository->find($id);

        if (!$sponsor instanceof Sponsor) {
            return $this->util->setResponse(404, Literals::SponsorNotFound);
        }
        $dataToSend = json_encode(array('sponsor' => $sponsor));
        return $this->util->setJsonResponse(200, $dataToSend);
	}

	private function setSponsorPost($sponsor, $params){
        $sponsor->setName($params['name']);
        $sponsor->setCif($params['cif']);
        $sponsor->setStatus($this->util->getStatus($this->getDoctrine()->getManager(), 'sponsor', 'active'));

        return $sponsor;
    }

    private function checkSponsor($params){
        $isFail = false;
        $message = "";
        if(empty($params['name'])){
            $isFail = true;
            $message .= Literals::NameEmpty;
        }
        if(empty($params['cif'])){
            $isFail = true;
            $message .= Literals::CifEmpty;
        }

        if($isFail){
            return $message;
        }
        else{
            return null;
        }
    }

    public function postSponsorAction(){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Sponsor');

    	$params = array();
    	$content = $this->get("request")->getContent();

    	if (!empty($content)){

	    	$params = json_decode($content, true);
            $checked = $this->checkSponsor($params);
            if($checked != null){
                return $this->util->setResponse(400, $checked);
            }

            if($this->isSponsorNew($params['cif'])){

                $sponsor = new Sponsor();
                $sponsor = $this->setSponsorPost($sponsor, $params);

                $em->persist($sponsor);
                $em->flush();

                $dataToSend = json_encode(array('sponsor' => $sponsor));
                return $this->util->setJsonResponse(201, $dataToSend);  

            } else {
                return $this->util->setResponse(400, Literals::SponsorRegistered);
            } 
        } else {
            return $this->util->setResponse(400, Literals::EmptyContent);
        }
    }

    private function isSponsorNew($cif){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Sponsor');
        $sponsor = $repository->findOneByCif($cif);

        if (!$sponsor instanceof Sponsor) {
            return true;
        }
        return false;
    }

    private function setSponsorPut($sponsor, $params){

        $sponsor->setName(isset($params['name']) ? $params['name'] : $sponsor->getName());
        $sponsor->setCif(isset($params['cif']) ? $params['cif'] : $sponsor->getCif());
        $sponsor->setStatus(isset($params['status']) ? $params['status'] : $sponsor->getStatus());

        return $sponsor;
    }

	public function putSponsorAction($id){

		$em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Sponsor');
        $sponsor = $repository->find($id);

        if ($sponsor instanceof Sponsor) {
            $params = array();
            $content = $this->get("request")->getContent();

            if (!empty($content)){

                $params = json_decode($content, true);
                $sponsor = $this->setSponsorPut($sponsor, $params);

                $em->persist($sponsor);
                $em->flush();

                $dataToSend = json_encode(array('sponsor' => $sponsor));
                return $this->util->setJsonResponse(200, $dataToSend);
            } else {
                return $this->util->setResponse(400, Literals::EmptyContent);
            }
        } else {
            return $this->util->setResponse(404, Literals::SponsorNotFound);
        }

	}

	public function deleteSponsorAction($id){

		$em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Sponsor');
        $sponsor = $repository->find($id);

        if ($sponsor instanceof Sponsor) {
            $em->remove($sponsor);
            $em->flush();

            return $this->util->setResponse(200, Literals::SponsorDeleted);
        } else {
            return $this->util->setResponse(404, Literals::SponsorNotFound);
        }

	}
}
