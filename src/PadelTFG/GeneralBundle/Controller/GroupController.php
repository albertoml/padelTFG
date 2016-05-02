<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\GroupService as GroupService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Category;

class GroupController extends FOSRestController
{

    var $util;
    var $groupService;

    function __construct(){ 
        $this->util = new Util();
        $this->groupService = new GroupService();
    }

	public function allGroupAction(){

        $this->groupService->setManager($this->getDoctrine()->getManager());
        $groups = $this->groupService->allGroups();
        $dataToSend = json_encode(array('group' => $groups));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getGroupAction($id){

        $this->groupService->setManager($this->getDoctrine()->getManager());
        $group = $this->groupService->getGroup($id);

        if (!$group instanceof GroupCategory) {
            return $this->util->setResponse(404, Literals::GroupNotFound);
        }
        $dataToSend = json_encode(array('group' => $group));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getGroupByTournamentAction($idTournament){

        $this->groupService->setManager($this->getDoctrine()->getManager());
        $groups = $this->groupService->getGroupsByTournament($idTournament);

        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $tournamentService->getTournament($idTournament);

        if(!$tournament instanceof Tournament){
            return $this->util->setResponse(400, Literals::TournamentNotFound);
        }
        else{
            $dataToSend = json_encode(array('group' => $groups));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getGroupByCategoryAction($idCategory){

        $this->groupService->setManager($this->getDoctrine()->getManager());
        $groups = $this->groupService->getGroupsByCategory($idCategory);

        $categoryService = new CategoryService();
        $categoryService->setManager($this->getDoctrine()->getManager());
        $category = $categoryService->getCategory($idCategory);

        if(!$category instanceof Category){
            return $this->util->setResponse(400, Literals::CategoryNotFound);
        }
        else{
            $dataToSend = json_encode(array('group' => $groups));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function postGroupAction(){

        $this->groupService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        if(!empty($params['nextGroupTo'])){
            $nextGroupTo = $this->groupService->getGroup((int) $params['nextGroupTo']);
            $params['name'] = Literals::NewGroupLabel;
            $params['tournament'] = $nextGroupTo->getTournament();
            $params['category'] = $nextGroupTo->getCategory();
            $params['numPairs'] = 0;
        }
        $group = $this->groupService->saveGroup($params);
        $dataToSend = json_encode(array('group' => $group['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }

    public function postGroupsTournamentAction(){

        $this->groupService->setManager($this->getDoctrine()->getManager());

        $params = array();
        $content = $this->get("request")->getContent();
        $params = json_decode($content, true);
        $group = $this->groupService->saveGroupsTournament($params);
        if($group['result'] == 'fail'){
            $dataToSend = json_encode(array('error' => $group['message']));
            return $this->util->setResponse(400, $dataToSend);
        }
        $dataToSend = json_encode(array('group' => $group['message']));
        return $this->util->setJsonResponse(201, $dataToSend);
    }
}
