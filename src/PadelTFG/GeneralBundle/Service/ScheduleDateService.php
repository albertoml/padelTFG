<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\ScheduleDate;

class ScheduleDateService{

    var $em;

    public function setManager($em){ 
        $this->em = $em;
    } 

    public function getScheduleDate($id){
        $repository = $this->em->getRepository('GeneralBundle:ScheduleDate');
        $scheduleDate = $repository->find($id);
        return $scheduleDate;
    }

    public function getScheduleDateByScheduleRangeDate($scheduleRangeDateId){
        $repository = $this->em->getRepository('GeneralBundle:ScheduleDate');
        $scheduleDates = $repository->findByScheduleRangeDate($scheduleRangeDateId);
        return $scheduleDates;
    }

    public function saveScheduleDate($scheduleRangeDate, $date){
    	$scheduleDate = new ScheduleDate();
        $scheduleDate->setDate(new \DateTime($date));
        $scheduleDate->setScheduleRangeDate($scheduleRangeDate);
        $this->em->persist($scheduleDate);
        $this->em->flush();
    	return $scheduleDate;
    }
    
}
