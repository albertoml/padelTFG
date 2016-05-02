<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Sponsor;

class SponsorControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/SponsorTest/SponsorTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/SponsorTest/SponsorTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllSponsorActionAPI()
    {
        $this->client->request('GET', '/api/sponsor');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Sponsor TFG', $response);
        $this->assertContains('Sponsor TFG2', $response);
        $this->assertContains('Sponsor TFG3', $response);
        $this->assertContains('Sponsor TFG4', $response);
        $this->assertContains('Sponsor TFG5', $response);
    }

    public function testGetSponsorNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/sponsor/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::SponsorNotFound, $response);
    }

    public function testGetSponsorActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Sponsor');
        $sponsor = $repository->findOneByName("Sponsor TFG");

        $this->client->request('GET', '/api/sponsor/' . $sponsor->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Sponsor TFG', $response);
    }

    public function testPostSponsorActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/sponsor';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'cif' => "14293192F",
            'name' => 'Sponsor POST'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Sponsor POST', $response);
        $this->assertContains('14293192F', $response);
    }

    public function testPostSponsorRegisteredActionAPI()
    {
        $method = 'POST';
        $uri = '/api/sponsor';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'cif' => "111111111A",
            'name' => 'Sponsor TFG'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Sponsor).cif:', $response);
        $this->assertContains('This value is already used.', $response);
    }

    public function testPostEmptyRequiredFieldsSponsorActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/sponsor';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'notFieldRequired' => 34
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Sponsor).name:', $response);
        $this->assertContains('Sponsor).cif:', $response);
        $this->assertContains('This value should not be blank.', $response);
    }

    public function testPostEmptyContentSponsorActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/sponsor';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Sponsor).name:', $response);
        $this->assertContains('Sponsor).cif:', $response);
        $this->assertContains('This value should not be blank.', $response);
    }

    public function testPutSponsorActionAPI()
    {

    	$repository = $this->em->getRepository('GeneralBundle:Sponsor');
        $sponsor = $repository->findOneByName("Sponsor TFG");

    	$method = 'PUT';
        $uri = '/api/sponsor/' . $sponsor->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'name' => 'Sponsor TFGModify'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"cif":"111111111A"', $response);
        $this->assertContains("Sponsor TFGModify", $response);
    }

    public function testPutNotFoundSponsorActionAPI()
    {
    	$method = 'PUT';
        $uri = '/api/sponsor/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'name' => 'Sponsor TFGModify'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::SponsorNotFound, $response);
    }

    public function testPutEmptyContentSponsorActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Sponsor');
        $sponsor = $repository->findOneByName("Sponsor TFG");

    	$method = 'PUT';
        $uri = '/api/sponsor/' . $sponsor->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Sponsor TFG', $response);
    }

    public function testDeleteSponsorActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Sponsor');
        $sponsor = $repository->findOneByName("Sponsor TFGDELETE");

    	$method = 'DELETE';
        $uri = '/api/sponsor/' . $sponsor->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::SponsorDeleted, $response);
    }

    public function testDeleteNotFoundSponsorActionAPI()
    {
    	$method = 'DELETE';
        $uri = '/api/sponsor/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = "";

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::SponsorNotFound, $response);
    }
}
