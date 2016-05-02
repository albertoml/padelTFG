<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Tournament;

class TournamentControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/TournamentTest/TournamentTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/TournamentTest/TournamentTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllTournamentActionAPI()
    {
        $this->client->request('GET', '/api/tournament');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Torneo TFG', $response);
        $this->assertContains('Torneo TFG2', $response);
        $this->assertContains('Torneo TFG3', $response);
        $this->assertContains('Torneo TFG4', $response);
        $this->assertContains('Torneo TFG5', $response);
    }

    public function testGetTournamentNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/tournament/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentNotFound, $response);
    }

    public function testGetTournamentActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("Torneo TFG");

        $this->client->request('GET', '/api/tournament/' . $tournament->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Torneo TFG', $response);
    }

    public function testPostTournamentActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/tournament';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'admin' => "emailTournamentTest",
            'name' => 'Torneo POST',
            'creationDate' => "15-06-2015"
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Torneo POST', $response);
        $this->assertContains('emailTournamentTest', $response);
    }

    public function testPostAdminIncorrectTournamentActionAPI()
    {
        $method = 'POST';
        $uri = '/api/tournament';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'admin' => "notExists",
            'name' => 'Torneo POST'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }


    public function testPostEmptyRequiredFieldsTournamentActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/tournament';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'registeredLimit' => 34
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }

    public function testPostAdminNotCorrectTournamentActionAPI()
    {
        $method = 'POST';
        $uri = '/api/tournament';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'admin' => 'notcorrectEmail'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }

    public function testPostEmptyContentTournamentActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/tournament';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }

    public function testPutTournamentActionAPI()
    {

    	$repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("Torneo TFG");

    	$method = 'PUT';
        $uri = '/api/tournament/' . $tournament->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'registeredLimit' => 17
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"registeredLimit":17', $response);
        $this->assertContains("Torneo TFG", $response);
    }

    public function testPutNotFoundTournamentActionAPI()
    {
    	$method = 'PUT';
        $uri = '/api/tournament/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'registeredLimit' => 17
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentNotFound, $response);
    }

    public function testPutEmptyContentTournamentActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("Torneo TFG");

    	$method = 'PUT';
        $uri = '/api/tournament/' . $tournament->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Torneo TFG', $response);
    }

    public function testDeleteTournamentActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("Torneo TFGDELETE");

    	$method = 'DELETE';
        $uri = '/api/tournament/' . $tournament->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentDeleted, $response);
    }

    public function testDeleteNotFoundTournamentActionAPI()
    {
    	$method = 'DELETE';
        $uri = '/api/tournament/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = "";

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentNotFound, $response);
    }
}
