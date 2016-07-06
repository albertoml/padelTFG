<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\AnnotationService as AnnotationService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Annotation;

class AnnotationController extends FOSRestController{

    var $util;
    var $annotationService;

    function __construct(){ 
        $this->util = new Util();
        $this->annotationService = new AnnotationService();
    } 

	public function allAnnotationAction(){
        $this->annotationService->setManager($this->getDoctrine()->getManager());
        $annotations = $this->annotationService->allAnnotations();
        $dataToSend = json_encode(array('annotation' => $annotations));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getAnnotationAction($id){

        $this->annotationService->setManager($this->getDoctrine()->getManager());
        $annotation = $this->annotationService->getAnnotation($id);

        if (!$annotation instanceof Annotation) {
            return $this->util->setResponse(404, Literals::AnnotationNotFound);
        }
        $dataToSend = json_encode(array('annotation' => $annotation));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getAnnotationByUserAction($userId){

        $this->annotationService->setManager($this->getDoctrine()->getManager());
        $annotations = $this->annotationService->getAnnotationByUser($userId);
        $dataToSend = json_encode(array('annotation' => $annotations));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function postAnnotationAction(){

        $this->annotationService->setManager($this->getDoctrine()->getManager());

        $params = array();
    	$content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $annotation = $this->annotationService->saveAnnotation($params, $this);
        if($annotation['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $annotation['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('annotation' => $annotation['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

    public function putAnnotationAction($id){

        $this->annotationService->setManager($this->getDoctrine()->getManager());
        $annotation = $this->annotationService->getAnnotation($id);

        if ($annotation instanceof Annotation) {

            $params = array();
            $content = $this->get("request")->getContent();
            $params = json_decode($content, true);
            $annotation = $this->annotationService->modifyAnnotation($annotation, $params, $this);
            if($annotation['result'] == 'fail'){
                $dataToSend = json_encode(array('error' => $annotation['message']));
                return $this->util->setResponse(400, $dataToSend);
            }
            $dataToSend = json_encode(array('annotation' => $annotation['message']));
            return $this->util->setJsonResponse(200, $dataToSend);
            
        } else {
            return $this->util->setResponse(404, Literals::AnnotationNotFound);
        }
    }

    public function deleteAnnotationAction($id){

        $this->annotationService->setManager($this->getDoctrine()->getManager());
        $annotation = $this->annotationService->getAnnotation($id);

        if ($annotation instanceof Annotation) {
            
            $annotation = $this->annotationService->deleteAnnotation($annotation);
            return $this->util->setResponse(200, Literals::AnnotationDeleted);
        } else {
            return $this->util->setResponse(404, Literals::AnnotationNotFound);
        }
    }
}
