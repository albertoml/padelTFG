<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Service\InscriptionService as InscriptionService;
use PadelTFG\GeneralBundle\Service\GameService as GameService;
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
        $categoryEntity->setBgColor(isset($category['bgColor']) ? $category['bgColor'] : '');
        $categoryEntity->setRegisteredLimitMax(isset($category['registeredLimitMax']) && is_int($category['registeredLimitMax']) ? $category['registeredLimitMax'] : 0);
        $categoryEntity->setRegisteredLimitMin(isset($category['registeredLimitMin']) && is_int($category['registeredLimitMin']) ? $category['registeredLimitMin'] : 0);
        $categoryEntity->setGender(isset($category['gender']) ? $category['gender'] : null);
        $categoryEntity->setTournament($tournament);
        $validator = $controller->get('validator');
        $errors = $validator->validate($categoryEntity);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($categoryEntity);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $categoryEntity);
    }

    public function getDrawLength($numInscriptions){
        $flagDrawLengthFound = false;
        $drawLength = 0;
        $count = 0;
        while (!$flagDrawLengthFound) {
            if($numInscriptions <= pow(2, $count)){
                $flagDrawLengthFound = true;
                $drawLength = pow(2, $count);
            }
            else{
                $count = $count + 1;
            }
        }
        return $drawLength;
    }

    public function assignPairForDrawGame($inscriptions, $numMatchsLength, $numMatch, $pair){
        
        $nameLiteral = 'draw' . $numMatchsLength*2;

        if($pair == 'pair1'){
            $index = Literals::$nameLiteral[$numMatch*2];
        }
        else{
            $index = Literals::$nameLiteral[$numMatch*2 + 1];   
        }
                
        $inscription = $inscriptions[$index];
        if($inscription instanceof Pair){
            return $inscription;
        }
        else{
            return null;
        }
    }

    public function generateDraw($inscriptions, $numMatchs, $categoryId, $tournamentId){
        $gameService = new GameService();
        $gameService->setManager($this->em);

        $gamesOnDraw = array();
        $flagFirstRound = true;

        while($numMatchs > 0){
            for($i = 0; $i < $numMatchs; $i ++){
                $paramsForDoGame = array(
                    'category' => $categoryId,
                    'tournament' => $tournamentId,
                    'isDrawGame' => true
                );
                if($flagFirstRound){
                    $paramsForDoGame['pair1'] = $this->assignPairForDrawGame($inscriptions, $numMatchs, $i, 'pair1');    
                    $paramsForDoGame['pair2'] = $this->assignPairForDrawGame($inscriptions, $numMatchs, $i, 'pair2');    
                }
                $paramsForDoGame['description'] = $numMatchs . '/' . $i;
                $gameSaved = $gameService->saveGame($paramsForDoGame);
                $gamesOnDraw[] = $gameSaved['message'];
            }
            $flagFirstRound = false;
            $numMatchs = $numMatchs / 2;
            $numMatchs = intval($numMatchs);
        }
        return $gamesOnDraw;
    }

    public function createDrawForCategory($inscriptions, $categoryId, $tournamentId){
        $inscriptionService = new InscriptionService();
        $inscriptionService->setManager($this->em);

        $inscriptions = $inscriptionService->orderByClassified($inscriptions);

        $numInscriptions = count($inscriptions);
        $drawLength = $this->getDrawLength($numInscriptions);
        $numPairsBye = $drawLength - $numInscriptions;
        for($i = 0; $i < $numPairsBye; $i ++){
            $inscriptions[] = Literals::ByePairName;
        }

        $numMatchs = $drawLength / 2;
        $numMatchs = intval($numMatchs);
        $gamesOnDraw = $this->generateDraw($inscriptions, $numMatchs, $categoryId, $tournamentId);
        return $gamesOnDraw;
    }
}
