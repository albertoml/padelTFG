<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Category;

class CategoryController extends FOSRestController
{

    var $util;

    function __construct(){ 
        $this->util = new Util();
    }

	public function allCategoryAction(){
        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Category');
        $categories = $repository->findAll();
        $dataToSend = json_encode(array('category' => $categories));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getCategoryAction($id){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Category');
        $category = $repository->find($id);

        if (!$category instanceof Category) {
            return $this->util->setResponse(404, Literals::CategoryNotFound);
        }
        $dataToSend = json_encode(array('category' => $category));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getCategoryByTournamentAction($idTournament){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Category');
        $categories = $repository->findByTournament($idTournament);

        $dataToSend = json_encode(array('category' => $categories));
        return $this->util->setJsonResponse(200, $dataToSend);
    }
}
