<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Game;
use PadelTFG\GeneralBundle\Entity\Tournament;

class GameControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/GameTest/GameTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/GameTest/GameTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllGameActionAPI()
    {
        $this->client->request('GET', '/api/game');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Game Test 1', $response);
        $this->assertContains('Game Test 2', $response);
        $this->assertContains('Game Test 3', $response);      
        $this->assertContains('Game Test 4', $response);      
    }

    public function testGetGameNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/game/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::GameNotFound, $response);
    }

    public function testGetGameActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Game');
        $game = $repository->findOneByDescription("Game Test 1");

        $this->client->request('GET', '/api/game/' . $game->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Game Test 1', $response);
        $this->assertContains('User1Pair1', $response);
        $this->assertContains('User2Pair1', $response);
        $this->assertContains('User1Pair2', $response);
        $this->assertContains('User2Pair2', $response);
        $this->assertContains('Group A Test', $response);
        $this->assertContains('"score":"6\/0 - 6\/0"', $response);
        $this->assertContains('TournamentName', $response);
        $this->assertContains('Category Test', $response);
    }

    public function testGetGameByTournamentActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("TournamentName");

        $this->client->request('GET', '/api/game/tournament/' . $tournament->getId() . '/false');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Game Test 1', $response);
        $this->assertContains('Game Test 2', $response);
        $this->assertContains('Game Test 3', $response);
        $this->assertContains('Game Test 4', $response);
    }

    public function testGetGameDrawssByTournamentActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("TournamentName");

        $this->client->request('GET', '/api/game/tournament/' . $tournament->getId() . '/true');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetGameByTournamentNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/game/tournament/0/true');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentNotFound, $response);
    }

    public function testGetGameByCategoryActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Test");

        $this->client->request('GET', '/api/game/category/' . $category->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Game Test 1', $response);
        $this->assertContains('Game Test 2', $response);
        $this->assertContains('Game Test 3', $response);
        $this->assertContains('Game Test 4', $response);
    }

    public function testGetGameByCategoryNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/game/category/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::CategoryNotFound, $response);
    }

    public function testGetGameByGroupActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->findOneByName("Group A Test");

        $this->client->request('GET', '/api/game/group/' . $group->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Game Test 1', $response);
        $this->assertNotContains('Game Test 2', $response);
        $this->assertNotContains('Game Test 3', $response);
        $this->assertNotContains('Game Test 4', $response);
    }

    public function testGetGameByGroupNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/game/group/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::GroupNotFound, $response);
    }

    public function testGetGameByUserActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByName("User1Pair1");

        $this->client->request('GET', '/api/game/user/' . $user->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Game Test 1', $response);
        $this->assertNotContains('6/0 - 6/0', $response);
    }

    public function testGetGameByUserNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/game/user/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }

    public function testPostGameEmptyRequiredFieldsActionAPI()
    {
        $method = 'POST';
        $uri = '/api/game';
        $parameters = array();
        $files = array();
        $server = array();
        $content = 'requiredFieldsNotFound';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::CreatedGameStatus, $response);
        $this->assertContains('"tournament":null', $response);
        $this->assertContains('"group":null', $response);
        $this->assertContains('"category":null', $response);
        $this->assertContains('"pair1":null', $response);
        $this->assertContains('"pair2":null', $response);
        $this->assertContains('"description":""', $response);
        $this->assertContains('"startDate":null', $response);
        $this->assertContains('"endDate":null', $response);
        $this->assertContains('"score":""', $response);
    }

    public function testPostGameEmptyContentActionAPI()
    {
        $method = 'POST';
        $uri = '/api/game';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::CreatedGameStatus, $response);
        $this->assertContains('"tournament":null', $response);
        $this->assertContains('"group":null', $response);
        $this->assertContains('"category":null', $response);
        $this->assertContains('"pair1":null', $response);
        $this->assertContains('"pair2":null', $response);
        $this->assertContains('"description":""', $response);
        $this->assertContains('"startDate":null', $response);
        $this->assertContains('"endDate":null', $response);
        $this->assertContains('"score":""', $response);
    }

    

    public function testPostGameActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->findOneByName("Group A Test");

        $method = 'POST';
        $uri = '/api/game';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"score":"6/1 - 6/1","description":"Game POST","tournament":' . $group->getTournament()->getId() . ', "category":' . $group->getCategory()->getId() . ',"group":' . $group->getId() . '}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"tournament":' . $group->getTournament()->getId(), $response);
        $this->assertContains('"category":' . $group->getCategory()->getId(), $response);
        $this->assertContains('"group":{"id":' . $group->getId(), $response);
        $this->assertContains('"description":"Game POST"', $response);
        $this->assertContains('"score":"6\/1 - 6\/1"', $response);
    }
 
    public function testPostGameTournamentNotCorrectActionAPI()
    {

        $method = 'POST';
        $uri = '/api/game/tournament';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"tournamentId":"notCorrectId"}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentIdNotCorrect, $response);
    } 

    public function testPostGameTournamentEmptyContentActionAPI()
    {

        $method = 'POST';
        $uri = '/api/game/tournament';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentIdNotCorrect, $response);
    }

    public function testPostGroupTournamentActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("TournamentName1");

        $method = 'POST';
        $uri = '/api/game/tournament';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"tournamentId":' . $tournament->getId() . '}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Category Test1', $response);
        $this->assertContains('Group C Test', $response);
        $this->assertContains('User1Pair6', $response);
        $this->assertContains('User2Pair6', $response);
        $this->assertContains('User1Pair7', $response);
        $this->assertContains('User2Pair7', $response);
        $this->assertContains('User1Pair8', $response);
        $this->assertContains('User2Pair8', $response);        
        $this->assertContains('User1Pair9', $response);
        $this->assertContains('User2Pair9', $response);        
        $this->assertContains('User1Pair10', $response);
        $this->assertContains('User2Pair10', $response);    
    }

    public function testPutGameActionAPI()
    {

        $repository = $this->em->getRepository('GeneralBundle:Game');
        $game = $repository->findOneByDescription("Game Test 1");

        $method = 'PUT';
        $uri = '/api/game/' . $game->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'score' => '6/3 - 6/3'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"score":"6\/3 - 6\/3"', $response);
        $this->assertContains("Game Test 1", $response);
    }

    public function testPutNotFoundGameActionAPI()
    {
        $method = 'PUT';
        $uri = '/api/game/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'score' => '6/3 - 6/3'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::GameNotFound, $response);
    }

    public function testPutEmptyContentGameActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Game');
        $game = $repository->findOneByDescription("Game Test 1");

        $method = 'PUT';
        $uri = '/api/game/' . $game->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Game Test 1', $response);
        $this->assertContains('6\/0 - 6\/0', $response);
    }

    public function testDeleteGameActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Game');
        $game = $repository->findOneByDescription("Game DELETED");

        $method = 'DELETE';
        $uri = '/api/game/' . $game->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::GameDeleted, $response);
    }

    public function testDeleteNotFoundGameActionAPI()
    {
        $method = 'DELETE';
        $uri = '/api/game/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = "";

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::GameNotFound, $response);
    }
}
