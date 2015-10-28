<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\User;

class UserController extends FOSRestController{

    var $util;

    function __construct(){ 
        $this->util = new Util();
    } 

	public function allUserAction(){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:User');
        $users = $repository->findAll();
        $dataToSend = json_encode(array('user' => $users));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getUserAction($id){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:User');

        $user = $repository->find($id);

        if (!$user instanceof User) {
            return $this->util->setResponse(404, Literals::UserNotFound);
        }
        $dataToSend = json_encode(array('user' => $user));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    private function setUserPost($user, $params){
        $user->setName($params['name']);
        $user->setLastName($params['lastName']);
        $user->setEmail($params['email']);
        $user->setPassword($params['password']);
        $user->setStatus($this->util->getStatus($this->getDoctrine()->getManager(), 'user', 'registered'));
        $user->setFirstPhone(isset($params['firstPhone']) ? $params['firstPhone'] : '');
        $user->setSecondPhone(isset($params['secondPhone']) ? $params['secondPhone'] : '');
        $user->setAddress(isset($params['address']) ? $params['address'] : '');
        $user->setCity(isset($params['city']) ? $params['city'] : '');
        $user->setCountry(isset($params['country']) ? $params['country'] : '');
        $user->setCP(isset($params['cp']) ? $params['cp'] : '');
        $user->setBirthDate(isset($params['birthDate']) ? $params['birthDate'] : null);
        $user->setProfileImage(isset($params['profileImage']) ? $params['profileImage'] : '');
        $user->setGameLevel(isset($params['gameLevel']) ? $params['gameLevel'] : 0);
        $user->setAlias(isset($params['alias']) ? $params['alias'] : '');

        return $user;
    }

    private function checkUser($params){
        $isFail = false;
        $message = "";
        if(empty($params['name'])){
            $isFail = true;
            $message .= Literals::NameEmpty;
        }
        if(empty($params['lastName'])){
            $isFail = true;
            $message .= Literals::LastNameEmpty;
        }
        if(empty($params['email'])){
            $isFail = true;
            $message .= Literals::EmailEmpty;
        }
        if(empty($params['password'])){
            $isFail = true;
            $message .= Literals::PasswordEmpty;
        }

        if($isFail){
            return $message;
        }
        else{
            return null;
        }
    }

    public function postUserAction(){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:User');

    	$params = array();
    	$content = $this->get("request")->getContent();

    	if (!empty($content)){

	    	$params = json_decode($content, true);
            $checked = $this->checkUser($params);
            if($checked != null){
                return $this->util->setResponse(400, $checked);
            }

            if($this->isUserNew($params['email'])){

                $user = new User();
                $user = $this->setUserPost($user, $params);

                $em->persist($user);
                $em->flush();

                $dataToSend = json_encode(array('user' => $user));
                return $this->util->setJsonResponse(201, $dataToSend);

            } else {
                return $this->util->setResponse(400, Literals::EmailRegistered);
            }
        } else {
            return $this->util->setResponse(400, Literals::EmptyContent);
        }
    }

    private function isUserNew($email){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:User');
        $user = $repository->findOneByEmail($email);

        if (!$user instanceof User) {
            return true;
        }
        return false;
    }

    private function setUserPut($user, $params){
        $user->setName(isset($params['name']) ? $params['name'] : $user->getName());
        $user->setLastName(isset($params['lastName']) ? $params['lastName'] : $user->getLastName());
        $user->setEmail(isset($params['email']) ? $params['email'] : $user->getEmail());
        $user->setPassword(isset($params['password']) ? $params['password'] : $user->getPassword());
        $user->setStatus(isset($params['status']) ? $params['status'] : $user->getStatus());
        $user->setFirstPhone(isset($params['firstPhone']) ? $params['firstPhone'] : $user->getFirstPhone());
        $user->setSecondPhone(isset($params['secondPhone']) ? $params['secondPhone'] : $user->getSecondPhone());
        $user->setAddress(isset($params['address']) ? $params['address'] : $user->getAddress());
        $user->setCity(isset($params['city']) ? $params['city'] : $user->getCity());
        $user->setCountry(isset($params['country']) ? $params['country'] : $user->getCountry());
        $user->setCP(isset($params['cp']) ? $params['cp'] : $user->getCP());
        $user->setBirthDate(isset($params['birthDate']) ? $params['birthDate'] : $user->getBirthDate());
        $user->setProfileImage(isset($params['profileImage']) ? $params['profileImage'] : $user->getProfileImage());
        $user->setGameLevel(isset($params['gameLevel']) ? $params['gameLevel'] : $user->getGameLevel());
        $user->setAlias(isset($params['alias']) ? $params['alias'] : $user->getAlias());

        return $user;
    }

    public function putUserAction($id){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:User');
        $user = $repository->find($id);

        if ($user instanceof User) {
            $params = array();
            $content = $this->get("request")->getContent();

            if (!empty($content)){

                $params = json_decode($content, true);
                $user = $this->setUserPut($user, $params);

                $em->persist($user);
                $em->flush();

                $dataToSend = json_encode(array('user' => $user));
                return $this->util->setJsonResponse(200, $dataToSend);
            } else {
                return $this->util->setResponse(400, Literals::EmptyContent);
            }
        } else {
            return $this->util->setResponse(404, Literals::UserNotFound);
        }
    }

    public function deleteUserAction($id){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:User');
        $user = $repository->find($id);

        if ($user instanceof User) {
            $em->remove($user);
            $em->flush();

            return $this->util->setResponse(200, Literals::UserDeleted);
        } else {
            return $this->util->setResponse(404, Literals::UserNotFound);
        }
    }
}
