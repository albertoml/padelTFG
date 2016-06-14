<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Service\InscriptionService as InscriptionService;
use PadelTFG\GeneralBundle\Service\GameService as GameService;

class GroupService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allGroups(){
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $groups = $repository->findAll();
        return $groups;
    }

    public function getGroup($id){
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->find($id);
        return $group;
    }

    public function getGroupsByCategory($idCategory){
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $groups = $repository->findByCategory($idCategory);
        return $groups;
    }

    public function getNumGroupsByCategory($idCategory){
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $groups = $repository->findByCategory($idCategory);
        return count($groups);
    }

    public function getGroupsByTournament($idTournament){
        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);
        $categories = $categoryService->getCategoryByTournament($idTournament);
        $returnGroups = array();
        foreach ($categories as $category) {
            $groups = $this->getGroupsByCategory($category->getId());
            $returnGroups[$category->getName() . ';' . $category->getId()] = $groups;
        }
        return $returnGroups;
    }

    private function setGroupSave($group, $params){
        $group->setName(!empty($params['name']) ? $params['name'] : 'No name');

        if(!empty($params['category'])){
            if(is_int($params['category'])){
                $categoryService = new CategoryService();
                $categoryService->setManager($this->em);
                $cat = $categoryService->getCategory($params['category']);
                $group->setCategory($cat);
            }
            else{
                $group->setCategory($params['category']);
            }
        }
        else{
            $group->setCategory(null);
        }

        if(!empty($params['tournament'])){
            if(is_int ($params['tournament'])){
                $tournamentService = new TournamentService();
                $tournamentService->setManager($this->em);
                $cat = $tournamentService->getTournament($params['tournament']);
                $group->setTournament($cat);
            }
            else{
                $group->setTournament($params['tournament']);
            }
        }
        else{
            $group->setTournament(null);
        }

        $group->setNumPairs(is_int($params['numPairs']) ? $params['numPairs'] : null);
        return $group;
    }

    public function saveGroup($params){
        $group = new GroupCategory();
        $group = $this->setGroupSave($group, $params);        
        $this->em->persist($group);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $group);
    }

    public function saveGroupsTournament($params){

        foreach ($params as $catId => $category) {
            $persistGroupsCategory = array();
            foreach ($category as $groupKey => $group) {
                if($group['groupId'] != 'newGroup'){
                    $groupEntity = $this->getGroup($group['groupId']);
                    $groupEntity = $this->modifyGroup($groupEntity, $group);
                    $this->checkPairsInGroup($groupEntity['message'], $group['category'], $group['pairs']);
                    $persistGroupsCategory[] = $groupEntity['message'];
                }
                else{
                    $groupResult = $this->saveGroup($group);
                    $this->checkPairsInGroup($groupResult['message'], $group['category'], $group['pairs']);
                    $persistGroupsCategory[] = $groupResult['message'];
                }
            }
            $allGroups = $this->getGroupsByCategory($catId);
            foreach ($allGroups as $groupCandidate) {
                if(!$this->containGroup($groupCandidate, $persistGroupsCategory)){
                    $this->deleteGroup($groupCandidate);
                }
            }
        }
        return array('result' => 'ok', 'message' => $persistGroupsCategory);
    }

    private function containGroup($group, $arrayGroups){
        foreach ($arrayGroups as $item) {
            if($item->getId() == $group->getId()){
                return true;
            }
        }
        return false;
    }

    public function checkPairsInGroup($group, $categoryId, $pairs){

        $inscriptionService = new InscriptionService();
        $inscriptionService->setManager($this->em);
        $inscirptions  = array();

        foreach ($pairs as $pair) {
            $inscriptionEntity = $inscriptionService->getInscriptionsByPairAndCategory($pair, $categoryId);
            if($inscriptionEntity instanceof Inscription){
                if($inscriptionEntity->getGroup()->getId() != $group->getId()){
                    $inscriptionEntity->setGroup($group);
                    $this->em->persist($inscriptionEntity);
                }
            }
        }
        $this->em->flush();
    }

    public function calculateClassficationByGroup($inscriptions, $group){
        $gameService = new GameService();
        $gameService->setManager($this->em);
        $rank = array();
        foreach ($inscriptions as $inscription) {
            $rank[$inscription->getPair()->getId()] = array('matchsWon' => 0, 'setsWon' => 0, 'setsLost' => 0, 'gamesWon' => 0, 'gamesLost' => 0, 'points' => 0, 'pair' => $inscription->getPair()->getId());
        }

        $games = $gameService->getGamesByGroup($group->getId());
        foreach ($games as $game) {
            if(!is_null($game->getScore()) && $game->getScore() != ''){
                $rank = $gameService->updateScore($game->getScore(), $rank, $game->getPair1()->getId(), $game->getPair2()->getId());    
            }
        }
        if(!is_null($rank)){
            $rank = $this->sortByScore($rank);
            if(Literals::doubleTieResolveByGame == "true"){
                $rank = $this->resolveDobulesDraws($rank, $games);
            }
            $position = 0;
            foreach ($rank as $r) {
                foreach ($inscriptions as $inscription) {
                    if($inscription->getPair()->getId() == $r['pair']){
                        $inscription->setClassifiedPositionInGroup($position);
                        $this->em->persist($inscription);
                        $position = $position + 1;
                    }
                }
            }
        }
        return $rank;
    }

    public function sortByScorePoints($a, $b){

        if($a['points'] > $b['points']){
            return -1;
        }
        else if($a['points'] < $b['points']){
            return 1;
        }
        else{
            if($a['matchsWon'] > $b['matchsWon']){
                return -1;
            }
            else if($a['matchsWon'] < $b['matchsWon']){
                return 1;
            }
            else{
                if($a['setsWon'] > $b['setsWon']){
                    return -1;
                }
                else if($a['setsWon'] < $b['setsWon']){
                    return 1;
                }
                else{
                    if($a['setsLost'] < $b['setsLost']){
                        return -1;
                    }
                    else if($a['setsLost'] > $b['setsLost']){
                        return 1;
                    }
                    else{
                        if($a['gamesWon'] > $b['gamesWon']){
                            return -1;
                        }
                        else if($a['gamesWon'] < $b['gamesWon']){
                            return 1;
                        }
                        else{
                            if($a['gamesLost'] < $b['gamesLost']){
                                return -1;
                            }
                            else if($a['gamesLost'] > $b['gamesLost']){
                                return 1;
                            }
                            else{
                                return 0;
                            } 
                        } 
                    } 
                }   
            }
        }
    }

    public function sortByScore($rank){
        
        usort($rank ,array($this, "sortByScorePoints"));
        return $rank;
    }

    public function resolveDobulesDraws($rank, $games){
        $pivotPair = -1;
        $pivotPoints = -1;
        $drawFound = 0;
        foreach ($rank as $r) {
            $actualPoint = $r['points'];
            $actualPair = $r['pair'];
            if($pivotPoints == $actualPoint){
                $drawFound = $drawFound + 1;
                $tiePair = $actualPair;
            }
            else{
                if($drawFound == 1){
                    $rank = $this->resolveDraw($pivotPair, $tiePair, $rank, $games);
                }
                $pivotPair = $actualPair;
                $pivotPoints = $actualPoint;
                $drawFound = 0;
            }
        }
        if($drawFound == 1){
            $rank = $this->resolveDraw($pivotPair, $tiePair, $rank, $games);
        }
        return $rank;
    }

    public function resolveDraw($pair1, $pair2, $rank, $games){
        
        $gameService = new GameService();
        $gameService->setManager($this->em);
        $resultWonGame = $gameService->wonGameByPairs($pair1, $pair2, $games);
        if($resultWonGame == $pair2){

            $first = 0;
                
            foreach ($rank as $r) {
                if($r['pair'] == $pair1){
                    $tmp1 = $r;
                }
                else if($r['pair'] == $pair2){
                    $tmp2 = $r;
                }
            }
            $rankNew = array();
            foreach ($rank as $r) {
                if($r['pair'] == $pair1 || $r['pair'] == $pair2){
                    if($first == 0){
                        $rankNew[] = $tmp2;
                        $first = 1;
                    }
                    else{
                        $rankNew[] = $tmp1;
                    }
                }
                else{
                    $rankNew[] = $r;
                }
            }
            return $rankNew;
        }
        return $rank;
    }

    private function setGroupModify($group, $params){

        $group->setName(!empty($params['name']) ? $params['name'] : $group->getName());
        $group->setNumPairs(is_int($params['numPairs']) ? $params['numPairs'] : $group->getNumPairs());

        return $group;
    }

    public function modifyGroup($group, $params){
        $group = $this->setGroupModify($group, $params);
        $this->em->persist($group);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $group);
    }

    public function deleteGroupsByCategory($catId){
        $groups = $this->getGroupsByCategory($catId);
        foreach ($groups as $group) {
            $this->deleteGroup($group);
        }
    }

    public function deleteGroup($group){
        $this->em->remove($group);
        $this->em->flush();
    }
}
