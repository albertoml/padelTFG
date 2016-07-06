<?php

namespace PadelTFG\GeneralBundle\Tests\ServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\UserRole;
use PadelTFG\GeneralBundle\Service\UserRoleService as UserRoleService;

class UserRoleServiceTest extends WebTestCase
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

    public function testGetPlayerRole(){
        $userRoleService = new UserRoleService();
        $userRoleService->setManager($this->em);
        $role = $userRoleService->getPlayerRole();

        $this->assertContains(Literals::Player, json_encode($role));
    }
}
