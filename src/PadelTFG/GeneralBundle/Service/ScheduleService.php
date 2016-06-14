<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Schedule;
use PadelTFG\GeneralBundle\Service\ScheduleTrackService as ScheduleTrackService;
use PadelTFG\GeneralBundle\Service\ScheduleRangeService as ScheduleRangeService;
use PadelTFG\GeneralBundle\Service\ScheduleDateService as ScheduleDateService;
use PadelTFG\GeneralBundle\Service\ScheduleRangeDateService as ScheduleRangeDateService;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\GameService as GameService;
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

    public function getScheduleByTournamentId($tournamentId){
        $repository = $this->em->getRepository('GeneralBundle:Schedule');
        $schedule = $repository->findOneByTournament($tournamentId);
        return $schedule;
    }

    private function setScheduleSave($schedule, $params){

        if(!empty($params['tournament'])){
            if(is_int ($params['tournament'])){
                $tournamentService = new TournamentService();
                $tournamentService->setManager($this->em);
                $tournament = $tournamentService->getTournament($params['tournament']);
                $schedule->setTournament($tournament);
            }
            else{
                $schedule->setTournament($params['tournament']);
            }
        }
        else{
            $schedule->setTournament(null);
        }
        $this->em->persist($schedule);
        $this->em->flush();
        return $schedule;
    }

    public function saveScheduleTrack($schedule, $params){

        if(is_int($params['track'])){
            $scheduleTrackService = new ScheduleTrackService();
            $scheduleTrackService->setManager($this->em);
            for ($i=1; $i <= (int) $params['track']; $i++) { 
                $scheduleTrackService->saveScheduleTrack($schedule, $i);
            }
        }
    }

    public function saveScheduleDate($schedule, $params){

        $scheduleRangeDateService = new ScheduleRangeDateService();
        $scheduleRangeDateService->setManager($this->em);
        $scheduleDateService = new ScheduleDateService();
        $scheduleDateService->setManager($this->em);
        $scheduleRangeService = new scheduleRangeService();
        $scheduleRangeService->setManager($this->em);

        foreach ($params['scheduleRanges'] as $rangeCollection) {
            
            $scheduleRangeDate = $scheduleRangeDateService->saveScheduleRangeDate($schedule);

            foreach ($rangeCollection['dates'] as $date) {
                $scheduleDate = $scheduleDateService->saveScheduleDate($scheduleRangeDate, $date);
            }
            
            foreach ($rangeCollection['ranges'] as $range) {
                $scheduleRange = $scheduleRangeService->saveScheduleRange($scheduleRangeDate, $range);
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

    public function scheduleCalculateStartDate($idSchedule){
        $scheduleRangeDateService = new ScheduleRangeDateService();
        $scheduleRangeDateService->setManager($this->em);
        $scheduleDateService = new ScheduleDateService();
        $scheduleDateService->setManager($this->em);

        $startDate = null;

        $scheduleRangeDates = $scheduleRangeDateService->getScheduleRangeDateByScheduleId($idSchedule);
        foreach ($scheduleRangeDates as $scheduleRangeDate) {
            $scheduleDates = $scheduleDateService->getScheduleDateByScheduleRangeDate($scheduleRangeDate);
            foreach ($scheduleDates as $scheduleDate) {
                if($startDate == null){
                    $startDate = $scheduleDate->getDate();
                }
                else{
                    if($scheduleDate->getDate() < $startDate){
                        $startDate = $scheduleDate->getDate();
                    }
                }
            }
        }
        return $startDate->format('Y-m-d');
    }

    public function parseHour($hour){
        $hourArray = substr($hour, 1);
        $hourArray = preg_split("/[:]+/", $hourArray);

        $hourParsed = floatval($hourArray[0]) + floatval($hourArray[1]);
        return $hourParsed; 
    }

    public function scheduleCalculateMaxRange($idSchedule){
        $scheduleRangeDateService = new ScheduleRangeDateService();
        $scheduleRangeDateService->setManager($this->em);
        $scheduleRangeService = new ScheduleRangeService();
        $scheduleRangeService->setManager($this->em);

        $maxRange = null;

        $scheduleRangeDates = $scheduleRangeDateService->getScheduleRangeDateByScheduleId($idSchedule);
        foreach ($scheduleRangeDates as $scheduleRangeDate) {
            $scheduleRanges = $scheduleRangeService->getScheduleRangeByScheduleRangeDate($scheduleRangeDate);
            foreach ($scheduleRanges as $scheduleRange) {
                if($maxRange == null){
                    $maxRange = $scheduleRange->getToHour();
                }
                else{
                    if($this->parseHour($scheduleRange->getToHour()) > $this->parseHour($maxRange)){
                        $maxRange = $scheduleRange->getToHour();
                    }
                }
            }
        }
        return substr($maxRange, 1);
    }

    public function scheduleCalculateMinRange($idSchedule){
        $scheduleRangeDateService = new ScheduleRangeDateService();
        $scheduleRangeDateService->setManager($this->em);
        $scheduleRangeService = new ScheduleRangeService();
        $scheduleRangeService->setManager($this->em);

        $minRange = null;

        $scheduleRangeDates = $scheduleRangeDateService->getScheduleRangeDateByScheduleId($idSchedule);
        foreach ($scheduleRangeDates as $scheduleRangeDate) {
            $scheduleRanges = $scheduleRangeService->getScheduleRangeByScheduleRangeDate($scheduleRangeDate);
            foreach ($scheduleRanges as $scheduleRange) {
                if($minRange == null){
                    $minRange = $scheduleRange->getFromHour();
                }
                else{
                    if($this->parseHour($scheduleRange->getFromHour()) < $this->parseHour($minRange)){
                        $minRange = $scheduleRange->getFromHour();
                    }
                }
            }
        }
        return substr($minRange, 1);
    }

    public function scheduleResourcesCompose($idSchedule){
        $scheduleTrackService = new ScheduleTrackService();
        $scheduleTrackService->setManager($this->em);
        $scheduleTracks = $scheduleTrackService->getScheduleTrackByScheduleId($idSchedule);
        return $scheduleTracks;
    }

    public function scheduleCompose($idSchedule, $gamesToInsert){
        $scheduleTrackService = new ScheduleTrackService();
        $scheduleTrackService->setManager($this->em);
        $scheduleRangeDateService = new ScheduleRangeDateService();
        $scheduleRangeDateService->setManager($this->em);
        $scheduleDateService = new ScheduleDateService();
        $scheduleDateService->setManager($this->em);
        $scheduleRangeService = new scheduleRangeService();
        $scheduleRangeService->setManager($this->em);

        $schedule = array();
        $numMatch = 0;
        
        $scheduleTracks = $scheduleTrackService->getScheduleTrackByScheduleId($idSchedule);

        $scheduleRangeDates = $scheduleRangeDateService->getScheduleRangeDateByScheduleId($idSchedule);
        foreach ($scheduleRangeDates as $scheduleRangeDate) {
            $scheduleDates = $scheduleDateService->getScheduleDateByScheduleRangeDate($scheduleRangeDate);
            $scheduleRanges = $scheduleRangeService->getScheduleRangeByScheduleRangeDate($scheduleRangeDate);
            foreach ($scheduleDates as $scheduleDate) {
                foreach ($scheduleRanges as $scheduleRange) {
                    foreach ($scheduleTracks as $scheduleTrack) {
                        $foundGame = false;
                        $numMatch = $numMatch + 1;
                        if(!is_null($gamesToInsert)){
                            foreach ($gamesToInsert as $game) {
                                if($game->getScheduleId() == strval($numMatch)){

                                    $title = $game->getPair1()->getUser1()->getName();
                                    $title .= ' - ';
                                    $title .= $game->getPair1()->getUser2()->getName();
                                    $title .= ' ' . Literals::vs . ' ';
                                    $title .= $game->getPair2()->getUser1()->getName();
                                    $title .= ' - ';
                                    $title .= $game->getPair2()->getUser2()->getName();
                                    $bgColor = $game->getBgColor();

                                    $match = array( 'id' => strval($numMatch),
                                        'start' => date_format($scheduleDate->getDate(), 'Y-m-d') . $scheduleRange->getFromHour(),
                                        'end' => date_format($scheduleDate->getDate(), 'Y-m-d') . $scheduleRange->getToHour(),
                                        'resourceId' => $scheduleTrack->getId(),
                                        'title' => $title,
                                        'backgroundColor' => $bgColor);
                                    $foundGame = true;
                                }
                            }
                        }
                        if(!$foundGame){
                            $match = array( 'id' => strval($numMatch),
                                'start' => date_format($scheduleDate->getDate(), 'Y-m-d') . $scheduleRange->getFromHour(),
                                'end' => date_format($scheduleDate->getDate(), 'Y-m-d') . $scheduleRange->getToHour(),
                                'resourceId' => $scheduleTrack->getId(),
                                'title' => Literals::NotSet,
                                'backgroundColor' => Literals::NotSet);
                        }
                        $schedule[] = $match;
                    }
                }
            }
        }
        return $schedule;
    }

    public function setDatesToMatchsInTournament($gamesInTournament, $inscriptionsTournament, $idTournament){
        $gameService = new GameService();
        $gameService->setManager($this->em);

        $scheduleEntity = $this->getScheduleByTournamentId($idTournament);

        if($scheduleEntity instanceof Schedule){
            $schedule = $this->scheduleCompose($scheduleEntity->getId(), null);
            foreach ($gamesInTournament as $game) {
                $schedule = $this->insertInSchedule($game, $schedule, $scheduleEntity);
            }
            $schedule = $this->scheduleCompose($scheduleEntity->getId(), $gamesInTournament);
            $scheduleResources = $this->scheduleResourcesCompose($scheduleEntity->getId());

            $scheduleEntity->setScheduleJson(json_encode($schedule));
            $scheduleEntity->setScheduleResourcesJson(json_encode($scheduleResources));
            $scheduleEntity->setStartDate($this->scheduleCalculateStartDate($scheduleEntity->getId()));
            $scheduleEntity->setMinRange($this->scheduleCalculateMinRange($scheduleEntity->getId()));
            $scheduleEntity->setMaxRange($this->scheduleCalculateMaxRange($scheduleEntity->getId()));
            $this->em->persist($scheduleEntity);
            $this->em->flush();
            return $scheduleEntity->getScheduleJson();
        }
        else{
            return null;
        }
    }

    public function insertInSchedule($game, $schedule, $scheduleEntity){
        $inserted = false;
        $scheduleNew = array();

        foreach ($schedule as $rangeIndex => $range) {
            if($range['title'] == Literals::NotSet && !$inserted){

                $game->setStartDate($range['start']);
                $game->setEndDate($range['end']);
                $game->setTrack($range['resourceId']);
                $game->setScheduleId($range['id']);
                $this->em->persist($game);
                $this->em->flush();
                $inserted = true;
            }
            else{
                $scheduleNew[] = $range;
            }
        } 
        return $scheduleNew;
    }
}
