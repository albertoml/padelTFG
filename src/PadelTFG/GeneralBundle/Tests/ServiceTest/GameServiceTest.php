<?php

namespace PadelTFG\GeneralBundle\Tests\ServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Service\GameService as GameService;

class GameServiceTest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function tearDown()
    {
    }

    public function testGetNumberMatchs_2_10()
    {
        $results = array(1, 3, 6, 10, 15, 21, 28, 36, 45);
        $gameService = new GameService();
        $gameService->setManager($this->em);
        for($i = 2 ; $i < 11 ; $i ++){
            $numberMatchs = $gameService->getNumberMatchs($i);
            $this->assertEquals($results[$i - 2], $numberMatchs);
        }
    }

    public function testUpdateScore()
    {
        //this test depend the system parametes points to winner and loser values

        $rank[1] = array('matchsWon' => 0, 'setsWon' => 0, 'setsLost' => 0, 'gamesWon' => 0, 'gamesLost' => 0, 'points' => 0);
        $rank[2] = array('matchsWon' => 0, 'setsWon' => 0, 'setsLost' => 0, 'gamesWon' => 0, 'gamesLost' => 0, 'points' => 0);

        $rankResult[1] = array('matchsWon' => 2, 'setsWon' => 5, 'setsLost' => 3, 'gamesWon' => 35, 'gamesLost' => 38, 'points' => 6);
        $rankResult[2] = array('matchsWon' => 1, 'setsWon' => 3, 'setsLost' => 5, 'gamesWon' => 38, 'gamesLost' => 35, 'points' => 3);

        $gameService = new GameService();
        $gameService->setManager($this->em);
        $rank = $gameService->updateScore("6/3 2/6 7/5", $rank, 1, 2);
        $rank = $gameService->updateScore("6/4 7/5", $rank, 1, 2);
        $rank = $gameService->updateScore("0/6 6/3 1/6", $rank, 1, 2);
        $this->assertEquals($rank, $rankResult);
    }

    public function testWonGameByScoreWon1()
    {
        $gameService = new GameService();
        $gameService->setManager($this->em);
        $wonMatch = $gameService->wonGameByScore('6/3 6/3');
        $this->assertEquals($wonMatch, 1);
    }

    public function testWonGameByScoreWon2()
    {
        $gameService = new GameService();
        $gameService->setManager($this->em);
        $wonMatch = $gameService->wonGameByScore('6/7 6/7');
        $this->assertEquals($wonMatch, 2);
    }
}
