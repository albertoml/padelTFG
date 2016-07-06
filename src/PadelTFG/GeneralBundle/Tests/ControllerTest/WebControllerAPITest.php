<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

class WebControllerAPITest extends WebTestCase
{
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
    }

    protected function tearDown()
    {
    }

    public function testGetUserGendersActionAPI()
    {
        $this->client->request('GET', '/api/web/index');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Hello', $response);
    }
}
