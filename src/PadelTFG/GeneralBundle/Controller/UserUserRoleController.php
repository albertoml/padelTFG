<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\UserUserRoleService as UserUserRoleService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Service\UserService as UserService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\UserUserRole;

class UserUserRoleController extends FOSRestController
{

    var $util;
    var $userRoleService;

    function __construct(){ 
        $this->util = new Util();
        $this->userUserRoleService = new UserUserRoleService();
    } 

	public function allUserUserRoleAction(){
        $this->userUserRoleService->setManager($this->getDoctrine()->getManager());
        $roles = $this->userUserRoleService->getAllUserUserRoles();
        $dataToSend = json_encode(array('role' => $roles));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getUserUserRoleAction($id){
        $this->userUserRoleService->setManager($this->getDoctrine()->getManager());
        $userRole = $this->userUserRoleService->getUserUserRole($id);

        if (!$userRole instanceof UserUserRole) {
            return $this->util->setResponse(404, Literals::UserNotFound);
        }

        $tournaments = $this->addUserAdminTournaments($userRole->getId());

        $dataToSend = json_encode(array('userRole' => $userRole, 'tournament' => $tournaments));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function addUserAdminTournaments($idAdmin){
        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->getDoctrine()->getManager());
        $tournaments = $tournamentService->getTournamentsByAdmin($idAdmin);
        return $tournaments;
    }
}
