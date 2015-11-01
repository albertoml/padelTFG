<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Annotation;

class AnnotationController extends FOSRestController{

    var $util;

    function __construct(){ 
        $this->util = new Util();
    } 

	public function allAnnotationAction(){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Annotation');
        $annotations = $repository->findAll();
        $dataToSend = json_encode(array('annotation' => $annotations));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getAnnotationAction($id){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Annotation');

        $annotation = $repository->find($id);

        if (!$annotation instanceof Annotation) {
            return $this->util->setResponse(404, Literals::AnnotationNotFound);
        }
        $dataToSend = json_encode(array('annotation' => $annotation));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    private function setAnnotationPost($annotation, $params){
        $annotation->setText($params['text']);
        $annotation->setStatus($this->util->getStatus($this->getDoctrine()->getManager(), 'annotation', 'created'));

        return $annotation;
    }

    private function checkAnnotation($params){
        $isFail = false;
        $message = "";
        if(empty($params['text'])){
            $isFail = true;
            $message .= Literals::TextEmpty;
        }

        if($isFail){
            return $message;
        }
        else{
            return null;
        }
    }

    public function postAnnotationAction(){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Annotation');

    	$params = array();
    	$content = $this->get("request")->getContent();

    	if (!empty($content)){

	    	$params = json_decode($content, true);
            $checked = $this->checkAnnotation($params);
            if($checked != null){
                return $this->util->setResponse(400, $checked);
            }

            $annotation = new Annotation();
            $annotation = $this->setAnnotationPost($annotation, $params);

            $em->persist($annotation);
            $em->flush();

            $dataToSend = json_encode(array('annotation' => $annotation));
            return $this->util->setJsonResponse(201, $dataToSend);

        } else {
            return $this->util->setResponse(400, Literals::EmptyContent);
        }
    }

    private function setAnnotationPut($annotation, $params){
        $annotation->setText(isset($params['text']) ? $params['text'] : $annotation->getText());
        $annotation->setStatus(isset($params['status']) ? $params['status'] : $annotation->getStatus());

        return $annotation;
    }

    public function putAnnotationAction($id){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Annotation');
        $annotation = $repository->find($id);

        if ($annotation instanceof Annotation) {
            $params = array();
            $content = $this->get("request")->getContent();

            if (!empty($content)){

                $params = json_decode($content, true);
                $annotation = $this->setAnnotationPut($annotation, $params);

                $em->persist($annotation);
                $em->flush();

                $dataToSend = json_encode(array('annotation' => $annotation));
                return $this->util->setJsonResponse(200, $dataToSend);
            } else {
                return $this->util->setResponse(400, Literals::EmptyContent);
            }
        } else {
            return $this->util->setResponse(404, Literals::AnnotationNotFound);
        }
    }

    public function deleteAnnotationAction($id){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Annotation');
        $annotation = $repository->find($id);

        if ($annotation instanceof Annotation) {
            $em->remove($annotation);
            $em->flush();

            return $this->util->setResponse(200, Literals::AnnotationDeleted);
        } else {
            return $this->util->setResponse(404, Literals::AnnotationNotFound);
        }
    }
}
