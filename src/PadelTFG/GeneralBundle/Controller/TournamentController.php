<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Service\UserService as UserService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;

class TournamentController extends FOSRestController
{
	var $util;
    var $tournamentService;

    function __construct(){ 
        $this->util = new Util();
        $this->tournamentService = new TournamentService();
    } 

	public function allTournamentAction(){
        $this->tournamentService->setManager($this->getDoctrine()->getManager());
        $tournaments = $this->tournamentService->allTournaments();
        $dataToSend = json_encode(array('tournament' => $tournaments));
        return $this->util->setJsonResponse(200, $dataToSend);
	}

	public function getTournamentAction($id){

        $this->tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $this->tournamentService->getTournament($id);

        if (!$tournament instanceof Tournament) {
            return $this->util->setResponse(404, Literals::TournamentNotFound);
        }
        $dataToSend = json_encode($tournament);
        return $this->util->setJsonResponse(200, $dataToSend);
	}

    public function postTournamentAction(){

        $this->tournamentService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();

        $params = json_decode($content, true);

        $userService = new UserService();
        $userService->setManager($this->getDoctrine()->getManager());
        
        if(!empty($params['admin'])){
            $user = $userService->getUserByEmail(trim($params['admin']));

            if(!$user instanceof User){
                return $this->util->setResponse(404, Literals::UserNotFound);
            }

            $tournament = $this->tournamentService->saveTournament($params, $user, $this);
            if($tournament['result'] == 'fail'){
                $dataToSend = json_encode(array('error' => $tournament['message']));
                return $this->util->setResponse(400, $dataToSend);
            }
            $dataToSend = json_encode(array('tournament' => $tournament['message']));
            return $this->util->setJsonResponse(201, $dataToSend);
        }
        else{
            return $this->util->setResponse(404, Literals::UserNotFound);
        }
    }

	public function putTournamentAction($id){

		$this->tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $this->tournamentService->getTournament($id);

        if ($tournament instanceof Tournament) {

            $params = array();
            $content = $this->get("request")->getContent();
            $params = json_decode($content, true);
            $tournament = $this->tournamentService->modifyTournament($tournament, $params, $this);
            if($tournament['result'] == 'fail'){
                $dataToSend = json_encode(array('error' => $tournament['message']));
                return $this->util->setResponse(400, $dataToSend);
            }
            $dataToSend = json_encode(array('tournament' => $tournament['message']));
            return $this->util->setJsonResponse(200, $dataToSend);
            
        } else {
            return $this->util->setResponse(404, Literals::TournamentNotFound);
        }

	}

	public function deleteTournamentAction($id){

        $this->tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $this->tournamentService->getTournament($id);

        if ($tournament instanceof Tournament) {
            $tournament = $this->tournamentService->deleteTournament($tournament);

            return $this->util->setResponse(200, Literals::TournamentDeleted);
        } else {
            return $this->util->setResponse(404, Literals::TournamentNotFound);
        }
	}

    public function closeInscriptionTournamentAction($id){
        $this->tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $this->tournamentService->getTournament($id);

        if ($tournament instanceof Tournament) {

            $params = array();
            $content = $this->get("request")->getContent();
            $params = json_decode($content, true);

            $tournament = $this->tournamentService->closeInscriptionTournament($tournament, $params);
            if($tournament['result'] == 'fail'){
                $dataToSend = json_encode(array('error' => $tournament['message']));
                return $this->util->setResponse(400, $dataToSend);
            }
            $dataToSend = json_encode(array('tournament' => $tournament['message']));
            return $this->util->setJsonResponse(200, $dataToSend);
            
        } else {
            return $this->util->setResponse(404, Literals::TournamentNotFound);
        }
    }
}
