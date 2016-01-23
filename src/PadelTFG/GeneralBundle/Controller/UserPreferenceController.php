<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\UserPreferenceService as UserPreferenceService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\UserPreference;

class UserPreferenceController extends FOSRestController{

    var $util;
    var $userPrefenceService;

    function __construct(){ 
        $this->util = new Util();
        $this->userPrefenceService = new UserPreferenceService();
    } 

	public function allUserPreferencesAction(){

        $this->userPrefenceService->setManager($this->getDoctrine()->getManager());
        $userPreferences = $this->userPrefenceService->allUsersPreferences();
        $dataToSend = json_encode(array('userPreferences' => $userPreferences));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getUserPreferenceAction($id){

        $this->userPrefenceService->setManager($this->getDoctrine()->getManager());
        $userPreference = $this->userPrefenceService->getUserPreference($id);

        if (!$userPreference instanceof UserPreference) {
            return $this->util->setResponse(404, Literals::UserNotFound);
        }
        $dataToSend = json_encode(array('userPreference' => $userPreference));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function putUserPreferenceAction($id){

        $this->userPrefenceService->setManager($this->getDoctrine()->getManager());
        $userPreference = $this->userPrefenceService->getUserPreference($id);

        if ($userPreference instanceof UserPreference) {

            $params = array();
            $content = $this->get("request")->getContent();
            $params = json_decode($content, true);
            $userPreference = $this->userPrefenceService->modifyUserPreference($userPreference, $params);
            $dataToSend = json_encode(array('userPreferences' => $userPreference['message']));
            return $this->util->setJsonResponse(200, $dataToSend);
            
        } else {
            return $this->util->setResponse(404, Literals::UserNotFound);
        }
    }

    public function deleteUserPreferenceAction($id){

        $this->userPrefenceService->setManager($this->getDoctrine()->getManager());
        $userPreference = $this->userPrefenceService->getUserPreference($id);

        if ($userPreference instanceof UserPreference) {
            $userPreference = $this->userPrefenceService->deleteUserPreference($userPreference);
            return $this->util->setResponse(200, Literals::UserDeleted);
        } else {
            return $this->util->setResponse(404, Literals::UserNotFound);
        }
    }
}
