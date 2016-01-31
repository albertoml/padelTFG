<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Annotation;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\UserService as UserService;

class AnnotationService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allAnnotations(){
        $repository = $this->em->getRepository('GeneralBundle:Annotation');
        $annotations = $repository->findAll();
        return $annotations;
    }

    public function getAnnotation($id){
        $repository = $this->em->getRepository('GeneralBundle:Annotation');
        $annotation = $repository->find($id);
        return $annotation;
    }

    public function getAnnotationByUser($userId){
        $repository = $this->em->getRepository('GeneralBundle:Annotation');
        $annotations = $repository->findByUser($userId);
        return $annotations;
    }

    private function setAnnotationSave($annotation, $params){
        $annotation->setText(isset($params['text']) ? $params['text'] : '');
        $annotation->setStatus($this->statusService->getStatus('annotation', 'created'));
        $userService = new UserService();
        $userService->setManager($this->em);
        $user = $userService->getUser(isset($params['user']) ? $params['user'] : '');
        $annotation->setUser($user);
        return $annotation;
    }

    public function saveAnnotation($params, $controller){
        $annotation = new Annotation();
        $annotation = $this->setAnnotationSave($annotation, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($annotation);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($annotation);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $annotation);
    }

    private function setAnnotationModify($annotation, $params){
        $annotation->setText(isset($params['text']) ? $params['text'] : $annotation->getText());
        $annotation->setStatus(isset($params['status']) ? $params['status'] : $annotation->getStatus());
        return $annotation;
    }

    public function modifyAnnotation($annotation, $params, $controller){
        $annotation = $this->setAnnotationModify($annotation, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($annotation);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($annotation);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $annotation);
    }

    public function deleteAnnotation($annotation){
        $this->em->remove($annotation);
        $this->em->flush();
    }
}
