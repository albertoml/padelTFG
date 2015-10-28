<?php

namespace PadelTFG\GeneralBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Recordal;

class RecordalControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/RecordalTest/RecordalTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/RecordalTest/RecordalTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllRecordalActionAPI()
    {
        $this->client->request('GET', '/api/recordal');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Recordal TFG', $response);
        $this->assertContains('Recordal TFG2', $response);
        $this->assertContains('Recordal TFG3', $response);
        $this->assertContains('Recordal TFG4', $response);
        $this->assertContains('Recordal TFG5', $response);
    }

    public function testGetRecordalNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/recordal/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::RecordalNotFound, $response);
    }

    public function testGetRecordalActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Recordal');
        $recordal = $repository->findOneByText("Recordal TFG");

        $this->client->request('GET', '/api/recordal/' . $recordal->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Recordal TFG', $response);
    }

    public function testPostRecordalActionAPI()
    {
        $date = new \DateTime();
        $date->modify('+1 day');
        $recordalDate = $date->format('Y-m-d');

    	$method = 'POST';
        $uri = '/api/recordal';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => "Recordal POST",
            'recordalDate' => $recordalDate
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Recordal POST', $response);
    }

    public function testPostRecordalIncorrectRecordalDateActionAPI()
    {
        $date = new \DateTime();
        $date->modify('-1 day');
        $recordalDate = $date->format('Y-m-d');

        $method = 'POST';
        $uri = '/api/recordal';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => "Recordal POST",
            'recordalDate' => $recordalDate
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::RecordalDateIncorrect, $response);
    }

    public function testPostEmptyRequiredFieldsRecordalActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/recordal';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'notFieldRequired' => 34
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $expectedError = Literals::TextEmpty . Literals::RecordalDateEmpty;
        $this->assertContains($expectedError, $response);
    }

    public function testPostEmptyContentRecordalActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/recordal';
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::EmptyContent, $response);
    }

    public function testPutRecordalActionAPI()
    {

    	$repository = $this->em->getRepository('GeneralBundle:Recordal');
        $recordal = $repository->findOneByText("Recordal TFG");
    	$method = 'PUT';
        $uri = '/api/recordal/' . $recordal->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => 'Modify Recordal'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"text":"Modify Recordal"', $response);
    }

    public function testPutRecordalIncorrectRecordalDateActionAPI()
    {
        $date = new \DateTime();
        $date->modify('-1 day');
        $recordalDate = $date->format('Y-m-d');

        $repository = $this->em->getRepository('GeneralBundle:Recordal');
        $recordal = $repository->findOneByText("Recordal TFG");

        $method = 'PUT';
        $uri = '/api/recordal/' . $recordal->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'recordalDate' => $recordalDate
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::RecordalDateIncorrect, $response);
    }

    public function testPutNotFoundRecordalActionAPI()
    {
    	$method = 'PUT';
        $uri = '/api/recordal/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => 'Modify Recordal'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::RecordalNotFound, $response);
    }

    public function testPutEmptyContentRecordalActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Recordal');
        $recordal = $repository->findOneByText("Recordal TFG");

    	$method = 'PUT';
        $uri = '/api/recordal/' . $recordal->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::EmptyContent, $response);
    }

    public function testDeleteTournamentActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Recordal');
        $recordal = $repository->findOneByText("Recordal TFGDELETE");

    	$method = 'DELETE';
        $uri = '/api/recordal/' . $recordal->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::RecordalDeleted, $response);
    }

    public function testDeleteNotFoundRecordalActionAPI()
    {
    	$method = 'DELETE';
        $uri = '/api/recordal/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = "";

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::RecordalNotFound, $response);
    }
}
