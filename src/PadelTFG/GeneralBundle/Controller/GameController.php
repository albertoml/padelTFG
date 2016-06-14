<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Service\GameService as GameService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;
use PadelTFG\GeneralBundle\Service\GroupService as GroupService;
use PadelTFG\GeneralBundle\Service\UserService as UserService;

use PadelTFG\GeneralBundle\Entity\Game;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\GroupCategory;

class GameController extends FOSRestController
{

    var $util;
    var $gameService;

    function __construct(){ 
        $this->util = new Util();
        $this->gameService = new GameService();
    }

	public function allGameAction(){

        $this->gameService->setManager($this->getDoctrine()->getManager());
        $games = $this->gameService->allGames();
        $dataToSend = json_encode(array('game' => $games));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getGameAction($id){

        $this->gameService->setManager($this->getDoctrine()->getManager());
        $game = $this->gameService->getGame($id);

        if (!$game instanceof Game) {
            return $this->util->setResponse(404, Literals::GameNotFound);
        }
        $dataToSend = json_encode(array('game' => $game));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getGameByTournamentAction($idTournament, $isDraw){

        $this->gameService->setManager($this->getDoctrine()->getManager());

        if($isDraw == "true"){
            $games = $this->gameService->getGamesInDrawByTournament($idTournament);
        }
        else{
            $games = $this->gameService->getGamesByTournament($idTournament);
        }

        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $tournamentService->getTournament($idTournament);

        if(!$tournament instanceof Tournament){
            return $this->util->setResponse(400, Literals::TournamentNotFound);
        }
        else{
            $dataToSend = json_encode(array('game' => $games));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getGameByCategoryAction($idCategory){

        $this->gameService->setManager($this->getDoctrine()->getManager());
        $games = $this->gameService->getGamesByCategory($idCategory);

        $categoryService = new CategoryService();
        $categoryService->setManager($this->getDoctrine()->getManager());
        $category = $categoryService->getCategory($idCategory);

        if(!$category instanceof Category){
            return $this->util->setResponse(400, Literals::CategoryNotFound);
        }
        else{
            $dataToSend = json_encode(array('game' => $games));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getGameByGroupAction($idGroup){

        $this->gameService->setManager($this->getDoctrine()->getManager());
        $games = $this->gameService->getGamesByGroup($idGroup);

        $groupService = new GroupService();
        $groupService->setManager($this->getDoctrine()->getManager());
        $group = $groupService->getGroup($idGroup);

        if(!$group instanceof GroupCategory){
            return $this->util->setResponse(400, Literals::GroupNotFound);
        }
        else{
            $dataToSend = json_encode(array('game' => $games));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getGameByUserAction($idUser){

        $this->gameService->setManager($this->getDoctrine()->getManager());
        $games = $this->gameService->getGamesByUser($idUser);

        $userService = new UserService();
        $userService->setManager($this->getDoctrine()->getManager());
        $user = $userService->getUser($idUser);

        if(!$user instanceof User){
            return $this->util->setResponse(400, Literals::UserNotFound);
        }
        else{
            $dataToSend = json_encode(array('game' => $games));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function postGameAction(){

        $this->gameService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        
        $game = $this->gameService->saveGame($params);
        if($game['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $game['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('game' => $game['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

    public function postGamesTournamentAction(){

        $this->gameService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $game = $this->gameService->saveGamesTournament($params);
        if($game['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $game['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('game' => $game['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

    public function putGameAction($id){

        $this->gameService->setManager($this->getDoctrine()->getManager());
        $game = $this->gameService->getGame($id);

        if ($game instanceof Game) {

            $params = array();
            $content = $this->get("request")->getContent();
            $params = json_decode($content, true);
            $game = $this->gameService->modifyGame($game, $params);
            if($game['result'] == 'fail'){
                $dataToSend = json_encode(array('error' => $game['message']));
                return $this->util->setResponse(400, $dataToSend);
            }
            $dataToSend = json_encode(array('game' => $game['message']));
            return $this->util->setJsonResponse(200, $dataToSend);
            
        } else {
            return $this->util->setResponse(404, Literals::GameNotFound);
        }

    }
}
