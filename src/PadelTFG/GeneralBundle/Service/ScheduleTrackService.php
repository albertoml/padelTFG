<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\ScheduleTrack;

class ScheduleTrackService{

    var $em;

    public function setManager($em){ 
        $this->em = $em;
    } 

    public function getScheduleTrack($id){
        $repository = $this->em->getRepository('GeneralBundle:ScheduleTrack');
        $scheduleTrack = $repository->find($id);
        return $scheduleTrack;
    }

    public function getScheduleTrackByScheduleId($scheduleId){
        $repository = $this->em->getRepository('GeneralBundle:ScheduleTrack');
        $scheduleTrack = $repository->findBySchedule($scheduleId);
        return $scheduleTrack;
    }

    public function saveScheduleTrack($schedule, $i){
        $scheduleTrack = new ScheduleTrack();
        $scheduleTrack->setTitle(Literals::Track . ' ' . $i);
        $scheduleTrack->setSchedule($schedule);
        $this->em->persist($scheduleTrack);
        $this->em->flush();
        return $scheduleTrack;
    }
}
