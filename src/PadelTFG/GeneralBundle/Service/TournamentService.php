<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;

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

    private function setTournamentSave($tournament, $params, $user){
        $tournament->setAdmin($user);
        $tournament->setName($params['name']);
        $tournament->setStartInscriptionDate(isset($params['startInscriptionDate']) ? new \DateTime($params['startInscriptionDate']) : null);
        $tournament->setEndInscriptionDate(isset($params['endInscriptionDate']) ? new \DateTime($params['endInscriptionDate']) : null);
        $tournament->setStartGroupDate(isset($params['startGroupDate']) ? new \DateTime($params['startGroupDate']) : null);
        $tournament->setEndGroupDate(isset($params['endGroupDate']) ? new \DateTime($params['endGroupDate']) : null);
        $tournament->setStartFinalDate(isset($params['startFinalDate']) ? new \DateTime($params['startFinalDate']) : null);
        $tournament->setEndFinalDate(isset($params['endFinalDate']) ? new \DateTime($params['endFinalDate']) : null);
        $tournament->setRegisteredLimit(isset($params['registeredLimit']) ? $params['registeredLimit'] : 0);
        $tournament->setImage(isset($params['image']) ? $params['image'] : '');
        $tournament->setStatus($this->statusService->getStatus($this->em, 'tournament', 'created'));

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
        $tournament->setCreationDate(isset($params['creationDate']) ? new \DateTime($params['creationDate']) : $tournament->getCreationDate());
        $tournament->setStartInscriptionDate(isset($params['startInscriptionDate']) ? new \DateTime($params['startInscriptionDate']) : $tournament->getStartInscriptionDate());
        $tournament->setEndInscriptionDate(isset($params['endInscriptionDate']) ? new \DateTime($params['endInscriptionDate']) : $tournament->getEndInscriptionDate());
        $tournament->setStartGroupDate(isset($params['startGroupDate']) ? new \DateTime($params['startGroupDate']) : $tournament->getStartGroupDate());
        $tournament->setEndGroupDate(isset($params['endGroupDate']) ? new \DateTime($params['endGroupDate']) : $tournament->getEndGroupDate());
        $tournament->setStartFinalDate(isset($params['startFinalDate']) ? new \DateTime($params['startFinalDate']) : $tournament->getStartFinalDate());
        $tournament->setEndFinalDate(isset($params['endFinalDate']) ? new \DateTime($params['endFinalDate']) : $tournament->getEndFinalDate());
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
}
