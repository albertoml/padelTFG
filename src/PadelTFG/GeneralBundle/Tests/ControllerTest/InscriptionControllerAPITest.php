<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Inscription;

class InscriptionControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/InscriptionTest/InscriptionTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/InscriptionTest/InscriptionTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllInscriptionActionAPI()
    {
        $this->client->request('GET', '/api/inscription');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1PairTest', $response);
        $this->assertContains('User2Pair1PairTest', $response);
        $this->assertContains('User1Pair2PairTest', $response);
        $this->assertContains('User2Pair2PairTest', $response);
        $this->assertContains('User1Pair3PairTest', $response);
        $this->assertContains('User2Pair3PairTest', $response);
        $this->assertContains('User1Pair4PairTest', $response);
        $this->assertContains('User2Pair4PairTest', $response);
    }

    public function testGetInscriptionNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/inscription/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::InscriptionNotFound, $response);
    }

    public function testGetInscriptionActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair1PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);
    	$repository = $this->em->getRepository('GeneralBundle:Inscription');
        $inscription = $repository->findOneByPair($pair);

        $this->client->request('GET', '/api/inscription/' . $inscription->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1PairTest', $response);
        $this->assertContains('User2Pair1PairTest', $response);
        $this->assertContains('CategoryTournamentName', $response);
        $this->assertContains('Category Tournament', $response);
    }

    public function testGetInscriptionByTournamentActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName");

        $this->client->request('GET', '/api/inscription/tournament/' . $tournament->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1PairTest', $response);
        $this->assertContains('User2Pair1PairTest', $response);
        $this->assertContains('User1Pair2PairTest', $response);
        $this->assertContains('User2Pair2PairTest', $response);
        $this->assertContains('User1Pair3PairTest', $response);
        $this->assertContains('User2Pair3PairTest', $response);
        $this->assertContains('CategoryTournamentName', $response);
        $this->assertContains('Category Tournament', $response);
        $this->assertContains('Category Tournament1', $response);
    }

    public function testGetInscriptionByTournamentNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/inscription/tournament/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentNotFound, $response);
    }

    public function testGetInscriptionByCategoryActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Tournament1");

        $this->client->request('GET', '/api/inscription/category/' . $category->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair2PairTest', $response);
        $this->assertContains('User2Pair2PairTest', $response);
        $this->assertContains('User1Pair3PairTest', $response);
        $this->assertContains('User2Pair3PairTest', $response);
        $this->assertContains('CategoryTournamentName', $response);
        $this->assertContains('Category Tournament1', $response);
    }

    public function testGetInscriptionByCategoryNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/inscription/category/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::CategoryNotFound, $response);
    }

    public function testGetInscriptionByPairActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair2PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair = $repository->findOneByUser1($user1);

        $this->client->request('GET', '/api/inscription/pair/' . $pair->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair2PairTest', $response);
        $this->assertContains('User2Pair2PairTest', $response);
        $this->assertContains('CategoryTournamentName', $response);
        $this->assertContains('CategoryTournamentName1', $response);
        $this->assertContains('Category Tournament2', $response);
        $this->assertContains('Category Tournament1', $response);

    }

    public function testGetInscriptionByPairNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/inscription/pair/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::PairNotFound, $response);
    }

    public function testGetInscriptionByGroupActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:GroupCategory');
        $group = $repository->findOneByName('Group A');

        $this->client->request('GET', '/api/inscription/group/' . $group->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('User1Pair1PairTest', $response);
        $this->assertContains('User2Pair1PairTest', $response);
        $this->assertContains('CategoryTournamentName', $response);
        $this->assertContains('Category Tournament', $response);
    }

    public function testPostInscriptionActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Tournament");
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName");
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user2 = $repository->findOneByName('User1Pair2PairTest');
        $user3 = $repository->findOneByName('User1Pair3PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair2 = $repository->findOneByUser1($user2);
        $pair3 = $repository->findOneByUser1($user3);

        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"category":' . $category->getId() . ', "tournament":' . $tournament->getId() . ', "pair":[{"pairId":' .
        $pair2->getId() . ', "observations":[]}, {"pairId":' . $pair3->getId() . ', "observations":[]}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('2 ' . Literals::Inscriptions, $response);
    }

    public function testPostInscriptionEmptyRequiredFieldsActionAPI()
    {
        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = 'requiredFieldsNotFound';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::CategoryNotFound, $response);
    }

    public function testPostInscriptionEmptyContentActionAPI()
    {
        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::CategoryNotFound, $response);
    }

    public function testPostInscriptionCategoryNotFoundActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName");
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user2 = $repository->findOneByName('User1Pair2PairTest');
        $user3 = $repository->findOneByName('User1Pair3PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair2 = $repository->findOneByUser1($user2);
        $pair3 = $repository->findOneByUser1($user3);

        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"category":"Incorrect", "tournament":' . $tournament->getId() . ', "pair":[{"pairId":' .
        $pair2->getId() . ', "observations":[]}, {"pairId":' . $pair3->getId() . ', "observations":[]}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::CategoryNotFound, $response);
    }

    public function testPostInscriptionTournamentNotFoundActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Tournament");
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user2 = $repository->findOneByName('User1Pair2PairTest');
        $user3 = $repository->findOneByName('User1Pair3PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair2 = $repository->findOneByUser1($user2);
        $pair3 = $repository->findOneByUser1($user3);

        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"category":' . $category->getId() . ', "tournament":"Incorrect", "pair":[{"pairId":' .
        $pair2->getId() . ', "observations":[]}, {"pairId":' . $pair3->getId() . ', "observations":[]}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::TournamentNotFound, $response);
    }

    public function testPostInscriptionCategoryIncorrectActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Tournament2");
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName");
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user2 = $repository->findOneByName('User1Pair2PairTest');
        $user3 = $repository->findOneByName('User1Pair3PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair2 = $repository->findOneByUser1($user2);
        $pair3 = $repository->findOneByUser1($user3);

        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"category":' . $category->getId() . ', "tournament":' . $tournament->getId() . ', "pair":[{"pairId":' .
        $pair2->getId() . ', "observations":[]}, {"pairId":' . $pair3->getId() . ', "observations":[]}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::CategoryIncorrect, $response);
    }

    public function testPostInscriptionTwoPairsOnePairNotFoundActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Tournament");
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName");
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user3 = $repository->findOneByName('User1Pair3PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair3 = $repository->findOneByUser1($user3);

        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"category":' . $category->getId() . ', "tournament":' . $tournament->getId() .
         ', "pair":[{"pairId":0, "observations":[]}, {"pairId":' . $pair3->getId() . ', "observations":[]}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('1 ' . Literals::Inscriptions, $response);
    }

    public function testPostInscriptionTwoPairsOnePairDuplicatedActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Tournament2");
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName1");
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair1PairTest');
        $user4 = $repository->findOneByName('User1Pair4PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair1 = $repository->findOneByUser1($user1);
        $pair4 = $repository->findOneByUser1($user4);

        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"category":' . $category->getId() . ', "tournament":' . $tournament->getId() . ', "pair":[{"pairId":' . $pair1->getId() . ', "observations":[]}, {"pairId":' . $pair4->getId() . ', "observations":[]}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains($pair4->getId() . ' ' . Literals::PairDuplicate, $response);
        $this->assertContains('1 ' . Literals::Inscriptions, $response);
    }

    public function testPostInscriptionWithObservationsActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Tournament");
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName");
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user3 = $repository->findOneByName('User1Pair3PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair3 = $repository->findOneByUser1($user3);

        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"category":' . $category->getId() . ', "tournament":' . $tournament->getId() . ', "pair":[{"pairId":' .
         $pair3->getId() . ', "observations":[{"date":"15-10-2015 10:00", "fromHour":"10", "toHour":"12"}' . 
         ', {"date":"15-10-2015 10:00", "fromHour":"10", "toHour":"12"}]}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('1 ' . Literals::Inscriptions, $response);
    }

    public function testPostInscriptionWithObservationsEmptyRequiredFieldsActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Tournament");
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName");
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user3 = $repository->findOneByName('User1Pair3PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair3 = $repository->findOneByUser1($user3);

        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"category":' . $category->getId() . ', "tournament":' . $tournament->getId() . ', "pair":[{"pairId":' .
         $pair3->getId() . ', "observations":[{"fieldIncorrect":"15-10-2015 10:00", "fieldIncorrect":"15-10-2015 12:00", "fieldIncorrect":"no"}]}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value is not a valid datetime.', $response);
        $this->assertContains('1 ' . Literals::Inscriptions, $response);
    }

    public function testPostInscriptionRegisteredLimitTournamentExceptionActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Tournament2");
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName1");
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair1PairTest');
        $user3 = $repository->findOneByName('User1Pair3PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair1 = $repository->findOneByUser1($user1);
        $pair3 = $repository->findOneByUser1($user3);

        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"category":' . $category->getId() . ', "tournament":' . $tournament->getId() . ', "pair":[{"pairId":' . $pair1->getId() . ', "observations":[]}, 
        {"pairId":' . $pair3->getId() . ', "observations":[]}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('1 ' . Literals::Inscriptions, $response);
        $this->assertContains($tournament->getId() . ' ' . Literals::TournamentInscriptionLimit, $response);
    }

    public function testPostInscriptionRegisteredLimitMaxCategoryExceptionActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Category');
        $category = $repository->findOneByName("Category Tournament1");
        $repository = $this->em->getRepository('GeneralBundle:Tournament');
        $tournament = $repository->findOneByName("CategoryTournamentName");
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user1 = $repository->findOneByName('User1Pair4PairTest');
        $repository = $this->em->getRepository('GeneralBundle:Pair');
        $pair1 = $repository->findOneByUser1($user1);

        $method = 'POST';
        $uri = '/api/inscription';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '{"category":' . $category->getId() . ', "tournament":' . $tournament->getId() . ', "pair":[{"pairId":' .
        $pair1->getId() . ', "observations":[]}]}';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains($pair1->getId() . ' ' . Literals::CategoryInscriptionLimitMax, $response);
    }
}
