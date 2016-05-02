<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

class StatusControllerAPITest extends WebTestCase
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
		$this->em->close();
    }

    public function testStatusNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/status/notFound');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::StatusNotFound, $response);
    }

    public function testStatusUserActionAPI()
    {
        $this->client->request('GET', '/api/status/user');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Registered', $response);
        $this->assertContains('In Tournament', $response);
        $this->assertContains('Tournament Admin', $response);
        $this->assertContains('Deleted', $response);
    }

    public function testStatusTournamentActionAPI()
    {
        $this->client->request('GET', '/api/status/tournament');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Created', $response);
        $this->assertContains('In Inscription Date', $response);
        $this->assertContains('In Group Date', $response);
        $this->assertContains('In Finals Date', $response);
        $this->assertContains('Finished', $response);
        $this->assertContains('Deleted', $response);
    }

    public function testStatusSponsorActionAPI()
    {
        $this->client->request('GET', '/api/status/sponsor');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Active', $response);
        $this->assertContains('Defaulter', $response);
        $this->assertContains('Hidden', $response);
        $this->assertContains('Deleted', $response);
    }

    public function testStatusRecordalActionAPI()
    {
        $this->client->request('GET', '/api/status/recordal');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Created', $response);
        $this->assertContains('Read', $response);
        $this->assertContains('Hidden', $response);
        $this->assertContains('Deleted', $response);
    }

    public function testStatusNotificationActionAPI()
    {
        $this->client->request('GET', '/api/status/notification');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Created', $response);
        $this->assertContains('Sent', $response);
        $this->assertContains('Read', $response);
        $this->assertContains('Hidden', $response);
        $this->assertContains('Deleted', $response);
    }

    public function testStatusGameActionAPI()
    {
        $this->client->request('GET', '/api/status/game');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Created', $response);
        $this->assertContains('In Process To Change', $response);
        $this->assertContains('Canceled', $response);
        $this->assertContains('Won Pair 1', $response);
        $this->assertContains('Won Pair 2', $response);
    }

    public function testStatusAnnotationActionAPI()
    {
        $this->client->request('GET', '/api/status/annotation');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Created', $response);
        $this->assertContains('Read', $response);
        $this->assertContains('Hidden', $response);
        $this->assertContains('Deleted', $response);
    }

    public function testStatusInscriptionActionAPI()
    {
        $this->client->request('GET', '/api/status/inscription');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Tournament Not Started', $response);
        $this->assertContains('Not Classified', $response);
        $this->assertContains('Classified', $response);
        $this->assertContains('Finished', $response);
    }
}
