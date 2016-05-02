<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class InfoController extends FOSRestController{

    public function infoPhpAction(){
        phpinfo();
    }
}
