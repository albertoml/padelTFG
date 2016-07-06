<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\UserRole;

class UserRoleControllerAPITest extends WebTestCase
{
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
    }

    protected function tearDown()
    {
    }

    public function testAllUserRolesActionAPI()
    {
        $this->client->request('GET', '/api/userRole');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::Player, $response);
        $this->assertContains(Literals::TournamentAdmin, $response);
        $this->assertContains(Literals::Admin, $response);
    }
}
