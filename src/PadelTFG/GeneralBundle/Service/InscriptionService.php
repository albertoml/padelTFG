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

class InscriptionService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
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
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findByTournament($idTournament);
        return $inscriptions;
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

    public function getInscriptionsByGroup($idGroup){
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findByGroup($idGroup);
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

        if($category->getTournament()->getId() != $tournament->getId()){
            return Literals::CategoryIncorrect;
        }
        return null;
    }

    private function setInscriptionSave($inscription, $pair, $category, $tournament){
        $inscription->setPair($pair);
        $inscription->setCategory($category);
        $inscription->setTournament($tournament);
        $inscription->setStatus($this->statusService->getStatus($this->em, 'inscription', 'tournament not started'));
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

        if($inscription == null){
            $pair = $this->getPairFromId($pairId);
            if(!$pair instanceof Pair){
                $checked['message'] .= $pairId . ' ' . Literals::PairNotFound;
            }
            else if($tournament->getRegisteredLimit() != null && 
$tournament->getRegisteredLimit() == $this->getCountInscriptionsInTournament($tournament->getId())){
                $checked['message'] .= $pairId . ' ' . Literals::TournamentInscriptionLimit;
            }
            else if($category->getRegisteredLimitMax() != null && 
$category->getRegisteredLimitMax() == $this->getCountInscriptionsInCategory($category->getId())){
                $checked['message'] .= $pairId . ' ' . Literals::CategoryInscriptionLimitMax;
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

    public function saveInscriptions($params, $controller){

        $category = $this->getCategoryFromParams($params);
        $tournament = $this->getTournamentFromParams($params);
        $failCheckAttributes = $this->checkInscriptionAttributes($category, $tournament);
        if($failCheckAttributes == null){

            $checked = null;
            $checked['message'] = "";
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
                                $checked['message'] .= $resumeToObservationsInsert['message'];
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
                }
                else{
                    $checked['message'] .= Literals::PairNotFound;
                }
            }
            $this->em->flush();
            
            if($inscriptions == 0){
                return array('result' => 'fail', 'message' => $checked['message']);
            }
            else{
                $checked['message'] .= $inscriptions . ' ' . Literals::Inscriptions;
                return array('result' => 'ok', 'message' => $checked['message']);
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
