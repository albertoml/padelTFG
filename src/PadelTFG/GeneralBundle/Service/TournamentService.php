<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;
use PadelTFG\GeneralBundle\Service\InscriptionService as InscriptionService;
use PadelTFG\GeneralBundle\Service\GroupService as GroupService;
class TournamentService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($this->em);
    } 

	public function allTournaments(){
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findAll();
        return $tournament;
    }

    public function getTournament($id){
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->find($id);
        return $tournament;
    }

    public function getTournamentsByAdmin($id){
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findByAdmin($id);
        return $tournament;
    }

    private function setTournamentSave($tournament, $params, $user){
        $tournament->setAdmin($user);
        $tournament->setName($params['name']);
        $tournament->setStartInscriptionDate(!empty($params['startInscriptionDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startInscriptionDate']) : null);
        $tournament->setEndInscriptionDate(!empty($params['endInscriptionDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endInscriptionDate']) : null);
        $tournament->setStartGroupDate(!empty($params['startGroupDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startGroupDate']) : null);
        $tournament->setEndGroupDate(!empty($params['endGroupDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endGroupDate']) : null);
        $tournament->setStartFinalDate(!empty($params['startFinalDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startFinalDate']) : null);
        $tournament->setEndFinalDate(!empty($params['endFinalDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endFinalDate']) : null);
        $tournament->setRegisteredLimit(!empty($params['registeredLimit']) ? $params['registeredLimit'] : 0);
        $tournament->setImage(!empty($params['image']) ? $params['image'] : '');
        $tournament->setStatus($this->statusService->getStatus($this->em, 'tournament', 'Created'));

        return $tournament;
    }

    public function saveTournament($params, $user, $controller){
        $tournament = new Tournament();
        $tournament = $this->setTournamentSave($tournament, $params, $user);
        $validator = $controller->get('validator');
        $errors = $validator->validate($tournament);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($tournament);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $tournament);
    }

    private function setTournamentModify($tournament, $params){

        $tournament->setName(isset($params['name']) ? $params['name'] : $tournament->getName());
        $tournament->setCreationDate(!empty($params['creationDate']) ? \DateTime::createFromFormat('d/m/Y', $params['creationDate']) : $tournament->getCreationDate());
        $tournament->setStartInscriptionDate(!empty($params['startInscriptionDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startInscriptionDate']) : $tournament->getStartInscriptionDate());
        $tournament->setEndInscriptionDate(!empty($params['endInscriptionDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endInscriptionDate']) : $tournament->getEndInscriptionDate());
        $tournament->setStartGroupDate(!empty($params['startGroupDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startGroupDate']) : $tournament->getStartGroupDate());
        $tournament->setEndGroupDate(!empty($params['endGroupDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endGroupDate']) : $tournament->getEndGroupDate());
        $tournament->setStartFinalDate(!empty($params['startFinalDate']) ? \DateTime::createFromFormat('d/m/Y', $params['startFinalDate']) : $tournament->getStartFinalDate());
        $tournament->setEndFinalDate(!empty($params['endFinalDate']) ? \DateTime::createFromFormat('d/m/Y', $params['endFinalDate']) : $tournament->getEndFinalDate());
        $tournament->setRegisteredLimit(isset($params['registeredLimit']) ? $params['registeredLimit'] : $tournament->getRegisteredLimit());
        $tournament->setImage(isset($params['image']) ? $params['image'] : $tournament->getImage());
        $tournament->setStatus(isset($params['status']) ? $params['status'] : $tournament->getStatus());

        return $tournament;
    }

    public function modifyTournament($tournament, $params, $controller){
        $tournament = $this->setTournamentModify($tournament, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($tournament);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($tournament);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $tournament);
    }

    public function deleteTournament($tournament){
        $this->em->remove($tournament);
        $this->em->flush();
    }

    public function closeInscriptionTournament($tournament, $params){
        $categoryService = new CategoryService();
        $inscriptionService = new InscriptionService();
        $groupService = new GroupService();
        $categoryService->setManager($this->em);
        $inscriptionService->setManager($this->em);
        $groupService->setManager($this->em);

        $categories = $categoryService->getCategoryByTournament($tournament->getId());
        $inscriptionsToSend = array();

        foreach ($categories as $category) {
            $inscriptions = $inscriptionService->getInscriptionsByCategory($category->getId());
            for ($i=0; $i < $params[$category->getName()]; $i++) { 
                $group = array('name' => 'Group ' . $i,
                                'category' => $category,
                                'tournament' => $tournament);
                $groupService->saveGroup($group);   
            }
        }
        $result = array('result' => 'ok', 'message' => $inscriptionsToSend);
        return $result;
    }
}
