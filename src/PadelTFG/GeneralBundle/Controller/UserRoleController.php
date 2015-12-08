<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\UserRoleService as UserRoleService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

class UserRoleController extends FOSRestController
{

    var $util;
    var $userRoleService;

    function __construct(){ 
        $this->util = new Util();
        $this->userRoleService = new UserRoleService();
    } 

	public function allUserRoleAction(){
        $this->userRoleService->setManager($this->getDoctrine()->getManager());
        $roles = $this->userRoleService->getAllUserRoles();
        $dataToSend = json_encode(array('role' => $roles));
        return $this->util->setJsonResponse(200, $dataToSend);
    }
}
