<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Observation;

class InscriptionController extends FOSRestController
{
    var $util;

    function __construct(){ 
        $this->util = new Util();
    }

	public function allInscriptionAction(){
        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findAll();
        $dataToSend = json_encode(array('inscription' => $inscriptions));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getInscriptionAction($id){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->find($id);

        if (!$inscription instanceof Inscription) {
            return $this->util->setResponse(404, Literals::InscriptionNotFound);
        }
        $dataToSend = json_encode(array('inscription' => $inscription));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getInscriptionByTournamentAction($idTournament){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findByTournament($idTournament);

        $repositoryTournament = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Tournament');
        $tournament = $repositoryTournament->find($idTournament);

        if($tournament==null){
            return $this->util->setResponse(400, Literals::TournamentNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getInscriptionByCategoryAction($idCategory){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findByCategory($idCategory);

        $repositoryCategory = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Category');
        $category = $repositoryCategory->find($idCategory);

        if($category==null){
            return $this->util->setResponse(400, Literals::CategoryNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getInscriptionByPairAction($idPair){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findByPair($idPair);

        $repositoryPair = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Pair');
        $pair = $repositoryPair->find($idPair);

        if($pair==null){
            return $this->util->setResponse(400, Literals::PairNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getInscriptionByGroupAction($idGroup){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Inscription');
        $inscriptions = $repository->findByGroup($idGroup);

        $repositoryGroup = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:GroupCategory');
        $group = $repositoryGroup->find($idGroup);

        if($group==null){
            return $this->util->setResponse(400, Literals::GroupNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    private function checkInscription($params){
        $isFail = false;
        $message = "";
        if(empty($params['pair'])){
            $isFail = true;
            $message .= Literals::PairEmpty;
        }
        if(empty($params['category'])){
            $isFail = true;
            $message .= Literals::CategoryEmpty;
        }
        if(empty($params['tournament'])){
            $isFail = true;
            $message .= Literals::TournamentEmpty;
        }
        if($isFail){
            return $message;
        }
        else{
            return null;
        }
    }

    private function checkObservation($params){
        $isFail = false;
        $message = "";
        if(empty($params['startDate'])){
            $isFail = true;
            $message .= Literals::StartDateEmpty;
        }
        if(empty($params['endDate'])){
            $isFail = true;
            $message .= Literals::EndDateEmpty;
        }
        if(empty($params['available'])){
            $isFail = true;
            $message .= Literals::AvailableEmpty;
        }
        if($isFail){
            return $message;
        }
        else{
            return null;
        }
    }

    private function getCountInscriptionsInTournament($tournamentId){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT i FROM GeneralBundle:Inscription i WHERE i.tournament = :tournament'
        )->setParameters(array(
            'tournament' => $tournamentId
        ));
        $inscriptionsNumber = $query->getResult();
        return count($inscriptionsNumber);
    }

    private function getCountInscriptionsInCategory($categoryId){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT i FROM GeneralBundle:Inscription i WHERE i.category = :category'
        )->setParameters(array(
            'category' => $categoryId
        ));
        $inscriptionsNumber = $query->getResult();
        return count($inscriptionsNumber);
    }

    public function postInscriptionAction(){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Inscription');

        $params = array();
        $content = $this->get("request")->getContent();

        if (!empty($content)){
            $params = json_decode($content, true);
            $checked = $this->checkInscription($params);
            if($checked != null){
                return $this->util->setResponse(400, $checked);
            }

            $repositoryCategory = $em->getRepository('GeneralBundle:Category');
            $category = $repositoryCategory->find($params['category']);
            $repositoryTournament = $em->getRepository('GeneralBundle:Tournament');
            $tournament = $repositoryTournament->find($params['tournament']);
            
            if($category == null){
                return $this->util->setResponse(400, Literals::CategoryNotFound);
            }
            else if($tournament == null){
                return $this->util->setResponse(400, Literals::TournamentNotFound);
            }
            else if($category->getTournament()->getId() != $params['tournament']){
                return $this->util->setResponse(400, Literals::CategoryIncorrect);
            }
            else{
                $message = null;
                $inscriptions = 0;
                foreach ($params['pair'] as $objIns) {
                    $pairId = $objIns['pairId'];
                    $query = $em->createQuery(
                        'SELECT i FROM GeneralBundle:Inscription i WHERE i.category = :category AND
                        i.tournament = :tournament AND i.pair = :pair'
                    )->setParameters(array(
                        'category' => $category->getId(),
                        'tournament'  => $tournament->getId(),
                        'pair' => $pairId,
                    ));
                    $inscription = $query->getResult();
                    if($inscription == null){
                        $repositoryPair = $em->getRepository('GeneralBundle:Pair');
                        $pair = $repositoryPair->find($pairId);
                        if($pair == null){
                            $message .= $pairId . ' ' . Literals::PairNotFound;
                        }
                        else if($tournament->getRegisteredLimit() != null && 
        $tournament->getRegisteredLimit() == $this->getCountInscriptionsInTournament($tournament->getId())){
                            $message .= $pairId . ' ' . Literals::TournamentInscriptionLimit;
                        }
                        else if($category->getRegisteredLimitMax() != null && 
        $category->getRegisteredLimitMax() == $this->getCountInscriptionsInCategory($category->getId())){
                            $message .= $pairId . ' ' . Literals::CategoryInscriptionLimitMax;
                        }
                        else{
                            $newInscription = new Inscription();
                            $newInscription->setPair($pair);
                            $newInscription->setCategory($category);
                            $newInscription->setTournament($tournament);
                            $em->persist($newInscription);
                            $em->flush();
                            $inscriptions += 1;
                            if(!empty($objIns['observations'])){
                                foreach ($objIns['observations'] as $objObs) {
                                    $checked = $this->checkObservation($objObs);
                                    if($checked != null){
                                        $message .= $pairId . ' ' . Literals::ObservationIncorrect . ' ' .
                                        $checked;
                                    }
                                    else{
                                        $newObservation = new Observation();
                                        $newObservation->setStartDate(new \DateTime($objObs['startDate']));
                                        $newObservation->setEndDate(new \DateTime($objObs['endDate']));
                                        $newObservation->setAvailable($objObs['available'] == 'si' ? true : false);
                                        $newObservation->setInscription($newInscription);
                                        $em->persist($newObservation);
                                        $em->flush();
                                    }
                                }
                            }
                        }
                    }
                    else{
                        $message .= $pairId . ' ' . Literals::PairDuplicate;
                    }
                }
                $message .= $inscriptions . ' ' . Literals::Inscriptions;
                return $this->util->setResponse(200, $message);
            }

        } else {
            return $this->util->setResponse(400, Literals::EmptyContent);
        }
    }
}
