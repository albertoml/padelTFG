<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\UserService as UserService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\User;

class UserController extends FOSRestController{

    var $util;
    var $userService;

    function __construct(){ 
        $this->util = new Util();
        $this->userService = new UserService();
    } 

	public function allUserAction(){

        $this->userService->setManager($this->getDoctrine()->getManager());
        $users = $this->userService->allUsers();
        $dataToSend = json_encode(array('user' => $users));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getUserAction($id){

        $this->userService->setManager($this->getDoctrine()->getManager());
        $user = $this->userService->getUser($id);

        if (!$user instanceof User) {
            return $this->util->setResponse(404, Literals::UserNotFound);
        }
        $dataToSend = json_encode(array('user' => $user));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function postUserAction(){

        $this->userService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $user = $this->userService->saveUser($params, $this);
        if($user['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $user['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('user' => $user['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

    public function putUserAction($id){

        $this->userService->setManager($this->getDoctrine()->getManager());
        $user = $this->userService->getUser($id);

        if ($user instanceof User) {

            $params = array();
            $content = $this->get("request")->getContent();
            $params = json_decode($content, true);
            $user = $this->userService->modifyUser($user, $params, $this);
            if($user['result'] == 'fail'){
                $dataToSend = json_encode(array('error' => $user['message']));
                return $this->util->setResponse(400, $dataToSend);
            }
            $dataToSend = json_encode(array('user' => $user['message']));
            return $this->util->setJsonResponse(200, $dataToSend);
            
        } else {
            return $this->util->setResponse(404, Literals::UserNotFound);
        }
    }

    public function deleteUserAction($id){

        $this->userService->setManager($this->getDoctrine()->getManager());
        $user = $this->userService->getUser($id);

        if ($user instanceof User) {
            $user = $this->userService->deleteUser($user);
            return $this->util->setResponse(200, Literals::UserDeleted);
        } else {
            return $this->util->setResponse(404, Literals::UserNotFound);
        }
    }

    public function loginUserAction($email, $password){

        $this->userService->setManager($this->getDoctrine()->getManager());
        $user = $this->userService->loginUser($email, $password);
        if($user['result'] == 'ok'){
            $dataToSend = json_encode(array('user' => $user['message']));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
        else{
            return $this->util->setResponse(400, $user['message']);
        }
    }
}
