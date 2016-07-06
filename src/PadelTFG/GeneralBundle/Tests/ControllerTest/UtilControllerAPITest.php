<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

class UtilControllerAPITest extends WebTestCase
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
        $this->client->request('GET', '/api/util/userGender');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::GenderMale, $response);
        $this->assertContains(Literals::GenderFemale, $response);
    }
    public function testGetGendersActionAPI()
    {
        $this->client->request('GET', '/api/util/gender');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::GenderMale, $response);
        $this->assertContains(Literals::GenderFemale, $response);
        $this->assertContains(Literals::GenderMixed, $response);
    }
}
