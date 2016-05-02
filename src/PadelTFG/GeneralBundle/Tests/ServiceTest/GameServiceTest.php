<?php

namespace PadelTFG\GeneralBundle\Tests\ServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Entity\User;
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
}
