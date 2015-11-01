<?php

namespace PadelTFG\GeneralBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;

class CategoryControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/CategoryTest/CategoryTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/CategoryTest/CategoryTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllCategoryActionAPI()
    {
        $this->client->request('GET', '/api/category');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Category TFG', $response);
        $this->assertContains('Category TFG2', $response);
        $this->assertContains('Category TFG3', $response);
        $this->assertContains('Category TFG4', $response);
        $this->assertContains('Category TFG5', $response);
    }

    public function testGetCategoryNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/category/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::CategoryNotFound, $response);
    }

    public function testGetCategoryActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category TFG");

        $this->client->request('GET', '/api/category/' . $category->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Category TFG', $response);
    }

    public function testGetCategoryByTournamentActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName");

        $this->client->request('GET', '/api/category/tournament/' . $tournament->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Category Tournament', $response);
        $this->assertContains('Category Tournament1', $response);
    }
}
