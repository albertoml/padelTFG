<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Schedule;
use PadelTFG\GeneralBundle\Entity\ScheduleDate;
use PadelTFG\GeneralBundle\Entity\ScheduleRange;
use PadelTFG\GeneralBundle\Entity\ScheduleRangeDate;
use PadelTFG\GeneralBundle\Service\ScheduleTrackService as ScheduleTrackService;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;


class ScheduleService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

    public function getSchedule($id){
        $repository = $this->em->getRepository('GeneralBundle:Schedule');
        $schedule = $repository->find($id);
        return $schedule;
    }

    private function setScheduleSave($schedule, $params){

        if(!empty($params['tournament'])){
            if(is_int ($params['tournament'])){
                $tournamentService = new TournamentService();
                $tournamentService->setManager($this->em);
                $tournament = $tournamentService->getTournament($params['tournament']);
                $schedule->setTournament($tournament);
                $this->em->persist($schedule);
                $this->em->flush();
            }
            else{
                $schedule->setTournament($params['tournament']);
            }
        }
        else{
            $schedule->setTournament(null);
        }
        return $schedule;
    }

    public function saveScheduleTrack($schedule, $params){

        if(is_int($params['track'])){
            $scheduleTrackService = new ScheduleTrackService();
            $scheduleTrackService->setManager($this->em);
            for ($i=0; $i < (int) $params['track']; $i++) { 
                $scheduleTrackService->saveScheduleTrack($schedule, $i);
            }
        }
    }

    public function saveScheduleDate($schedule, $params){

        foreach ($params['scheduleRanges'] as $rangeCollection) {
            
            $scheduleRangeDate = new ScheduleRangeDate();
            $scheduleRangeDate->setSchedule($schedule);
            $this->em->persist($scheduleRangeDate);
            $this->em->flush();

            foreach ($rangeCollection['dates'] as $date) {
                $scheduleDate = new ScheduleDate();
                $scheduleDate->setDate(new \DateTime($date));
                $scheduleDate->setScheduleRangeDate($scheduleRangeDate);
                $this->em->persist($scheduleDate);
            }
            
            foreach ($rangeCollection['ranges'] as $range) {
                $scheduleRange = new ScheduleRange();
                $scheduleRange->setFromHour($range['fromHour']);
                $scheduleRange->setToHour($range['toHour']);
                $scheduleRange->setScheduleRangeDate($scheduleRangeDate);
                $this->em->persist($scheduleRange);
            }
        }
        $this->em->flush();
    }

    public function saveSchedule($params){
        $schedule = new Schedule();
        $schedule = $this->setScheduleSave($schedule, $params);
        $this->saveScheduleTrack($schedule, $params);
        $this->saveScheduleDate($schedule, $params);
        return array('result' => 'ok', 'message' => $schedule);
    }

    public function scheduleCompose($idSchedule){

    }

    public function setDatesToMatchsInTournament($gamesInTournament, $inscriptionsTournament){
        foreach ($inscriptionsTournament as $categoryKey => $inscriptionsCategory) {
            foreach ($inscriptionsCategory as $groupKey => $inscriptionsGroup) {
                $gamesForGroup[] = $this->doGamesDateByGroup($inscriptionsGroup);
            }
            $allGamesToReturnInCategory[$categoryKey] = $gamesForGroup;
        }
    }

    public function doGamesDateByGroup($inscriptions){

    }
}
