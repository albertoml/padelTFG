<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\ScheduleRangeDate;

class ScheduleRangeDateService{

    var $em;

    public function setManager($em){ 
        $this->em = $em;
    } 

    public function getScheduleRangeDate($id){
        $repository = $this->em->getRepository('GeneralBundle:ScheduleRangeDate');
        $scheduleRangeDate = $repository->find($id);
        return $scheduleRangeDate;
    }

    public function getScheduleRangeDateByScheduleId($scheduleId){
        $repository = $this->em->getRepository('GeneralBundle:ScheduleRangeDate');
        $scheduleRangeDates = $repository->findBySchedule($scheduleId);
        return $scheduleRangeDates;
    }

    public function saveScheduleRangeDate($schedule){
    	$scheduleRangeDate = new ScheduleRangeDate();
        $scheduleRangeDate->setSchedule($schedule);
        $this->em->persist($scheduleRangeDate);
        $this->em->flush();
        return $scheduleRangeDate;
    }
    
}
