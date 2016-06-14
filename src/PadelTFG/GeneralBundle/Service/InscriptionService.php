<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;
use PadelTFG\GeneralBundle\Service\PairService as PairService;
use PadelTFG\GeneralBundle\Service\ObservationService as ObservationService;
use PadelTFG\GeneralBundle\Service\GroupService as GroupService;


class InscriptionService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allInscriptions(){
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findAll();
        return $inscriptions;
    }

    public function getInscription($id){
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->find($id);
        return $inscription;
    }

    public function getInscriptionsByTournament($idTournament){
        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);
        $categories = $categoryService->getCategoryByTournament($idTournament);
        $returnInscriptions = array();
        foreach ($categories as $category) {
            $inscriptions = $this->getInscriptionsByCategory($category->getId());
            $returnInscriptions[$category->getName() . ';' . $category->getId()] = $inscriptions;
        }
        return $returnInscriptions;
    }

    public function getInscriptionsByTournamentInArray($idTournament){
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findByTournament($idTournament);
        return $inscriptions;
    }

    public function getCountInscriptionsByTournamentByCategory($idTournament){
        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);
        $categories = $categoryService->getCategoryByTournament($idTournament);
        $returnInscriptions = array();
        foreach ($categories as $category) {
            $inscriptions = $this->getCountInscriptionsInCategory($category->getId());
            $returnInscriptions[$category->getName()] = $inscriptions;
        }
        $returnInscriptionsTotal['totalTournament'] = $this->getCountInscriptionsInTournament($idTournament);
        $returnInscriptionsTotal['category'] = $returnInscriptions;
        return $returnInscriptionsTotal;
    }

    public function getInscriptionsByCategory($idCategory){
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findByCategory($idCategory);
        return $inscriptions;
    }

    public function getInscriptionsByPair($idPair){
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findByPair($idPair);
        return $inscriptions;
    }

    public function getInscriptionsByPairAndCategory($idPair, $idCategory){
        $query = $this->em->createQuery(
            'SELECT i FROM GeneralBundle:Inscription i WHERE i.category = :category AND i.pair = :pair'
        )->setParameters(array(
            'category' => $idCategory,
            'pair' => $idPair
        ));
        $inscriptions = $query->getSingleResult();
        return $inscriptions;
    }

    public function getInscriptionsByUser($idUser){
        $pairService = new PairService();
        $pairService->setManager($this->em);
        $pairs = $pairService->getPairByUser($idUser);
        $inscriptions = array();

        foreach ($pairs as $pair) {
            foreach($this->getInscriptionsByPair($pair->getId()) as $ins){
                $inscriptions[] = $ins;
            }
        }
        return $inscriptions;
    }

    public function getInscriptionsByGroupForATournament($idTournament, $orderBy){
        $groupService = new GroupService();
        $groupService->setManager($this->em);
        $groupsinTournament = $groupService->getGroupsByTournament($idTournament);
        $returnInscriptions = array();
        foreach ($groupsinTournament as $indexCategory=>$groupsInCategory) {
            $inscriptions = $this->getInscriptionsByGroupForACategory($groupsInCategory, $orderBy);
            $returnInscriptions[$indexCategory] = $inscriptions;
        }
        return $returnInscriptions;
    }

    public function getInscriptionsByGroupForACategory($groupsInCategory, $orderBy){
        $groupService = new GroupService();
        $groupService->setManager($this->em);
        $returnInscriptions = array();
        foreach ($groupsInCategory as $group) {
            $inscriptions = $this->getInscriptionsByGroup($group->getId(), $orderBy);
            $returnInscriptions[$group->getName() . ';' . $group->getId()] = $inscriptions;
        }
        return $returnInscriptions;
    }

    public function getInscriptionsByGroup($idGroup, $orderBy){
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        if(!is_null($orderBy)){
            $inscriptions = $repository->findBy(
             array('group'=> $idGroup), 
             array($orderBy => 'ASC')
            );
        }
        else{
            $inscriptions = $repository->findByGroup($idGroup);
        }
        return $inscriptions;
    }

    public function getCountInscriptionsInTournament($tournamentId){
        $query = $this->em->createQuery(
            'SELECT i FROM GeneralBundle:Inscription i WHERE i.tournament = :tournament'
        )->setParameters(array(
            'tournament' => $tournamentId
        ));
        $inscriptionsNumber = $query->getResult();
        return count($inscriptionsNumber);
    }

    public function getCountInscriptionsInCategory($categoryId){
        $query = $this->em->createQuery(
            'SELECT i FROM GeneralBundle:Inscription i WHERE i.category = :category'
        )->setParameters(array(
            'category' => $categoryId
        ));
        $inscriptionsNumber = $query->getResult();
        return count($inscriptionsNumber);
    }

    private function getCategoryFromParams($params){
        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);
        $category = $categoryService->getCategory(isset($params['category']) ? $params['category'] : '');
        return $category;
    }

    private function getTournamentFromParams($params){
        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->em);
        $tournament = $tournamentService->getTournament(isset($params['tournament']) ? $params['tournament'] : '');
        return $tournament;
    }

    private function getPairFromId($idPair){
        $pairService = new PairService();
        $pairService->setManager($this->em);
        $pair = $pairService->getPair(isset($idPair) ? $idPair : '');
        return $pair;
    }

    private function checkInscriptionAttributes($category, $tournament){

        if(!$category instanceof Category){
            return Literals::CategoryNotFound;
        }
        if(!$tournament instanceof Tournament){
            return Literals::TournamentNotFound;
        }
        if($category->getTournament() != null && $category->getTournament()->getId() != $tournament->getId()){
            return Literals::CategoryIncorrect;
        }
        return null;
    }

    private function checkUsersInCategory($pair, $category){
        $inscriptions = $this->getInscriptionsByCategory($category->getId());
        $user1 = $pair->getUser1()->getId();
        $user2 = $pair->getUser2()->getId();
        foreach ($inscriptions as $ins) {
            if($ins->getPair()->getUser1()->getId() == $user1 || $ins->getPair()->getUser2()->getId() == $user1){
                return true;
            }
            else if($ins->getPair()->getUser1()->getId() == $user2 || $ins->getPair()->getUser2()->getId() == $user2){
                return true;
            }
        }
        return false;
    }

    private function setInscriptionSave($inscription, $pair, $category, $tournament){
        $inscription->setPair($pair);
        $inscription->setCategory($category);
        $inscription->setTournament($tournament);
        $inscription->setStatus($this->statusService->getStatus('inscription', Literals::Tournament_Not_StartedInscriptionStatus));
        return $inscription;
    }

    private function checkPair($category, $tournament, $pairId, $checked){
        $query = $this->em->createQuery(
            'SELECT i FROM GeneralBundle:Inscription i WHERE i.category = :category AND
            i.tournament = :tournament AND i.pair = :pair'
        )->setParameters(array(
            'category' => $category->getId(),
            'tournament'  => $tournament->getId(),
            'pair' => $pairId,
        ));
        $inscription = $query->getResult();

        $checked['message'] = "";
        $checked['result'] = false;

        if($inscription == null){
            $pair = $this->getPairFromId($pairId);
            if(!$pair instanceof Pair){
                $checked['message'] .= $pairId . ' ' . Literals::PairNotFound;
            }
            else if($this->checkUsersInCategory($pair, $category)){
                $checked['message'] .= $pairId . ' ' . Literals::UserDuplicate;
            }
            else if($tournament->getRegisteredLimit() != null && 
$tournament->getRegisteredLimit() == $this->getCountInscriptionsInTournament($tournament->getId())){
                $checked['message'] .= $tournament->getId() . ' ' . Literals::TournamentInscriptionLimit;
            }
            else if($category->getRegisteredLimitMax() != null && 
$category->getRegisteredLimitMax() == $this->getCountInscriptionsInCategory($category->getId())){
                $checked['message'] .= $pairId . ' ' . Literals::CategoryInscriptionLimitMax;
            }
            else if($category->getGender() != Literals::GenderMale && $category->getGender()!=$pair->getGender()){
                $checked['message'] .= $pairId . ' ' . Literals::IncorrectGender;
            }
            else{
                $checked['result'] = true;
            }
        }
        else{
            $checked['message'] .= $pairId . ' ' . Literals::PairDuplicate;
        }

        return $checked;
    }

    public function orderByClassified($inscriptions){
        $groupService = new GroupService();
        $groupService->setManager($this->em);
        if(!is_null($inscriptions) && count($inscriptions) > 0){
            $numGroups = $groupService->getNumGroupsByCategory($inscriptions[0]->getCategory()->getId());
            $inscriptionsOrder = array();
            $pivot = 0;
            $pivotGroup = 0;

            while(count($inscriptionsOrder) != count($inscriptions)){
                foreach ($inscriptions as $inscription) {
                    if($inscription->getClassifiedPositionInGroup() == $pivotGroup && $inscription->getClassifiedPositionByGroups() == $pivot){
                        $inscriptionsOrder[] = $inscription;
                    }
                }
                $pivot = $pivot + 1;
                if($pivot >= $numGroups){
                    $pivot = 0;
                    $pivotGroup = $pivotGroup + 1;
                }
            }
            return $inscriptionsOrder;
        }
        else{
            return $inscriptions;
        }
    }

    public function saveInscriptions($params, $controller){

        $category = $this->getCategoryFromParams($params);
        $tournament = $this->getTournamentFromParams($params);
        $failCheckAttributes = $this->checkInscriptionAttributes($category, $tournament);
        if($failCheckAttributes == null){

            $checked = null;
            $checked['message'] = "";
            $messageToShow = "";
            $inscriptions = 0;
            foreach ($params['pair'] as $objIns) {
                if(!empty($objIns['pairId'])){
                    $pairId = $objIns['pairId'];
                    $checked['result'] = false;
                    $checked = $this->checkPair($category, $tournament, $pairId, $checked);
                    if($checked['result']){
                        $newInscription = new Inscription();
                        $pair = $this->getPairFromId($pairId);
                        $newInscription = $this->setInscriptionSave($newInscription, $pair, $category, $tournament);
                        $this->em->persist($newInscription);
                        $this->em->flush();
                        $inscriptions += 1;
                        if(!empty($objIns['observations'])){
                            $observationService = new ObservationService();
                            $observationService->setManager($this->em);
                            $resumeToObservationsInsert = $observationService->saveObservations($objIns['observations'], $newInscription, $controller);
                            if($resumeToObservationsInsert['result'] == 'fail'){
                                $messageToShow .= $resumeToObservationsInsert['message'];
                            }
                            else{
                                $newInscription->setHasObservations(true);
                                $this->em->persist($newInscription);
                            }
                        }
                        else{
                            $newInscription->setHasObservations(false);
                            $this->em->persist($newInscription);
                        }
                    }
                    else{
                        $messageToShow .= $checked['message'];
                    }
                }
                else{
                    $messageToShow .= Literals::PairNotFound;
                }
            }
            $this->em->flush();
            
            if($inscriptions == 0){
                return array('result' => 'fail', 'message' => $messageToShow);
            }
            else{
                $messageToShow .= $inscriptions . ' ' . Literals::Inscriptions;
                return array('result' => 'ok', 'message' => $messageToShow);
            }     
        }
        else {
            return array('result' => 'fail', 'message' => $failCheckAttributes);
        }
    }

    public function deleteInscription($inscription){
        $this->em->remove($inscription);
        $this->em->flush();
    }
    
}
