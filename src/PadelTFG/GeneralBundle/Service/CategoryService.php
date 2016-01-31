<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;

class CategoryService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allCategories(){
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findAll();
        return $category;
    }

    public function getCategory($id){
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->find($id);
        return $category;
    }

    public function getCategoryByTournament($idTournament){
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findByTournament($idTournament);
        return $category;
    }

    public function addCategoriesToTournament($categories, $tournament, $controller){
        foreach ($categories as $category) {
            $this->addCategoryToTournament($category, $tournament, $controller);
        }
        return $tournament;
    }

    public function addCategoryToTournament($category, $tournament, $controller){
        $categoryEntity = new Category();
        $categoryEntity->setName(isset($category['name']) ? $category['name'] : '');
        $categoryEntity->setRegisteredLimitMax(isset($category['registeredLimitMax']) ? $category['registeredLimitMax'] : null);
        $categoryEntity->setRegisteredLimitMin(isset($category['registeredLimitMin']) ? $category['registeredLimitMin'] : null);
        $validator = $controller->get('validator');
        $errors = $validator->validate($categoryEntity);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $tournament->addCategory($categoryEntity);
        $this->em->persist($categoryEntity);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $categoryEntity);
    }
}
