<?php

namespace PadelTFG\GeneralBundle\Tests\ServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;

class StatusServiceTest extends WebTestCase
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

    public function testAllTournamentStatus()
    {
        $statusService = new StatusService();
        $statusService->setManager($this->em);
        $status = $statusService->getAllStatus('tournament');
        $this->assertEquals(7, count($status));
    }
    public function testTournamentStatus()
    {
        $statusService = new StatusService();
        $statusService->setManager($this->em);
    	$status = $statusService->getStatus('tournament', Literals::In_Group_DateTournamentStatus);
        $this->assertEquals(3, $status->getId());
        $this->assertEquals(Literals::In_Group_DateTournamentStatus, $status->getValue());
    }

    public function testUserStatus()
    {
        $statusService = new StatusService();
        $statusService->setManager($this->em);
        $status = $statusService->getStatus('user', Literals::RegisteredUserStatus);
        $this->assertEquals(1, $status->getId());
        $this->assertEquals(Literals::RegisteredUserStatus, $status->getValue());
    }

    public function testGameStatus()
    {
        $statusService = new StatusService();
        $statusService->setManager($this->em);
        $status = $statusService->getStatus('game', Literals::CreatedGameStatus);
        $this->assertEquals(1, $status->getId());
        $this->assertEquals(Literals::CreatedGameStatus, $status->getValue());
    }

    public function testGameNotFoundStatus()
    {
        $statusService = new StatusService();
        $statusService->setManager($this->em);
        $status = $statusService->getStatus('game', 'status not found');
        $this->assertEquals(null, $status);
    }

    public function testNotFoundEntityStatus()
    {
        $statusService = new StatusService();
        $statusService->setManager($this->em);
        $status = $statusService->getStatus('entity not found', 'status not found');
        $this->assertEquals(null, $status);
    }

    public function testNotFoundAllStatus()
    {
        $statusService = new StatusService();
        $statusService->setManager($this->em);
        $status = $statusService->getAllStatus('entity not found');
        $this->assertEquals(null, $status);
    }
}
