<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\GroupCategory;

class GroupController extends FOSRestController
{

    var $util;

    function __construct(){ 
        $this->util = new Util();
    }

	public function allGroupAction(){
        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:GroupCategory');
        $groups = $repository->findAll();
        $dataToSend = json_encode(array('group' => $groups));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getGroupAction($id){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->find($id);

        if (!$group instanceof Group) {
            return $this->util->setResponse(404, Literals::GroupNotFound);
        }
        $dataToSend = json_encode(array('group' => $group));
        return $this->util->setJsonResponse(200, $dataToSend);
    }

    public function getGroupByTournamentAction($idTournament){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:GroupCategory');
        $groups = $repository->findByTournament($idTournament);

        $repositoryTournament = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Tournament');
        $tournament = $repositoryTournament->find($idTournament);

        if($tournament==null){
            return $this->util->setResponse(400, Literals::TournamentNotFound);
        }
        else{
            $dataToSend = json_encode(array('group' => $groups));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    public function getGroupByCategoryAction($idCategory){

        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:GroupCategory');
        $groups = $repository->findByCategory($idCategory);

        $repositoryCategory = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Category');
        $category = $repositoryCategory->find($idCategory);

        if($category==null){
            return $this->util->setResponse(400, Literals::CategoryNotFound);
        }
        else{
            $dataToSend = json_encode(array('group' => $groups));
            return $this->util->setJsonResponse(200, $dataToSend);
        }
    }

    private function getInscriptionByCategoryOrderBySeeded($categoryId){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT i FROM GeneralBundle:Inscription i WHERE i.category = :category ORDER BY i.seeded ASC'
        )->setParameters(array(
            'category' => $categoryId
        ));
        $inscriptions = $query->getResult();
        return $inscriptions;
    }

    private function generateGroups($categoryId){

        $inscriptions = $this->getInscriptionByCategoryOrderBySeeded($categoryId);
        $repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:GroupCategory');
        $groups = $repository->findByCategory($categoryId);
        $numGroups = count($inscriptions) / count($groups);
        /*foreach ($inscriptions as $inscription) {

        }*/
        return $numGroups;
    }

    public function addGroupForTournamentAction($idTournament){
            
    }

    public function addGroupForCategoryAction($idCategory){

        $em = $this->getDoctrine()->getManager();
        $params = array();
        $content = $this->get("request")->getContent();
        if (!empty($content)){

            $params = json_decode($content, true);
            if(!empty($params['group'])){

                $repositoryCategory = $em->getRepository('GeneralBundle:Category');
                $category = $repositoryCategory->find($idCategory);
                foreach ($params['group'] as $group) {
                    $groupEntity = new GroupCategory();
                    $groupEntity->setName($group['name']);
                    $groupEntity->setCategory($category);
                    $groupEntity->setTournament($category->getTournament());
                    $em->persist($groupEntity);
                }
                $em->flush();
                $dataToSend = json_encode(array('group' => $category));
                return $this->util->setJsonResponse(200, $dataToSend);

            } else {
                return $this->util->setResponse(400, Literals::GroupNotFound);
            }
        } else {
            return $this->util->setResponse(400, Literals::EmptyContent);
        }
    }
}
