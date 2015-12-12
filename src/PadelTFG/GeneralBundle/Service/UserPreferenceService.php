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
        $userPreference->setName($params['name']);
        $userPreference->setLastName($params['lastName']);
        $userPreference->setEmail($params['email']);
        $userPreference->setStatus($params['status']);
        $userPreference->setRole($params['role']);
        $userPreference->setFirstPhone($params['firstPhone']);
        $userPreference->setSecondPhone($params['secondPhone']);
        $userPreference->setAddress($params['address']);
        $userPreference->setCity($params['city']);
        $userPreference->setCountry($params['country']);
        $userPreference->setCP($params['cp']);
        $userPreference->setBirthDate($params['birthDate']);
        $userPreference->setProfileImage($params['profileImage']);
        $userPreference->setGameLevel($params['gameLevel']);
        $userPreference->setAlias($params['alias']);
        $userPreference->setNotification($params['notification']);
        $userPreference->setRegistrationDate($params['registrationDate']);

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
