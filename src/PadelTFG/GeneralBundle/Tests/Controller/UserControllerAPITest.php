<?php

namespace PadelTFG\GeneralBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\User;

class UserControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/UserTest/UserTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/UserTest/UserTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllUserActionAPI()
    {
        $this->client->request('GET', '/api/user');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('amlTest58@alu.ua.es', $response);
        $this->assertContains('rcsTest58@alu.ua.es', $response);
    }

    public function testGetUserNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/user/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }

    public function testGetUserActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByEmail("amlTest58@alu.ua.es");

        $this->client->request('GET', '/api/user/' . $user->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('amlTest58@alu.ua.es', $response);
    }

    public function testPostUserActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/user';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'name' => 'Paco',
            'lastName' => 'Carrasco Soriano',
            'email' => 'pcs58POSTTest@alu.ua.es',
            'password' => 'hola123',
            'firstPhone' => '653293954'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('pcs58POSTTest@alu.ua.es', $response);
    }

    public function testPostEmailRegisteredUserActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/user';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'name' => 'Paco',
            'lastName' => 'Carrasco Soriano',
            'email' => 'pcs58POSTTestEmailRegistered@alu.ua.es',
            'password' => 'hola123',
            'firstPhone' => '653293954'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::EmailRegistered, $response);
    }

    public function testPostEmptyRequiredFieldsUserActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/user';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'firstPhone' => '653293954'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $expectedError = Literals::NameEmpty . Literals::LastNameEmpty . Literals::EmailEmpty . Literals::PasswordEmpty;
        $this->assertContains($expectedError, $response);
    }

    public function testPostEmptyContentUserActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/user';
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::EmptyContent, $response);
    }

    public function testPutUserActionAPI()
    {

    	$repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByEmail("amlTest58@alu.ua.es");

    	$method = 'PUT';
        $uri = '/api/user/' . $user->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'firstPhone' => '111111111'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('111111111', $response);
    }

    public function testPutNotFoundUserActionAPI()
    {
    	$method = 'PUT';
        $uri = '/api/user/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'firstPhone' => '111111111'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }

    public function testPutEmptyContentUserActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByEmail("amlTest58@alu.ua.es");

    	$method = 'PUT';
        $uri = '/api/user/' . $user->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::EmptyContent, $response);
    }

    public function testDeleteUserActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByEmail("rcsTestDelete58@alu.ua.es");

    	$method = 'DELETE';
        $uri = '/api/user/' . $user->getId();
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
        $uri = '/api/user/0';
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
