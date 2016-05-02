<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Service\InscriptionService as InscriptionService;

class GroupService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allGroups(){
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $groups = $repository->findAll();
        return $groups;
    }

    public function getGroup($id){
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->find($id);
        return $group;
    }

    public function getGroupsByCategory($idCategory){
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $groups = $repository->findByCategory($idCategory);
        return $groups;
    }

    public function getGroupsByTournament($idTournament){
        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);
        $categories = $categoryService->getCategoryByTournament($idTournament);
        $returnGroups = array();
        foreach ($categories as $category) {
            $groups = $this->getGroupsByCategory($category->getId());
            $returnGroups[$category->getName() . ';' . $category->getId()] = $groups;
        }
        return $returnGroups;
    }

    private function setGroupSave($group, $params){
        $group->setName(!empty($params['name']) ? $params['name'] : 'No name');

        if(!empty($params['category'])){
            if(is_int($params['category'])){
                $categoryService = new CategoryService();
                $categoryService->setManager($this->em);
                $cat = $categoryService->getCategory($params['category']);
                $group->setCategory($cat);
            }
            else{
                $group->setCategory($params['category']);
            }
        }
        else{
            $group->setCategory(null);
        }

        if(!empty($params['tournament'])){
            if(is_int ($params['tournament'])){
                $tournamentService = new TournamentService();
                $tournamentService->setManager($this->em);
                $cat = $tournamentService->getTournament($params['tournament']);
                $group->setTournament($cat);
            }
            else{
                $group->setTournament($params['tournament']);
            }
        }
        else{
            $group->setTournament(null);
        }

        $group->setNumPairs(is_int($params['numPairs']) ? $params['numPairs'] : null);
        return $group;
    }

    public function saveGroup($params){
        $group = new GroupCategory();
        $group = $this->setGroupSave($group, $params);        
        $this->em->persist($group);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $group);
    }

    public function saveGroupsTournament($params){

        foreach ($params as $catId => $category) {
            $persistGroupsCategory = array();
            foreach ($category as $groupKey => $group) {
                if($group['groupId'] != 'newGroup'){
                    $groupEntity = $this->getGroup($group['groupId']);
                    $groupEntity = $this->modifyGroup($groupEntity, $group);
                    $this->checkPairsInGroup($groupEntity['message'], $group['category'], $group['pairs']);
                    $persistGroupsCategory[] = $groupEntity['message'];
                }
                else{
                    $groupResult = $this->saveGroup($group);
                    $this->checkPairsInGroup($groupResult['message'], $group['category'], $group['pairs']);
                    $persistGroupsCategory[] = $groupResult['message'];
                }
            }
            $allGroups = $this->getGroupsByCategory($catId);
            foreach ($allGroups as $groupCandidate) {
                if(!$this->containGroup($groupCandidate, $persistGroupsCategory)){
                    $this->deleteGroup($groupCandidate);
                }
            }
        }
        return array('result' => 'ok', 'message' => $persistGroupsCategory);
    }

    private function containGroup($group, $arrayGroups){
        foreach ($arrayGroups as $item) {
            if($item->getId() == $group->getId()){
                return true;
            }
        }
        return false;
    }

    public function checkPairsInGroup($group, $categoryId, $pairs){

        $inscriptionService = new InscriptionService();
        $inscriptionService->setManager($this->em);
        $inscirptions  = array();

        foreach ($pairs as $pair) {
            $inscriptionEntity = $inscriptionService->getInscriptionsByPairAndCategory($pair, $categoryId);
            if($inscriptionEntity instanceof Inscription){
                if($inscriptionEntity->getGroup()->getId() != $group->getId()){
                    $inscriptionEntity->setGroup($group);
                    $this->em->persist($inscriptionEntity);
                }
            }
        }
        $this->em->flush();
    }

    private function setGroupModify($group, $params){

        $group->setName(!empty($params['name']) ? $params['name'] : $group->getName());
        $group->setNumPairs(is_int($params['numPairs']) ? $params['numPairs'] : $group->getNumPairs());

        return $group;
    }

    public function modifyGroup($group, $params){
        $group = $this->setGroupModify($group, $params);
        $this->em->persist($group);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $group);
    }

    public function deleteGroupsByCategory($catId){
        $groups = $this->getGroupsByCategory($catId);
        foreach ($groups as $group) {
            $this->deleteGroup($group);
        }
    }

    public function deleteGroup($group){
        $this->em->remove($group);
        $this->em->flush();
    }
}
