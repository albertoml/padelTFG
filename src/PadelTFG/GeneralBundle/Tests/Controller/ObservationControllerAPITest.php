<?php

namespace PadelTFG\GeneralBundle\Tests\Controller;

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
        $this->assertContains('"available":false', $response);
        $this->assertContains('"available":true', $response);
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
        $this->assertContains('"available":true', $response);
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
        
        //$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1ObservationTest', $response);
        $this->assertContains('User2Pair1ObservationTest', $response);
        $this->assertContains('"available":true', $response);
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
}
