<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Notification;

class NotificationControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/NotificationTest/NotificationTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/NotificationTest/NotificationTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllNotificationActionAPI()
    {
        $this->client->request('GET', '/api/notification');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Notification TFG', $response);
        $this->assertContains('Notification TFG2', $response);
        $this->assertContains('Notification TFG3', $response);
        $this->assertContains('Notification TFG4', $response);
        $this->assertContains('Notification TFG5', $response);
    }

    public function testGetNotificationNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/notification/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::NotificationNotFound, $response);
    }

    public function testGetNotificationActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Notification');
        $notification = $repository->findOneByText("Notification TFG");

        $this->client->request('GET', '/api/notification/' . $notification->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Notification TFG', $response);
    }

    public function testPostNotificationActionAPI()
    {
        $repositoryTournament = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repositoryTournament->findOneByName('TournamentNotificationTest');

        $date = new \DateTime();
        $date->modify('+1 day');
        $notificationDate = $date->format('d/m/Y');

    	$method = 'POST';
        $uri = '/api/notification';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => "Notification POST",
            'tournament' => $tournament->getId(),
            'notificationDate' => $notificationDate
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertContains('Notification POST', $response);
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('TournamentNotificationTest', $response);
    }

    public function testPostTournamentIncorrectNotificationActionAPI()
    {
        $date = new \DateTime();
        $date->modify('+1 day');
        $notificationDate = $date->format('d/m/Y');

        $method = 'POST';
        $uri = '/api/notification';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => "Notification POST",
            'tournament' => "tournamentIncorrect",
            'notificationDate' => $notificationDate
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentNotFound, $response);
    }


    public function testPostEmptyRequiredFieldsNotificationActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/notification';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'notFieldRequired' => 34
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentNotFound, $response);
    }

    public function testPostEmptyContentNotificationActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/notification';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentNotFound, $response);
    }

    public function testPostNotificationIncorrectNotificationDateActionAPI()
    {
        $date = new \DateTime();
        $date->modify('-1 day');
        $notificationDate = $date->format('d/m/Y');

        $repositoryTournament = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repositoryTournament->findOneByName('TournamentNotificationTest');

        $method = 'POST';
        $uri = '/api/notification';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => "Notification POST",
            'tournament' => $tournament->getId(),
            'notificationDate' => $notificationDate
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Notification).notificationDate:', $response);
        $this->assertContains('This value should be greater than', $response);
    }

    public function testPostNotificationIncorrectTextActionAPI()
    {
        $date = new \DateTime();
        $notificationDate = $date->format('d/m/Y');

        $repositoryTournament = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repositoryTournament->findOneByName('TournamentNotificationTest');

        $method = 'POST';
        $uri = '/api/notification';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => '',
            'tournament' => $tournament->getId(),
            'notificationDate' => $notificationDate
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Notification).text:', $response);
        $this->assertContains('This value should not be blank.', $response);
    }

    public function testPutNotificationActionAPI()
    {

    	$repository = $this->em->getRepository('GeneralBundle:Notification');
        $notification = $repository->findOneByText("Notification TFG");
        $method = 'PUT';
        $uri = '/api/notification/' . $notification->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => 'Modify Notification'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"text":"Modify Notification"', $response);
    }

    public function testPutNotificationIncorrectNotificationDateActionAPI()
    {
        $date = new \DateTime();
        $date->modify('-1 day');
        $notificationDate = $date->format('d/m/Y');

        $repository = $this->em->getRepository('GeneralBundle:Notification');
        $notification = $repository->findOneByText("Notification TFG");

        $method = 'PUT';
        $uri = '/api/notification/' . $notification->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'notificationDate' => $notificationDate
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Notification).notificationDate:', $response);
        $this->assertContains('This value should be greater than', $response);
    }

    public function testPutNotFoundNotificationActionAPI()
    {
    	$method = 'PUT';
        $uri = '/api/notification/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => 'Modify Notification'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::NotificationNotFound, $response);
    }

    public function testPutEmptyContentNotificationActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Notification');
        $notification = $repository->findOneByText("Notification TFG");

    	$method = 'PUT';
        $uri = '/api/notification/' . $notification->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Notification TFG', $response);
    }

    public function testDeleteNotificationActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Notification');
        $notification = $repository->findOneByText("Notification TFGDELETE");

    	$method = 'DELETE';
        $uri = '/api/notification/' . $notification->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::NotificationDeleted, $response);
    }

    public function testDeleteNotFoundNotificationActionAPI()
    {
    	$method = 'DELETE';
        $uri = '/api/notification/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = "";

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::NotificationNotFound, $response);
    }
}
