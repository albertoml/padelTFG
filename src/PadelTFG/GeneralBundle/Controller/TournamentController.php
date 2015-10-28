<?php

namespace PadelTFG\GeneralBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use PadelTFG\GeneralBundle\Resources\globals\Utils as Util;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;

class TournamentController extends FOSRestController
{
	var $util;

    function __construct(){ 
        $this->util = new Util();
    } 

	public function allTournamentAction(){
		$repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Tournament');
        $tournaments = $repository->findAll();
        $dataToSend = json_encode(array('tournament' => $tournaments));
        return $this->util->setJsonResponse(200, $dataToSend);
	}

	public function getTournamentAction($id){

		$repository = $this->getDoctrine()->getManager()->getRepository('GeneralBundle:Tournament');

        $tournament = $repository->find($id);

        if (!$tournament instanceof Tournament) {
            return $this->util->setResponse(404, Literals::TournamentNotFound);
        }
        $dataToSend = json_encode(array('tournament' => $tournament));
        return $this->util->setJsonResponse(200, $dataToSend);
	}

	private function setTournamentPost($tournament, $params, $user){
		$tournament->setAdmin($user);
        $tournament->setName($params['name']);
        $tournament->setCreationDate(new \DateTime($params['creationDate']));
        $tournament->setStartInscriptionDate(isset($params['startInscriptionDate']) ? new \DateTime($params['startInscriptionDate']) : null);
        $tournament->setEndInscriptionDate(isset($params['endInscriptionDate']) ? new \DateTime($params['endInscriptionDate']) : null);
        $tournament->setStartGroupDate(isset($params['startGroupDate']) ? new \DateTime($params['startGroupDate']) : null);
        $tournament->setEndGroupDate(isset($params['endGroupDate']) ? new \DateTime($params['endGroupDate']) : null);
        $tournament->setStartFinalDate(isset($params['startFinalDate']) ? new \DateTime($params['startFinalDate']) : null);
        $tournament->setEndFinalDate(isset($params['endFinalDate']) ? new \DateTime($params['endFinalDate']) : null);
        $tournament->setRegisteredLimit(isset($params['registeredLimit']) ? $params['registeredLimit'] : 0);
        $tournament->setImage(isset($params['image']) ? $params['image'] : '');
        $tournament->setStatus($this->util->getStatus($this->getDoctrine()->getManager(), 'tournament', 'created'));

        return $tournament;
    }

    private function checkTournament($params){
        $isFail = false;
        $message = "";
        if(empty($params['admin'])){
            $isFail = true;
            $message .= Literals::AdminEmpty;
        }
        if(empty($params['name'])){
            $isFail = true;
            $message .= Literals::NameEmpty;
        }
        if(empty($params['creationDate'])){
            $isFail = true;
            $message .= Literals::CreationDateEmpty;
        }

        if($isFail){
            return $message;
        }
        else{
            return null;
        }
    }

    public function postTournamentAction(){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Tournament');
        $repositoryUser = $em->getRepository('GeneralBundle:User');

    	$params = array();
    	$content = $this->get("request")->getContent();

    	if (!empty($content)){

	    	$params = json_decode($content, true);
            $checked = $this->checkTournament($params);
            if($checked != null){
                return $this->util->setResponse(400, $checked);
            }

            $tournament = new Tournament();
            $user = $repositoryUser->findOneByEmail(trim($params['admin']));
            if($user != null){
            	$tournament = $this->setTournamentPost($tournament, $params, $user);

	            $em->persist($tournament);
	            $em->flush();

	            $dataToSend = json_encode(array('tournament' => $tournament));
	            return $this->util->setJsonResponse(201, $dataToSend);
            } else {
            	return $this->util->setResponse(400, Literals::AdminIncorrect);
            }   
        } else {
            return $this->util->setResponse(400, Literals::EmptyContent);
        }
    }

    private function setTournamentPut($tournament, $params, $user){

    	if($user != null){
    		$tournament->setAdmin($user);
    	}
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

	public function putTournamentAction($id){

		$em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->find($id);

        if ($tournament instanceof Tournament) {
            $params = array();
            $content = $this->get("request")->getContent();

            if (!empty($content)){

                $params = json_decode($content, true);
                if(!empty($params['admin'])){
    				
    				$repositoryUser = $em->getRepository('GeneralBundle:User');
    				$user = $repositoryUser->findOneByEmail($params['admin']);
                    if($user == null){
                        return $this->util->setResponse(400, Literals::AdminIncorrect);
                    }
    			} else {
    				$user = null;
    			}
                $tournament = $this->setTournamentPut($tournament, $params, $user);

                $em->persist($tournament);
                $em->flush();

                $dataToSend = json_encode(array('tournament' => $tournament));
                return $this->util->setJsonResponse(200, $dataToSend);
            } else {
                return $this->util->setResponse(400, Literals::EmptyContent);
            }
        } else {
            return $this->util->setResponse(404, Literals::TournamentNotFound);
        }

	}

	public function deleteTournamentAction($id){

		$em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->find($id);

        if ($tournament instanceof Tournament) {
            $em->remove($tournament);
            $em->flush();

            return $this->util->setResponse(200, Literals::TournamentDeleted);
        } else {
            return $this->util->setResponse(404, Literals::TournamentNotFound);
        }

	}
}
