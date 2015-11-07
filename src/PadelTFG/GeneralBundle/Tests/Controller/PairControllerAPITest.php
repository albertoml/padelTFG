<?php

namespace PadelTFG\GeneralBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Tournament;

class PairControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/PairTest/PairTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/PairTest/PairTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllPairActionAPI()
    {
        $this->client->request('GET', '/api/pair');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1PairTest', $response);
        $this->assertContains('User2Pair1PairTest', $response);
        $this->assertContains('User1Pair2PairTest', $response);
        $this->assertContains('User2Pair2PairTest', $response);
        $this->assertContains('User1Pair3PairTest', $response);
        $this->assertContains('User2Pair3PairTest', $response);
    }

    public function testGetPairNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/pair/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::PairNotFound, $response);
    }

    public function testGetPairActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByName("User1Pair1PairTest");
    	$repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user->getId());

        $this->client->request('GET', '/api/pair/' . $pair->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1PairTest', $response);
        $this->assertContains('User2Pair1PairTest', $response);
    }

    public function testGetPairByUserActionApi()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByName("User1Pair1PairTest");

        $this->client->request('GET', '/api/pair/user/' . $user->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1PairTest', $response);
        $this->assertContains('User2Pair1PairTest', $response);
    }

    public function testGetPairByUserNotFoundActionApi()
    {
        $this->client->request('GET', '/api/pair/user/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }

    public function testGetPairByUsersActionApi()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByName("User1Pair1PairTest");
        $user2 = $repository->findOneByName("User2Pair1PairTest");

        $this->client->request('GET', '/api/pair/user/' . $user->getId() . '/' . $user2->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1PairTest', $response);
        $this->assertContains('User2Pair1PairTest', $response);
    }

    public function testGetPairByUsersEmptyResultActionApi()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByName("User1Pair1PairTest");
        $user2 = $repository->findOneByName("User2Pair2PairTest");

        $this->client->request('GET', '/api/pair/user/' . $user->getId() . '/' . $user2->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('[]', $response);
    }

    public function testGetPairByUsersUser2NotFoundActionApi()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByName("User1Pair1PairTest");

        $this->client->request('GET', '/api/pair/user/' . $user->getId() . '/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }

    public function testGetPairByUsersUser1NotFoundActionApi()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByName("User2Pair1PairTest");

        $this->client->request('GET', '/api/pair/user/0/' . $user->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::UserNotFound, $response);
    }
}
