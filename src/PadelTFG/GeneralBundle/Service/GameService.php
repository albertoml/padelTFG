<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Game;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\PairService as PairService;
use PadelTFG\GeneralBundle\Service\GroupService as GroupService;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Service\ScheduleService as ScheduleService;
use PadelTFG\GeneralBundle\Service\InscriptionService as InscriptionService;

class GameService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allGames(){
        $repository = $this->em->getRepository('GeneralBundle:Game');
        $games = $repository->findAll();
        return $games;
    }

    public function getGame($id){
        $repository = $this->em->getRepository('GeneralBundle:Game');
        $game = $repository->find($id);
        return $game;
    }

    public function getGameByDescription($description){
        $repository = $this->em->getRepository('GeneralBundle:Game');
        $game = $repository->findOneByDescription($description);
        return $game;
    }

    public function getGamesByGroup($idGroup){
        $repository = $this->em->getRepository('GeneralBundle:Game');
        $games = $repository->findByGroup($idGroup);
        return $games;
    }

    public function getGamesByCategory($idCategory){
        $repository = $this->em->getRepository('GeneralBundle:Game');
        $games = $repository->findByCategory($idCategory);
        return $games;
    }

    public function getGamesByTournamentInArray($idTournament){
        $repository = $this->em->getRepository('GeneralBundle:Game');
        $games = $repository->findByTournament($idTournament);
        return $games;
    }

    public function getGamesByTournament($idTournament){
        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);
        $categories = $categoryService->getCategoryByTournament($idTournament);
        $returnGames = array();
        foreach ($categories as $category) {
            $games = $this->getGamesByCategory($category->getId());
            $returnGames[$category->getName() . ';' . $category->getId()] = $games;
        }
        return $returnGames;
    }

    public function getGamesInDrawByTournament($idTournament){
        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);
        $categories = $categoryService->getCategoryByTournament($idTournament);
        $returnGames = array();
        foreach ($categories as $category) {
            $games = $this->getGamesInDrawByCategory($category->getId());
            $returnGames[$category->getName() . ';' . $category->getId()] = $games;
        }
        return $returnGames;
    }

    public function getGamesInDrawByCategory($idCategory){
        $repository = $this->em->getRepository('GeneralBundle:Game');
        $games = $repository->findBy(
            array('category' => $idCategory, 'isDrawGame' => true)
        );
        return $games;
    }

    public function getGamesByPair($idPair){
        $repository = $this->em->getRepository('GeneralBundle:Game');
        $games1 = $repository->findByPair1($idPair);
        $games2 = $repository->findByPair2($idPair);
        return array_merge($games1, $games2);
    }

    public function getGamesByUser($idUser){
        $pairService = new PairService();
        $pairService->setManager($this->em);
        $pairs = $pairService->getPairByUser($idUser);
        $games = array();

        foreach ($pairs as $pair) {
            foreach($this->getGamesByPair($pair->getId()) as $game){
                $games[] = $game;
            }
        }
        return $games;
    }

    private function setGameSave($game, $params){
        $game->setDescription(!empty($params['description']) ? $params['description'] : '');

        if(!empty($params['category'])){
            if(is_int($params['category'])){
                $categoryService = new CategoryService();
                $categoryService->setManager($this->em);
                $cat = $categoryService->getCategory($params['category']);
                $game->setCategory($cat);
            }
            else{
                $game->setCategory($params['category']);
            }
        }
        else{
            $game->setCategory(null);
        }

        if(!empty($params['tournament'])){
            if(is_int ($params['tournament'])){
                $tournamentService = new TournamentService();
                $tournamentService->setManager($this->em);
                $tournament = $tournamentService->getTournament($params['tournament']);
                $game->setTournament($tournament);
            }
            else{
                $game->setTournament($params['tournament']);
            }
        }
        else{
            $game->setTournament(null);
        }

        if(!empty($params['group'])){
            if(is_int ($params['group'])){
                $groupService = new GroupService();
                $groupService->setManager($this->em);
                $group = $groupService->getGroup($params['group']);
                $game->setGroup($group);
            }
            else{
                $game->setGroup($params['group']);
            }
        }
        else{
            $game->setGroup(null);
        }

        $game->setPair1(!empty($params['pair1']) ? $params['pair1'] : null);
        $game->setPair2(!empty($params['pair2']) ? $params['pair2'] : null);
        $game->setScore(!empty($params['score']) ? $params['score'] : '');
        $game->setStatus($this->statusService->getStatus('game', Literals::CreatedGameStatus));
        $game->setStartDate(!empty($params['startDate']) ? $params['startDate'] : null);
        $game->setEndDate(!empty($params['endDate']) ? $params['endDate'] : null);
        $game->setTrack(!empty($params['track']) ? $params['track'] : '');
        $game->setBgColor(!empty($params['bgColor']) ? $params['bgColor'] : '');
        $game->setIsDrawGame(!empty($params['isDrawGame']) ? $params['isDrawGame'] : false);
        return $game;
    }

    public function saveGame($params){
        $game = new Game();
        $game = $this->setGameSave($game, $params);        
        $this->em->persist($game);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $game);
    }

    public function saveGamesTournament($params){

        if(!empty($params['tournamentId'])){
            $tournamentService = new TournamentService();
            $tournamentService->setManager($this->em);
            $tournament = $tournamentService->getTournament($params['tournamentId']);
            $allGamesToReturnInCategory = array();
            if($tournament instanceof Tournament){
                $inscriptionService = new InscriptionService();
                $inscriptionService->setManager($this->em);
                $inscriptionsTournament = $inscriptionService->getInscriptionsByGroupForATournament($params['tournamentId'], null);
                foreach ($inscriptionsTournament as $categoryKey => $inscriptionsCategory) {
                    $gamesForGroup = array();
                    foreach ($inscriptionsCategory as $groupKey => $inscriptionsGroup) {
                        $gamesForGroup[] = $this->doGamesByGroup($inscriptionsGroup);
                    }
                    $allGamesToReturnInCategory[$categoryKey] = $gamesForGroup;
                }  
                $scheduleService = new ScheduleService();
                $scheduleService->setManager($this->em);
                $scheduleService->setDatesToMatchsInTournament($this->getGamesByTournamentInArray($tournament->getId()), $inscriptionService->getInscriptionsByTournamentInArray($tournament->getId()), $tournament->getId());
                $tournament->setStatus($this->statusService->getStatus('tournament', Literals::Matchs_DoneTournamentStatus));
                $this->em->persist($tournament);
                $this->em->flush();

                return array('result' => 'ok', 'message' => $this->getGamesByTournament($tournament->getId()));
            }
            else{
                return array('result' => 'fail', 'message' => Literals::TournamentIdNotCorrect);
            }
        }
        else{
            return array('result' => 'fail', 'message' => Literals::TournamentIdNotCorrect);
        }
    }

    public function doGamesByGroup($inscriptions){
        $category = $inscriptions[0]->getCategory();
        $tournament = $inscriptions[0]->getTournament();
        $group = $inscriptions[0]->getGroup();
        $numberInscriptions = count($inscriptions) - 1;
        $numMatchs = $this->getNumberMatchs(count($inscriptions));
        $pair1 = 0;
        $pair2 = 1;
        $gamesSaved = array();
        for($i = 0 ;$i < $numMatchs;$i++){
            $paramsForSaveGame = array(
                'description' => $tournament->getName(),
                'category' => $category,
                'tournament' => $tournament,
                'group' => $group,
                'pair1' => $inscriptions[$pair1]->getPair(),
                'pair2' => $inscriptions[$pair2]->getPair(),
                'bgColor' => $category->getBgColor()
            );
            $gameObj = $this->saveGame($paramsForSaveGame);
            $gamesSaved[] = $gameObj['message'];
            $pair2 = $pair2 + 1;
            if($pair2 > $numberInscriptions){
                $pair1 = $pair1 + 1;
                $pair2 = $pair1 + 1;
            }
        }
        return $gamesSaved;
    }

    public function getNumberMatchs($numberInscriptions){
        $numMatchs = ($numberInscriptions * ($numberInscriptions - 1)) / 2;
        return $numMatchs;
    }

    public function updateScore($score, $rank, $idPair1, $idPair2){
        if(!is_null($score)){
            $setsPair1 = 0;
            $setsPair2 = 0;
            $numGames = 0;
            $gamesAnt = 0;        
            for($i=0 ; $i<strlen($score) ; $i++){
                if(substr($score,$i,1) != '/' && substr($score,$i,1) != ' '){
                    $games = intval(substr($score,$i,1));
                    if($numGames%2 == 0){
                        $rank[$idPair1]['gamesWon'] = $rank[$idPair1]['gamesWon'] + $games;
                        $rank[$idPair2]['gamesLost'] = $rank[$idPair2]['gamesLost'] + $games;
                        $gamesAnt = $games;
                    }
                    else{
                        $rank[$idPair1]['gamesLost'] = $rank[$idPair1]['gamesLost'] + $games;
                        $rank[$idPair2]['gamesWon'] = $rank[$idPair2]['gamesWon'] + $games;
                        if($gamesAnt > $games){
                            $setsPair1 = $setsPair1 + 1;
                            $rank[$idPair1]['setsWon'] = $rank[$idPair1]['setsWon'] + 1;
                            $rank[$idPair2]['setsLost'] = $rank[$idPair2]['setsLost'] + 1;
                        }
                        else{
                            $setsPair2 = $setsPair2 + 1;
                            $rank[$idPair1]['setsLost'] = $rank[$idPair1]['setsLost'] + 1;
                            $rank[$idPair2]['setsWon'] = $rank[$idPair2]['setsWon'] + 1;
                        }
                    }
                    $numGames = $numGames + 1;
                }
            }
            if($setsPair1 > $setsPair2){
                $rank[$idPair1]['matchsWon'] = $rank[$idPair1]['matchsWon'] + 1;
                $rank[$idPair1]['points'] = $rank[$idPair1]['points'] + Literals::pointsToWinner;
                if($setsPair2 == 1){
                    $rank[$idPair2]['points'] = $rank[$idPair2]['points'] + Literals::pointsToLoserIfWinSet;
                }
                else{
                    $rank[$idPair2]['points'] = $rank[$idPair2]['points'] + Literals::pointsToLoser;
                }
            } 
            else{
                $rank[$idPair2]['matchsWon'] = $rank[$idPair2]['matchsWon'] + 1;
                $rank[$idPair2]['points'] = $rank[$idPair2]['points'] + Literals::pointsToWinner;
                if($setsPair1 == 1){
                    $rank[$idPair1]['points'] = $rank[$idPair1]['points'] + Literals::pointsToLoserIfWinSet;
                }
                else{
                    $rank[$idPair1]['points'] = $rank[$idPair1]['points'] + Literals::pointsToLoser;
                }
            }
        }
        return $rank;
    }

    public function wonGameByPairs($pair1, $pair2, $games){
        foreach ($games as $game) {
            if($game->getPair1()->getId() == $pair1 && $game->getPair2()->getId() == $pair2 || $game->getPair2()->getId() == $pair1 && $game->getPair1()->getId() == $pair2){
                $result = $this->wonGameByScore($game->getScore());
                if($result == 1){
                    return $game->getPair1()->getId();
                }
                else{
                    return $game->getPair2()->getId();
                }
            }
        }
        return null;
    }

    public function wonGameByScore($score){
        
        $setsPair1 = 0;
        $setsPair2 = 0;
        $numGames = 0;
        $gamesAnt = 0; 
        
        for($i=0 ; $i<strlen($score) ; $i++){
            if(substr($score,$i,1) != '/' && substr($score,$i,1) != ' '){
                $games = intval(substr($score,$i,1));
                if($numGames%2 == 0){
                    $gamesAnt = $games;
                }
                else{
                    if($gamesAnt > $games){
                        $setsPair1 = $setsPair1 + 1;
                    }
                    else{
                        $setsPair2 = $setsPair2 + 1;
                    }
                }
                $numGames = $numGames + 1;
            }
        }
        if($setsPair1 > $setsPair2){
            return 1;
        } 
        else{
            return 2;
        }
    }

    public function getNextGameInDraw($game){
        if(!empty($game->getDescription())){
            $description = split('\|', $game->getDescription());
            if(count($description) == 2){
                $descriptionString = $description[0];
                $descriptionR = $description[1];
                $descriptionRound = split('/', $descriptionR);
                if(count($descriptionRound) == 2){
                    $newRound = intval($descriptionRound[0]) / 2;
                    if(intval($descriptionRound[1]) % 2 == 0){
                        $newNumMatch = intval($descriptionRound[1]);
                        $newNumMatch = intval($newNumMatch / 2);
                        $goToPair = 'pair1';
                    }
                    else{
                        $newNumMatch = intval($descriptionRound[1]) - 1;
                        $newNumMatch = intval($newNumMatch / 2);
                        $goToPair = 'pair2';
                    }
                    $newDescription = $descriptionString . '|' . $newRound . '/' . $newNumMatch;
                    $gameNextRound = $this->getGameByDescription($newDescription);
                    if($gameNextRound instanceof Game){
                        return array('game' => $gameNextRound, 'goTo' => $goToPair);
                    }
                }
            }
        }
        return null;
    }

    public function checkPassNextRound($game){
        if($game->getIsDrawGame() && !empty($game->getScore())){
            $nextGame = $this->getNextGameInDraw($game);
            if(!is_null($nextGame)){
                if($nextGame['goTo'] == 'pair1'){
                    if($game->getStatus()->getValue() == Literals::WonPair1GameStatus){
                        $nextGame['game']->setPair1($game->getPair1());
                    }
                    else{
                        $nextGame['game']->setPair1($game->getPair2());   
                    }
                }
                else{
                    if($game->getStatus()->getValue() == Literals::WonPair1GameStatus){
                        $nextGame['game']->setPair2($game->getPair1());
                    }
                    else{
                        $nextGame['game']->setPair2($game->getPair2());   
                    }
                }
                $this->em->persist($nextGame['game']);
                $this->em->flush();
            }
        }
    }

    private function setGameModify($game, $params){

        $game->setDescription(!empty($params['description']) ? $params['description'] : $game->getDescription());
        $game->setPair1(!empty($params['pair1']) ? $params['pair1'] : $game->getPair1());
        $game->setPair2(!empty($params['pair2']) ? $params['pair2'] : $game->getPair2());
        $game->setScore(!empty($params['score']) ? $params['score'] : $game->getScore());
        $game->setStartDate(!empty($params['startDate']) ? $params['startDate'] : $game->getStartDate());
        $game->setEndDate(!empty($params['endDate']) ? $params['endDate'] : $game->getEndDate());
        $game->setTrack(!empty($params['track']) ? $params['track'] : $game->getTrack());
        $game->setBgColor(!empty($params['bgColor']) ? $params['bgColor'] : $game->getBgColor());
        $game->setIsDrawGame(!empty($params['isDrawGame']) ? $params['isDrawGame'] : $game->getIsDrawGame());
        if(!empty($params['status'])){
            $game->setStatus($this->statusService->getStatus('game', $params['status']));
        }
        return $game;
    }

    public function modifyGame($game, $params){
        $game = $this->setGameModify($game, $params);
        $this->em->persist($game);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $game);
    }

    public function deleteGame($game){
        $this->em->remove($game);
        $this->em->flush();
    }
}
