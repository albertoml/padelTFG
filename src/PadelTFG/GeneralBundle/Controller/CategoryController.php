<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;

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

        $repositoryTournament = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Tournament');
        $tournament = $repositoryTournament->find($idTournament);

        if($tournament==null){
            return $this->util->setResponse(400, Literals::TournamentNotFound);
        }
        else{
            $dataToSend = json_encode(array('category' => $categories));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

      public function addCategoryAction($idTournament){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->find($idTournament);

        if ($tournament instanceof Tournament) {

            $params = array();
            $content = $this->get("request")->getContent();

            if (!empty($content)){

                $params = json_decode($content, true);
                if(!empty($params['category'])){

                    foreach ($params['category'] as $category) {
                        $categoryEntity = new Category();
                        $categoryEntity->setName($category['name']);
                        $categoryEntity->setRegisteredLimitMax(isset($category['registeredLimitMax']) ? $category['registeredLimitMax'] : null);
                        $categoryEntity->setRegisteredLimitMin(isset($category['registeredLimitMin']) ? $category['registeredLimitMin'] : null);
                        $tournament->addCategory($categoryEntity);
                        $em->persist($categoryEntity);
                    }
                    $em->persist($tournament);
                    $em->flush();

                    $dataToSend = json_encode(array('tournament' => $tournament));
                    return $this->util->setJsonResponse(200, $dataToSend);
                } else {
                    return $this->util->setResponse(400, Literals::CategoryNotFound);
                }
            } else {
                return $this->util->setResponse(400, Literals::EmptyContent);
            }
        } else {
            return $this->util->setResponse(404, Literals::TournamentNotFound);
        }
    }
}
