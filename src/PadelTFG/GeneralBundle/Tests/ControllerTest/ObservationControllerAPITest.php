<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Observation;
use PadelTFG\GeneralBundle\Entity\Inscription;

class ObservationControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/ObservationTest/ObservationTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/ObservationTest/ObservationTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllObservationActionAPI()
    {
        $this->client->request('GET', '/api/observation');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1ObservationTest', $response);
        $this->assertContains('User1Pair2ObservationTest', $response);
        $this->assertContains('Category Observation', $response);
        $this->assertContains('ObservationTournamentName', $response);
    }

    public function testGetObservationNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/observation/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::ObservationNotFound, $response);
    }

    public function testGetObservationActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair1ObservationTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);
    	$repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->findOneByPair($pair);
        $repository = $this->em->getRepository('GeneralBundle:Observation');
        $observation = $repository->findOneByInscription($inscription->getId());

        $this->client->request('GET', '/api/observation/' . $observation->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1ObservationTest', $response);
        $this->assertContains('User2Pair1ObservationTest', $response);
        $this->assertContains('ObservationTournamentName', $response);
        $this->assertContains('Category Observation', $response);
    }

    public function testGetObservationByInscriptionActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair1ObservationTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->findOneByPair($pair);

        $this->client->request('GET', '/api/observation/inscription/' . $inscription->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1ObservationTest', $response);
        $this->assertContains('User2Pair1ObservationTest', $response);
        $this->assertContains('ObservationTournamentName', $response);
        $this->assertContains('Category Observation', $response);
    }

    public function testGetObservationByInscriptionNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/observation/inscription/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::InscriptionNotFound, $response);
    }

    public function testDeleteObservationActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair1ObservationTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->findOneByPair($pair);
        $repository = $this->em->getRepository('GeneralBundle:Observation');
        $observation = $repository->findOneByInscription($inscription->getId());

        $method = 'DELETE';
        $uri = '/api/observation/' . $observation->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::ObservationDeleted, $response);
    }

    public function testDeleteNotFoundObservationActionAPI()
    {
        $method = 'DELETE';
        $uri = '/api/observation/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = "";

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::ObservationNotFound, $response);
    }

    public function testPostObservationActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair3ObservationTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->findOneByPair($pair);

        $date = new \DateTime();
        $dateString = $date->format('d/m/Y');

        $method = 'POST';
        $uri = '/api/observation';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'observation' => array('date' => $dateString,
                            'fromHour' => '10',
                            'toHour' => '15'),
            'inscription' => $inscription->getId()
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"observations":"Ok"', $response);
    }

    public function testPostObservationIncorrectActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair3ObservationTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->findOneByPair($pair);

        $method = 'POST';
        $uri = '/api/observation';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'observation' => array('date' => '15/03/2001',
                            'fromHour' => '10',
                            'toHour' => '15'),
            'inscription' => $inscription->getId()
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value should be greater than', $response);
    }

    public function testPostObservationBadFormatActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair3ObservationTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->findOneByPair($pair);

        $method = 'POST';
        $uri = '/api/observation';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'date' => '15/03/2001',
            'fromHour' => '10',
            'toHour' => '15'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::ObservationBadFormat, $response);
    }

    public function testPostObservationAllActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair3ObservationTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->findOneByPair($pair);

        $date = new \DateTime();
        $dateString = $date->format('d/m/Y');

        $method = 'POST';
        $uri = '/api/observation/all';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'observations' => array('observation' => array('date' => $dateString,
                                                        'fromHour' => '10',
                                                        'toHour' => '15')),
            'inscription' => $inscription->getId()
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"observations":""', $response);
    }

    public function testPostObservationAllIncorrectActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair3ObservationTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->findOneByPair($pair);

        $method = 'POST';
        $uri = '/api/observation/all';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'observations' => array('observation' => array('date' => '15/03/2001',
                                                        'fromHour' => '10',
                                                        'toHour' => '15')),
            'inscription' => $inscription->getId()
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value should be greater than', $response);
    }

    public function testPostObservationAllBadFormatActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair3ObservationTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);
        $repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->findOneByPair($pair);

        $method = 'POST';
        $uri = '/api/observation/all';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'date' => '15/03/2001',
            'fromHour' => '10',
            'toHour' => '15'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::ObservationBadFormat, $response);
    }
}
