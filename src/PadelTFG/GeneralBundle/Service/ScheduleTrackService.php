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

    public function saveScheduleTrack($schedule, $i){

        $track = new ScheduleTrack();
        $track->setName('Track ' . $i);
        $track->setNumber($i);
        $track->setSchedule($schedule);
        $this->em->persist($track);
        $this->em->flush();
    }
}
