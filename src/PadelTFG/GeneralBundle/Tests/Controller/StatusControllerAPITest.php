<?php

namespace PadelTFG\GeneralBundle\Tests\Controller;

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
        $this->assertContains('registered', $response);
        $this->assertContains('in tournament', $response);
        $this->assertContains('tournament admin', $response);
        $this->assertContains('deleted', $response);
    }

    public function testStatusTournamentActionAPI()
    {
        $this->client->request('GET', '/api/status/tournament');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('created', $response);
        $this->assertContains('in inscription date', $response);
        $this->assertContains('in group date', $response);
        $this->assertContains('in finals date', $response);
        $this->assertContains('finished', $response);
        $this->assertContains('deleted', $response);
    }

    public function testStatusSponsorActionAPI()
    {
        $this->client->request('GET', '/api/status/sponsor');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('active', $response);
        $this->assertContains('defaulter', $response);
        $this->assertContains('hidden', $response);
        $this->assertContains('deleted', $response);
    }

    public function testStatusRecordalActionAPI()
    {
        $this->client->request('GET', '/api/status/recordal');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('created', $response);
        $this->assertContains('read', $response);
        $this->assertContains('hidden', $response);
        $this->assertContains('deleted', $response);
    }

    public function testStatusNotificationActionAPI()
    {
        $this->client->request('GET', '/api/status/notification');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('created', $response);
        $this->assertContains('sent', $response);
        $this->assertContains('read', $response);
        $this->assertContains('hidden', $response);
        $this->assertContains('deleted', $response);
    }

    public function testStatusGameActionAPI()
    {
        $this->client->request('GET', '/api/status/game');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('created', $response);
        $this->assertContains('in process to change', $response);
        $this->assertContains('pending', $response);
        $this->assertContains('played', $response);
        $this->assertContains('won', $response);
        $this->assertContains('losed', $response);
    }

    public function testStatusAnnotationActionAPI()
    {
        $this->client->request('GET', '/api/status/annotation');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('created', $response);
        $this->assertContains('read', $response);
        $this->assertContains('hidden', $response);
        $this->assertContains('deleted', $response);
    }
}
