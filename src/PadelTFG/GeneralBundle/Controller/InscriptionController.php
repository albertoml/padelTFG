<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\InscriptionService as InscriptionService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;
use PadelTFG\GeneralBundle\Service\PairService as PairService;
use PadelTFG\GeneralBundle\Service\UserService as UserService;
use PadelTFG\GeneralBundle\Service\GroupService as GroupService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\GroupCategory;

class InscriptionController extends FOSRestController
{
    var $util;
    var $inscriptionService;

    function __construct(){ 
        $this->util = new Util();
        $this->inscriptionService = new InscriptionService();
    }

	public function allInscriptionAction(){
        $this->inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscriptions = $this->inscriptionService->allInscriptions();
        $dataToSend = json_encode(array('inscription' => $inscriptions));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getInscriptionAction($id){

        $this->inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscription = $this->inscriptionService->getInscription($id);

        if (!$inscription instanceof Inscription) {
            return $this->util->setResponse(404, Literals::InscriptionNotFound);
        }
        $dataToSend = json_encode(array('inscription' => $inscription));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getInscriptionByTournamentAction($idTournament){

        $this->inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscriptions = $this->inscriptionService->getInscriptionsByTournament($idTournament);

        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $tournamentService->getTournament($idTournament);

        if(!$tournament instanceof Tournament){
            return $this->util->setResponse(400, Literals::TournamentNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getInscriptionByCategoryAction($idCategory){

        $this->inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscriptions = $this->inscriptionService->getInscriptionsByCategory($idCategory);

        $categoryService = new CategoryService();
        $categoryService->setManager($this->getDoctrine()->getManager());
        $category = $categoryService->getCategory($idCategory);

        if(!$category instanceof Category){
            return $this->util->setResponse(400, Literals::CategoryNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getInscriptionByPairAction($idPair){

        $this->inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscriptions = $this->inscriptionService->getInscriptionsByPair($idPair);

        $pairService = new PairService();
        $pairService->setManager($this->getDoctrine()->getManager());
        $pair = $pairService->getPair($idPair);

        if(!$pair instanceof Pair){
            return $this->util->setResponse(400, Literals::PairNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getInscriptionByUserAction($idUser){

        $this->inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscriptions = $this->inscriptionService->getInscriptionsByUser($idUser);

        $userService = new UserService();
        $userService->setManager($this->getDoctrine()->getManager());
        $user = $userService->getUser($idUser);

        if(!$user instanceof User){
            return $this->util->setResponse(400, Literals::UserNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getInscriptionByGroupAction($idGroup){

        $this->inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscriptions = $this->inscriptionService->getInscriptionsByGroup($idGroup, null);

        $groupService = new GroupService();
        $groupService->setManager($this->getDoctrine()->getManager());
        $group = $groupService->getGroup($idGroup);

        if(!$group instanceof GroupCategory){
            return $this->util->setResponse(400, Literals::GroupNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getInscriptionByGroupForATournamentAction($idTournament){

        $this->inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscriptions = $this->inscriptionService->getInscriptionsByGroupForATournament($idTournament, null);

        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $tournamentService->getTournament($idTournament);

        if(!$tournament instanceof Tournament){
            return $this->util->setResponse(400, Literals::TournamentNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function postInscriptionAction(){

        $this->inscriptionService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $inscription = $this->inscriptionService->saveInscriptions($params, $this);
        if($inscription['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $inscription['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('inscription' => $inscription['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

    public function deleteInscriptionAction($id){

        $this->inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscription = $this->inscriptionService->getInscription($id);

        if ($inscription instanceof Inscription) {

            $inscription = $this->inscriptionService->deleteInscription($inscription);

            return $this->util->setResponse(200, Literals::InscriptionDeleted);
        } else {
            return $this->util->setResponse(404, Literals::InscriptionNotFound);
        }
    }

    public function countInscriptionAction($idTournament){
        $this->inscriptionService->setManager($this->getDoctrine()->getManager());
        $inscriptions = $this->inscriptionService->getCountInscriptionsByTournamentByCategory($idTournament);

        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $tournamentService->getTournament($idTournament);

        if(!$tournament instanceof Tournament){
            return $this->util->setResponse(400, Literals::TournamentNotFound);
        }
        else{
            $dataToSend = json_encode(array('inscription' => $inscriptions));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }
}
