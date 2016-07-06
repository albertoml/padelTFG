<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Game;
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

    public function checkPairToAddDraw($description){
        $gameService = new GameService();
        $gameService->setManager($this->em);
        $game = $gameService->getGameByDescription($description);
        if($game instanceof Game){
            if(is_null($game->getPair1()) && !is_null($game->getPair2())){
                $game->setStatus($this->statusService->getStatus('game', Literals::WonPair2GameStatus));
                $this->em->persist($game);
                $this->em->flush();
                return $game->getPair2();
            }
            else if(is_null($game->getPair2()) && !is_null($game->getPair1())){
                $game->setStatus($this->statusService->getStatus('game', Literals::WonPair1GameStatus));
                $this->em->persist($game);
                $this->em->flush();
                return $game->getPair1();
            }
        }
        return null;
    }

    public function assignPairForDrawGame($inscriptions, $numMatchsLength, $numMatch, $pair){
        
        if($pair == 'pair1'){
            $indexInc = 0;
        }
        else{
            $indexInc = 1;
        }

        $index = -1;

        switch ($numMatchsLength) {
            case 1:
                $index = Literals::$draw2[$numMatch*2 + $indexInc];
                break;
            case 2:
                $index = Literals::$draw4[$numMatch*2 + $indexInc];
                break;
            case 4:
                $index = Literals::$draw8[$numMatch*2 + $indexInc];
                break;
            case 8:
                $index = Literals::$draw16[$numMatch*2 + $indexInc];
                break;
            default:
                $index = -1;
                break;
        }

        if($index > -1 && $index < count($inscriptions)){
            $inscription = $inscriptions[$index];
            if($inscription instanceof Inscription){
                return $inscription->getPair();
            }
            else{
                return null;
            }
        }
        else{
            return null;
        } 
    }

    public function generateDraw($inscriptions, $numMatchs, $categoryId, $tournamentId){
        $gameService = new GameService();
        $gameService->setManager($this->em);

        $gamesOnDraw = array();
        $flagRound = 0;

        while($numMatchs > 0){
            for($i = 0; $i < $numMatchs; $i ++){
                $paramsForDoGame = array(
                    'category' => $categoryId,
                    'tournament' => $tournamentId,
                    'isDrawGame' => true
                );
                $paramsForDoGame['description'] = $tournamentId . ';' . $categoryId . '|' . $numMatchs . '/' . $i;
                if($flagRound == 0){
                    $paramsForDoGame['pair1'] = $this->assignPairForDrawGame($inscriptions, $numMatchs, $i, 'pair1');    
                    $paramsForDoGame['pair2'] = $this->assignPairForDrawGame($inscriptions, $numMatchs, $i, 'pair2');    
                }
                else if($flagRound == 1){
                    $descriptionPair1 = $tournamentId . ';' . $categoryId . '|' . $numMatchs*2 . '/' . $i*2;    
                    $paramsForDoGame['pair1'] = $this->checkPairToAddDraw($descriptionPair1);

                    $index = $i*2;
                    $index = $index + 1;
                    $descriptionPair2 = $tournamentId . ';' . $categoryId . '|' . $numMatchs*2 . '/' . $index;

                    $paramsForDoGame['pair2'] = $this->checkPairToAddDraw($descriptionPair2);   
                }
                $gameSaved = $gameService->saveGame($paramsForDoGame);
                $gamesOnDraw[] = $gameSaved['message'];
            }
            $flagRound ++;
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
