<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Schedule;

class ScheduleControllerAPITest extends WebTestCase
{
	private $em;
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
        $loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/ScheduleTest/ScheduleTestInsert.php');
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
        $loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/ScheduleTest/ScheduleTestRemove.php');
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures(), true);

        $this->em->close();
    }

    public function testGetScheduleNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/schedule/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::ScheduleNotFound, $response);
    }

    public function testGetTournamentActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Schedule');
        $schedule = $repository->findOneByScheduleJson("Schedule TFG");

        $this->client->request('GET', '/api/schedule/' . $schedule->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Schedule TFG', $response);
    }

    public function testPostScheduleActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/schedule';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'scheduleResourcesJson' => "7",
            'scheduleJson' => 'Schedule POST'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Schedule POST', $response);
        $this->assertContains('7', $response);
    }
}
