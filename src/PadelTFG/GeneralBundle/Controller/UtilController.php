<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;


class UtilController extends FOSRestController{

    var $util;

    function __construct(){ 
        $this->util = new Util();
    } 

	public function getGendersAction(){
        $genders = $this->util->getGenders();
        $dataToSend = json_encode(array('genders' => $genders));
        return $this->util->setJsonResponse(200, $dataToSend);
    }
    public function getUserGendersAction(){
        $genders = $this->util->getUserGenders();
        $dataToSend = json_encode(array('genders' => $genders));
        return $this->util->setJsonResponse(200, $dataToSend);
    }
}
