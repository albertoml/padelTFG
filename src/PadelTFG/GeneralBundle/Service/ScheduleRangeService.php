<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\ScheduleRange;

class ScheduleRangeService{

    var $em;

    public function setManager($em){ 
        $this->em = $em;
    } 

    public function getScheduleRange($id){
        $repository = $this->em->getRepository('GeneralBundle:ScheduleRange');
        $scheduleRange = $repository->find($id);
        return $scheduleRange;
    }

    public function getScheduleRangeByScheduleRangeDate($scheduleRangeDateId){
        $repository = $this->em->getRepository('GeneralBundle:ScheduleRange');
        $scheduleRanges = $repository->findByScheduleRangeDate($scheduleRangeDateId);
        return $scheduleRanges;
    }

    public function saveScheduleRange($scheduleRangeDate, $range){
    	$scheduleRange = new ScheduleRange();
        $scheduleRange->setFromHour($range['fromHour']);
        $scheduleRange->setToHour($range['toHour']);
        $scheduleRange->setScheduleRangeDate($scheduleRangeDate);
        $this->em->persist($scheduleRange);
        $this->em->flush();
    	return $scheduleRange;
    }
    
}
