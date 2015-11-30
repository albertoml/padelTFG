<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;

class CategoryController extends FOSRestController
{

    var $util;
    var $categoryService;

    function __construct(){ 
        $this->util = new Util();
        $this->categoryService = new CategoryService();
    }

	public function allCategoryAction(){
        $this->categoryService->setManager($this->getDoctrine()->getManager());
        $categories = $this->categoryService->allCategories();
        $dataToSend = json_encode(array('category' => $categories));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getCategoryAction($id){

        $this->categoryService->setManager($this->getDoctrine()->getManager());
        $category = $this->categoryService->getCategory($id);

        if (!$category instanceof Category) {
            return $this->util->setResponse(404, Literals::CategoryNotFound);
        }
        $dataToSend = json_encode(array('category' => $category));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getCategoryByTournamentAction($idTournament){

        $this->categoryService->setManager($this->getDoctrine()->getManager());
        $category = $this->categoryService->getCategoryByTournament($idTournament);

        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $tournamentService->getTournament($idTournament);

        if(!$tournament instanceof Tournament){
            return $this->util->setResponse(400, Literals::TournamentNotFound);
        }
        else{
            $dataToSend = json_encode(array('category' => $category));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

      public function addCategoryAction($idTournament){

        $this->categoryService->setManager($this->getDoctrine()->getManager());
        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->getDoctrine()->getManager());
        $tournament = $tournamentService->getTournament($idTournament);

        if ($tournament instanceof Tournament) {

            $params = array();
            $content = $this->get("request")->getContent();

            if (!empty($content)){

                $params = json_decode($content, true);
                if(!empty($params['category'])){

                    $tournament = $this->categoryService->addCategoriesToTournament($params['category'], $tournament, $this);

                    $dataToSend = json_encode(array('tournament' => $tournament));
                    return $this->util->setJsonResponse(200, $dataToSend);
                } else {
                    return $this->util->setResponse(400, Literals::CategoryNotFound);
                }
            } else {
                return $this->util->setResponse(400, Literals::EmptyContent);
            }
        } else {
            return $this->util->setResponse(400, Literals::TournamentNotFound);
        }
    }
}
