<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Recordal;

class RecordalController extends FOSRestController{

    var $util;

    function __construct(){ 
        $this->util = new Util();
    } 

	public function allRecordalAction(){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Recordal');
        $recordals = $repository->findAll();
        $dataToSend = json_encode(array('recordal' => $recordals));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getRecordalAction($id){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Recordal');

        $recordal = $repository->find($id);

        if (!$recordal instanceof Recordal) {
            return $this->util->setResponse(404, Literals::RecordalNotFound);
        }
        $dataToSend = json_encode(array('recordal' => $recordal));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    private function setRecordalPost($recordal, $params){
        $recordal->setText($params['text']);
        $recordal->setRecordalDate(new \DateTime($params['recordalDate']));
        $recordal->setStatus($this->util->getStatus($this->getDoctrine()->getManager(), 'recordal', 'created'));

        return $recordal;
    }

    private function checkRecordal($params){
        $isFail = false;
        $message = "";
        if(empty($params['text'])){
            $isFail = true;
            $message .= Literals::TextEmpty;
        }
        if(empty($params['recordalDate'])){
            $isFail = true;
            $message .= Literals::RecordalDateEmpty;
        }
        else{
            if(new \DateTime() > new \DateTime($params['recordalDate'])){
                $isFail = true;
                $message .= Literals::RecordalDateIncorrect;
            }
        }

        if($isFail){
            return $message;
        }
        else{
            return null;
        }
    }

    public function postRecordalAction(){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Recordal');

    	$params = array();
    	$content = $this->get("request")->getContent();

    	if (!empty($content)){

	    	$params = json_decode($content, true);
            $checked = $this->checkRecordal($params);
            if($checked != null){
                return $this->util->setResponse(400, $checked);
            }

            $recordal = new Recordal();
            $recordal = $this->setRecordalPost($recordal, $params);

            $em->persist($recordal);
            $em->flush();

            $dataToSend = json_encode(array('recordal' => $recordal));
            return $this->util->setJsonResponse(201, $dataToSend);

        } else {
            return $this->util->setResponse(400, Literals::EmptyContent);
        }
    }

    private function setRecordalPut($recordal, $params){
        $recordal->setText(isset($params['text']) ? $params['text'] : $recordal->getText());
        $recordal->setRecordalDate(isset($params['recordalDate']) ? new \DateTime($params['recordalDate']) : $recordal->getRecordalDate());
        $recordal->setStatus(isset($params['status']) ? $params['status'] : $recordal->getStatus());

        return $recordal;
    }

    public function putRecordalAction($id){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Recordal');
        $recordal = $repository->find($id);

        if ($recordal instanceof Recordal) {
            $params = array();
            $content = $this->get("request")->getContent();

            if (!empty($content)){

                $params = json_decode($content, true);

                if(empty($params['recordalDate']) || new \DateTime() < new \DateTime($params['recordalDate'])){

                    $recordal = $this->setRecordalPut($recordal, $params);

                    $em->persist($recordal);
                    $em->flush();

                    $dataToSend = json_encode(array('recordal' => $recordal));
                    return $this->util->setJsonResponse(200, $dataToSend);
                } else {
                    return $this->util->setResponse(400, Literals::RecordalDateIncorrect);
                }
            } else {
                return $this->util->setResponse(400, Literals::EmptyContent);
            }
        } else {
            return $this->util->setResponse(404, Literals::RecordalNotFound);
        }
    }

    public function deleteRecordalAction($id){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Recordal');
        $recordal = $repository->find($id);

        if ($recordal instanceof Recordal) {
            $em->remove($recordal);
            $em->flush();

            return $this->util->setResponse(200, Literals::RecordalDeleted);
        } else {
            return $this->util->setResponse(404, Literals::RecordalNotFound);
        }
    }
}
