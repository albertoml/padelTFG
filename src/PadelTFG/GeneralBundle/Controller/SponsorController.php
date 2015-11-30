<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\SponsorService as SponsorService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Sponsor;

class SponsorController extends FOSRestController
{
	var $util;
    var $sponsorService;

    function __construct(){ 
        $this->util = new Util();
        $this->sponsorService = new SponsorService();
    } 

	public function allSponsorAction(){
        $this->sponsorService->setManager($this->getDoctrine()->getManager());
        $sponsors = $this->sponsorService->allSponsors();
        $dataToSend = json_encode(array('sponsor' => $sponsors));
        return $this->util->setJsonResponse(200, $dataToSend);
	}

	public function getSponsorAction($id){

        $this->sponsorService->setManager($this->getDoctrine()->getManager());
        $sponsor = $this->sponsorService->getSponsor($id);

        if (!$sponsor instanceof Sponsor) {
            return $this->util->setResponse(404, Literals::SponsorNotFound);
        }
        $dataToSend = json_encode(array('sponsor' => $sponsor));
        return $this->util->setJsonResponse(200, $dataToSend);
	}

    public function postSponsorAction(){

        $this->sponsorService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $sponsor = $this->sponsorService->saveSponsor($params, $this);
        if($sponsor['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $sponsor['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('sponsor' => $sponsor['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

	public function putSponsorAction($id){
        $this->sponsorService->setManager($this->getDoctrine()->getManager());
        $sponsor = $this->sponsorService->getSponsor($id);

        if ($sponsor instanceof Sponsor) {

            $params = array();
            $content = $this->get("request")->getContent();
            $params = json_decode($content, true);
            $sponsor = $this->sponsorService->modifySponsor($sponsor, $params, $this);
            if($sponsor['result'] == 'fail'){
                $dataToSend = json_encode(array('error' => $sponsor['message']));
                return $this->util->setResponse(400, $dataToSend);
            }
            $dataToSend = json_encode(array('sponsor' => $sponsor['message']));
            return $this->util->setJsonResponse(200, $dataToSend);
            
        } else {
            return $this->util->setResponse(404, Literals::SponsorNotFound);
        }
	}

	public function deleteSponsorAction($id){

        $this->sponsorService->setManager($this->getDoctrine()->getManager());
        $sponsor = $this->sponsorService->getSponsor($id);

        if ($sponsor instanceof Sponsor) {
            $sponsor = $this->sponsorService->deleteSponsor($sponsor);
            return $this->util->setResponse(200, Literals::SponsorDeleted);
        } else {
            return $this->util->setResponse(404, Literals::SponsorNotFound);
        }

	}
}
