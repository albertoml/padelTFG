<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\ScheduleDate;

class ScheduleDateService{

    var $em;

    public function setManager($em){ 
        $this->em = $em;
    } 

    
}
