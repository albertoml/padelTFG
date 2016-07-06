<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\UserPreference;

class UserPreferencesControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/UserPreferencesTest/UserPreferencesTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/UserPreferencesTest/UserPreferencesTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllUserPreferencesActionAPI()
    {
        $this->client->request('GET', '/api/userPreference');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"name":true', $response);
        $this->assertContains('"lastName":true', $response);
    }

    public function testGetUserPreferencesNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/userPreference/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }

    public function testGetUserPreferencesActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByEmail("amlTest58@alu.ua.es");

        $this->client->request('GET', '/api/userPreference/' . $user->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"name":true', $response);
        $this->assertContains('"lastName":true', $response);
    }

    public function testPutUserPreferencesNotFoundActionAPI()
    {
    	$method = 'PUT';
        $uri = '/api/userPreference/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'name' => false,
            'lastName' => false
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }

    public function testPutUserActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByEmail("amlTest58@alu.ua.es");

        $method = 'PUT';
        $uri = '/api/userPreference/' . $user->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'name' => false,
            'lastName' => false
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"name":false', $response);
        $this->assertContains('"lastName":false', $response);
    }

    public function testDeleteUserActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByEmail("rcsTestDelete58@alu.ua.es");

    	$method = 'DELETE';
        $uri = '/api/userPreference/' . $user->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserDeleted, $response);
    }

    public function testDeleteNotFoundUserActionAPI()
    {
    	$method = 'DELETE';
        $uri = '/api/userPreference/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = "";

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }
}
