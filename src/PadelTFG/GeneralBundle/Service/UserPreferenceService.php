<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\UserPreference;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;

class UserPreferenceService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allUsersPreferences(){
        $repository = $this->em->getRepository('GeneralBundle:UserPreference');
        $UserPreferences = $repository->findAll();
        return $UserPreferences;
    }

    public function getUserPreference($id){
        $repository = $this->em->getRepository('GeneralBundle:UserPreference');
        $userPreference = $repository->find($id);
        return $userPreference;
    }

    private function setUserPreferenceSave($userPreference, $id){
        $userPreference->setId($id);
        $userPreference->setName(true);
        $userPreference->setLastName(true);
        $userPreference->setEmail(true);
        $userPreference->setStatus(true);
        $userPreference->setFirstPhone(true);
        $userPreference->setCity(true);
        $userPreference->setRole(false);
        $userPreference->setSecondPhone(false);
        $userPreference->setAddress(false);
        $userPreference->setCountry(false);
        $userPreference->setCP(false);
        $userPreference->setBirthDate(false);
        $userPreference->setProfileImage(false);
        $userPreference->setGameLevel(false);
        $userPreference->setAlias(false);
        $userPreference->setNotification(false);
        $userPreference->setRegistrationDate(false);
        $userPreference->setGender(false);

        return $userPreference;
    }

    public function saveUserPreference($id){
        $userPreference = new UserPreference();
        $userPreference = $this->setUserPreferenceSave($userPreference, $id);
        $this->em->persist($userPreference);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $userPreference);
    }

    private function setUserPreferenceModify($userPreference, $params){
        $userPreference->setName(isset($params['name']) ? $params['name'] : false );
        $userPreference->setLastName(isset($params['lastName']) ? $params['lastName'] : false );
        $userPreference->setEmail(isset($params['email']) ? $params['email'] : false );
        $userPreference->setStatus(isset($params['status']) ? $params['status'] : false );
        $userPreference->setRole(isset($params['role']) ? $params['role'] : false );
        $userPreference->setFirstPhone(isset($params['firstPhone']) ? $params['firstPhone'] : false );
        $userPreference->setSecondPhone(isset($params['secondPhone']) ? $params['secondPhone'] : false );
        $userPreference->setAddress(isset($params['address']) ? $params['address'] : false );
        $userPreference->setCity(isset($params['city']) ? $params['city'] : false );
        $userPreference->setCountry(isset($params['country']) ? $params['country'] : false );
        $userPreference->setCP(isset($params['cp']) ? $params['cp'] : false );
        $userPreference->setBirthDate(isset($params['birthDate']) ? $params['birthDate'] : false );
        $userPreference->setProfileImage(isset($params['profileImage']) ? $params['profileImage'] : false );
        $userPreference->setGameLevel(isset($params['gameLevel']) ? $params['gameLevel'] : false );
        $userPreference->setAlias(isset($params['alias']) ? $params['alias'] : false );
        $userPreference->setNotification(isset($params['notification']) ? $params['notification'] : false );
        $userPreference->setRegistrationDate(isset($params['registrationDate']) ? $params['registrationDate'] : false );
        $userPreference->setGender(isset($params['gender']) ? $params['gender'] : false );

        return $userPreference;
    }

    public function modifyUserPreference($userPreference, $params){
        $userPreference = $this->setUserPreferenceModify($userPreference, $params);
        $this->em->persist($userPreference);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $userPreference);
    }

    public function deleteUserPreference($userPreference){
        $this->em->remove($userPreference);
        $this->em->flush();
    }
}
