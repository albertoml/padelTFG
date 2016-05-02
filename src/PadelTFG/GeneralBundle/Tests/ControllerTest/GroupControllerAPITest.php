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
use PadelTFG\GeneralBundle\Entity\Tournament;

class GroupControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/GroupTest/GroupTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/GroupTest/GroupTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllGroupActionAPI()
    {
        $this->client->request('GET', '/api/group');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Group A Test', $response);
        $this->assertContains('Group B Test', $response);
        $this->assertContains('Group C Test', $response);      
    }

    public function testGetGroupNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/group/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::GroupNotFound, $response);
    }

    public function testGetGroupActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->findOneByName("Group A Test");

        $this->client->request('GET', '/api/group/' . $group->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Group A Test', $response);
    }

    public function testGetGroupByTournamentActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("TournamentName");

        $this->client->request('GET', '/api/group/tournament/' . $tournament->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Group A Test', $response);
        $this->assertContains('Group B Test', $response);
        $this->assertContains('Group C Test', $response);
    }

    public function testGetGroupByTournamentNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/group/tournament/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentNotFound, $response);
    }

    public function testGetGroupByCategoryActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Test");

        $this->client->request('GET', '/api/group/category/' . $category->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Group A Test', $response);
        $this->assertContains('Group B Test', $response);
        $this->assertContains('Group C Test', $response);
    }

    public function testGetGroupByCategoryNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/group/category/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::CategoryNotFound, $response);
    }

    public function testPostGroupEmptyRequiredFieldsActionAPI()
    {
        $method = 'POST';
        $uri = '/api/group';
        $parameters = array();
        $files = array();
        $server = array();
        $content = 'requiredFieldsNotFound';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('No name', $response);
        $this->assertContains('"tournament":null', $response);
        $this->assertContains('"category":null', $response);
    }

    public function testPostGroupEmptyContentActionAPI()
    {
        $method = 'POST';
        $uri = '/api/group';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('No name', $response);
        $this->assertContains('"tournament":null', $response);
        $this->assertContains('"category":null', $response);
    }

    public function testPostGroupToNextGroupActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->findOneByName("Group A Test");

        $method = 'POST';
        $uri = '/api/group';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"nextGroupTo":' . $group->getId() . '}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::NewGroupLabel, $response);
        $this->assertContains('"tournament":' . $group->getTournament()->getId(), $response);
        $this->assertContains('"category":' . $group->getCategory()->getId(), $response);
        $this->assertContains('"numPairs":0', $response);
    }

    public function testPostGroupActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->findOneByName("Group A Test");

        $method = 'POST';
        $uri = '/api/group';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"name":"New Group Test","numPairs":0,"tournament":' . $group->getTournament()->getId() . ', "category":' . $group->getCategory()->getId() . '}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::NewGroupLabel, $response);
        $this->assertContains('"tournament":' . $group->getTournament()->getId(), $response);
        $this->assertContains('"category":' . $group->getCategory()->getId(), $response);
        $this->assertContains('"numPairs":0', $response);
    } 

    public function testPostGroupTournamentActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->findOneByName("Group A Test");

        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByEmail("User1Pair1");
        $user2 = $repository->findOneByEmail("User1Pair2");
        $user3 = $repository->findOneByEmail("User1Pair3");
        $user4 = $repository->findOneByEmail("User1Pair4");
        $user5 = $repository->findOneByEmail("User1Pair5");
        $user6 = $repository->findOneByEmail("User1Pair6");
        $user7 = $repository->findOneByEmail("User1Pair7");
        $user8 = $repository->findOneByEmail("User1Pair8");
        $user9 = $repository->findOneByEmail("User1Pair9");
        $user10 = $repository->findOneByEmail("User1Pair10");

        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair1 = $repository->findOneByUser1($user1);
        $pair2 = $repository->findOneByUser1($user2);
        $pair3 = $repository->findOneByUser1($user3);
        $pair4 = $repository->findOneByUser1($user4);
        $pair5 = $repository->findOneByUser1($user5);
        $pair6 = $repository->findOneByUser1($user6);
        $pair7 = $repository->findOneByUser1($user7);
        $pair8 = $repository->findOneByUser1($user8);
        $pair9 = $repository->findOneByUser1($user9);
        $pair10 = $repository->findOneByUser1($user10);

        $method = 'POST';
        $uri = '/api/group/tournament';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"' . $group->getCategory()->getId() . '":[{"name":"Group A TestP","category":' . $group->getCategory()->getId() . ',"tournament":' . $group->getTournament()->getId() . ',"numPairs":5,"pairs":[' . $pair1->getId() . ',' . $pair2->getId() . ',' . $pair3->getId() . ',' . $pair4->getId() . ',' . $pair5->getId() . '],"groupId":' . $group->getId() . '},{"name":"Group B TestP","category":' . $group->getCategory()->getId() . ',"tournament":' . $group->getTournament()->getId() . ',"numPairs":5,"pairs":[' . $pair6->getId() . ',' . $pair7->getId() . ',' . $pair8->getId() . ',' . $pair9->getId() . ',' . $pair10->getId() . '],"groupId":"newGroup"}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Group A TestP', $response);
        $this->assertContains('Group B TestP', $response);
        $this->assertContains('"numPairs":5', $response);
    }   
}
