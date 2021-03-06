<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;
use PadelTFG\GeneralBundle\Service\InscriptionService as InscriptionService;
use PadelTFG\GeneralBundle\Service\GroupService as GroupService;
use PadelTFG\GeneralBundle\Service\GameService as GameService;
class TournamentService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allTournaments(){
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findAll();
        return $tournament;
    }

    public function getTournament($id){
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->find($id);
        return $tournament;
    }

    public function getTournamentsByAdmin($userId){
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findByAdmin($userId);
        return $tournament;
    }

    private function setTournamentSave($tournament, $params, $user){
        $tournament->setAdmin($user);
        $tournament->setName($params['name']);
        $tournament->setStartInscriptionDate(!empty($params['startInscriptionDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startInscriptionDate']) : null);
        $tournament->setEndInscriptionDate(!empty($params['endInscriptionDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endInscriptionDate']) : null);
        $tournament->setStartGroupDate(!empty($params['startGroupDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startGroupDate']) : null);
        $tournament->setEndGroupDate(!empty($params['endGroupDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endGroupDate']) : null);
        $tournament->setStartFinalDate(!empty($params['startFinalDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startFinalDate']) : null);
        $tournament->setEndFinalDate(!empty($params['endFinalDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endFinalDate']) : null);
        $tournament->setRegisteredLimit(!empty($params['registeredLimit']) ? $params['registeredLimit'] : 0);
        $tournament->setImage(!empty($params['image']) ? $params['image'] : '');
        $tournament->setStatus($this->statusService->getStatus('tournament', 'Created'));

        return $tournament;
    }

    public function saveTournament($params, $user, $controller){
        $tournament = new Tournament();
        $tournament = $this->setTournamentSave($tournament, $params, $user);
        $validator = $controller->get('validator');
        $errors = $validator->validate($tournament);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($tournament);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $tournament);
    }

    private function setTournamentModify($tournament, $params){

        $tournament->setName(isset($params['name']) ? $params['name'] : $tournament->getName());
        $tournament->setStartInscriptionDate(!empty($params['startInscriptionDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startInscriptionDate']) : $tournament->getStartInscriptionDate());
        $tournament->setEndInscriptionDate(!empty($params['endInscriptionDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endInscriptionDate']) : $tournament->getEndInscriptionDate());
        $tournament->setStartGroupDate(!empty($params['startGroupDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startGroupDate']) : $tournament->getStartGroupDate());
        $tournament->setEndGroupDate(!empty($params['endGroupDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endGroupDate']) : $tournament->getEndGroupDate());
        $tournament->setStartFinalDate(!empty($params['startFinalDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startFinalDate']) : $tournament->getStartFinalDate());
        $tournament->setEndFinalDate(!empty($params['endFinalDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endFinalDate']) : $tournament->getEndFinalDate());
        $tournament->setRegisteredLimit(isset($params['registeredLimit']) ? $params['registeredLimit'] : $tournament->getRegisteredLimit());
        $tournament->setImage(isset($params['image']) ? $params['image'] : $tournament->getImage());
        $tournament->setStatus(isset($params['status']) ? $this->statusService->getStatus('tournament', $params['status']) : $tournament->getStatus());

        return $tournament;
    }

    public function modifyTournament($tournament, $params, $controller){
        $tournament = $this->setTournamentModify($tournament, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($tournament);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($tournament);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $tournament);
    }

    public function deleteTournament($tournament){
        $this->em->remove($tournament);
        $this->em->flush();
    }

    public function calculateNumGroups($inscriptions, $pairsByGroup, $category, $tournament){
        $groupService = new GroupService();
        $groupService->setManager($this->em);

        $groups = array();
        if($inscriptions != 0){
            $numGroupsAux = $inscriptions / $pairsByGroup;
            $numGroups = (int) $numGroupsAux;
            if($numGroups == 0){
                $groupContent = array('name' => 'Group ' . 1,
                                    'category' => $category,
                                    'tournament' => $tournament,
                                    'numPairs' => $inscriptions);
                $groupResult = $groupService->saveGroup($groupContent);
                if($groupResult['result'] == 'ok'){
                    $groups[] = $groupResult['message'];
                }
            }
            else{
                $i = 0;
                for ($i=0; $i < $numGroups; $i++) { 
                    $groupContent = array('name' => 'Group ' . ($i + 1),
                                    'category' => $category,
                                    'tournament' => $tournament,
                                    'numPairs' => $pairsByGroup);
                    $groupResult = $groupService->saveGroup($groupContent);
                    if($groupResult['result'] == 'ok'){
                        $groups[] = $groupResult['message'];
                    }
                }

                $restDivision = $inscriptions % $pairsByGroup;
                if($restDivision > 2){
                    $numGroups = $numGroups + 1;
                    $groupContent = array('name' => 'Group ' . ($i + 2),
                                    'category' => $category,
                                    'tournament' => $tournament,
                                    'numPairs' => $restDivision);
                    $groupResult = $groupService->saveGroup($groupContent);
                    if($groupResult['result'] == 'ok'){
                        $groups[] = $groupResult['message'];
                    }
                }
                else{
                    // arreglar este bucle porque puede no acabar nunca
                    // si sobran dos parejas y solo hay un grupo antes para insertarlas
                    while($restDivision != 0){
                        $groupModify = $groups[count($groups) - $restDivision];
                        $groupModify->setNumPairs($groupModify->getNumPairs() + 1);
                        $this->em->persist($groupModify);
                        $this->em->flush();
                        $restDivision = $restDivision - 1;
                    }
                }
            }
        }
        return $groups;
    }

    public function startTournament($tournament){
        $tournament->setStatus($this->statusService->getStatus('tournament', Literals::In_Inscription_DateTournamentStatus));
        $this->em->persist($tournament);
        $this->em->flush();
        $result = array('result' => 'ok', 'message' => $tournament);
        return $result;
    }

    public function closeInscriptionTournament($tournament, $params){
        $categoryService = new CategoryService();
        $inscriptionService = new InscriptionService();
        $groupService = new GroupService();
        $categoryService->setManager($this->em);
        $inscriptionService->setManager($this->em);
        $groupService->setManager($this->em);

        $saveChangesCorrect = true;

        $categories = $categoryService->getCategoryByTournament($tournament->getId());

        foreach ($categories as $category) {
            $inscriptions = $inscriptionService->getInscriptionsByCategory($category->getId());
            $countInscriptions = 0;
            if(intval($params[$category->getName()]) > 0){
                $groups = $this->calculateNumGroups(count($inscriptions), intval($params[$category->getName()]), $category, $tournament);
                foreach ($groups as $group) {
                    for($i = 0; $i < $group->getNumPairs(); $i++){
                        $inscriptions[$countInscriptions]->setGroup($group);
                        $this->em->persist($inscriptions[$i]);
                        $countInscriptions = $countInscriptions + 1;
                    }   
                }
            }
            else{
                $saveChangesCorrect = false;
            }
        }
        if($saveChangesCorrect){
            $tournament->setStatus($this->statusService->getStatus('tournament', Literals::In_Group_DateTournamentStatus));
            $this->em->persist($tournament);
            $this->em->flush();
            $result = array('result' => 'ok', 'message' => $inscriptionService->getInscriptionsByGroupForATournament($tournament->getId(), null));
            return $result;
        }
        else{
            return $result = array('result' => 'fail', 'message' => Literals::TournamentChangesNotSaved);
        }
    }

    public function closeGroupTournament($tournament){
        $categoryService = new CategoryService();
        $inscriptionService = new InscriptionService();
        $groupService = new GroupService();
        $categoryService->setManager($this->em);
        $inscriptionService->setManager($this->em);
        $groupService->setManager($this->em);

        $categories = $categoryService->getCategoryByTournament($tournament->getId());
        foreach ($categories as $category) {
            $groupsRank = array();
            $numPairsByGroup = array();
            $allInscriptions = array();
            $groups = $groupService->getGroupsByCategory($category->getId());
            if(!is_null($groups)){
                foreach ($groups as $group) {
                    $inscriptions = $inscriptionService->getInscriptionsByGroup($group->getId(), null);
                    $allInscriptions = array_merge($allInscriptions, $inscriptions);
                    $groupRank = $groupService->calculateClassficationByGroup($inscriptions, $group);  
                    if(!is_null($groupRank)){
                        $numPairsByGroup[] = count($groupRank);
                        $groupsRank[] = $groupRank;
                    }
                }
            }
            if(!empty($groupsRank)){
                rsort($numPairsByGroup);
                for($i = 0; $i < $numPairsByGroup[0]; $i++){
                    $rankByGroupPositions = array();
                    foreach ($groupsRank as $groupRank) {
                        if(count($groupRank) > $i && !is_null($groupRank[$i])){
                            $rankByGroupPositions[] = $groupRank[$i];
                        }       
                    }
                    $rankByGroupPositions = $groupService->sortByScore($rankByGroupPositions);

                    foreach ($allInscriptions as $inscription) {
                        $position = 0;
                        foreach ($rankByGroupPositions as $rank) {
                            if($inscription->getPair()->getId() == $rank['pair']){
                                $inscription->setClassifiedPositionByGroups($position);
                                $this->em->persist($inscription);
                            }
                            $position = $position + 1;
                        }
                    }
                }
            } 
        }
        $this->em->flush();
        $result = array('result' => 'ok', 'message' => $inscriptionService->getInscriptionsByGroupForATournament($tournament->getId(), 'classifiedPositionInGroup'));
        return $result;
    }

    public function createDrawTournament($tournament, $params){
        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);

        $inscriptionService = new InscriptionService();
        $inscriptionService->setManager($this->em);

        $gamesForDraw = array();

        $saveChangesCorrect = true;

        if(!empty($params)){
            foreach ($params as $categoryId => $category) {
                $categoryEntity = $categoryService->getCategory($categoryId);
                if(!is_null($categoryEntity)){
                    $inscriptionsInCategory = array();
                    foreach ($category as $pairId) {
                        $inscriptionsInCategory[] = $inscriptionService->getInscriptionsByPairAndCategory($pairId, $categoryId);
                    }
                    $gamesForDraw[$categoryEntity->getName() . ';' . $categoryId] = $categoryService->createDrawForCategory($inscriptionsInCategory, $categoryId, $tournament->getId());
                }
                else{
                    $saveChangesCorrect = false;
                }
            }
        }
        else{
            $saveChangesCorrect = false;
        }

        if($saveChangesCorrect){
            $tournament->setStatus($this->statusService->getStatus('tournament', Literals::In_Finals_DateTournamentStatus));
            $this->em->persist($tournament);
            $this->em->flush();
            $result = array('result' => 'ok', 'message' => $gamesForDraw);
            return $result;
        }
        else{
            return $result = array('result' => 'fail', 'message' => Literals::TournamentChangesNotSaved);
        }
    }
}
