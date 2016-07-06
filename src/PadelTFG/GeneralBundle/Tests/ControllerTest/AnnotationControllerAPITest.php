<?php

namespace PadelTFG\GeneralBundle\Tests\ControllerTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader as Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Annotation;
use PadelTFG\GeneralBundle\Entity\User;

class AnnotationControllerAPITest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/AnnotationTest/AnnotationTestInsert.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);
    }

    protected function tearDown()
    {
        $loader = new Loader;
		$loader->loadFromFile('src/PadelTFG/GeneralBundle/DataFixtures/ORM/AnnotationTest/AnnotationTestRemove.php');
		$purger = new ORMPurger();
		$executor = new ORMExecutor($this->em, $purger);
		$executor->execute($loader->getFixtures(), true);

		$this->em->close();
    }

    public function testAllAnnotationActionAPI()
    {
        $this->client->request('GET', '/api/annotation');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Annotation TFG', $response);
        $this->assertContains('Annotation TFG2', $response);
        $this->assertContains('Annotation TFG3', $response);
        $this->assertContains('Annotation TFG4', $response);
        $this->assertContains('Annotation TFG5', $response);
    }

    public function testGetAnnotationNotFoundActionAPI()
    {
        $this->client->request('GET', '/api/annotation/0');
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::AnnotationNotFound, $response);
    }

    public function testGetAnnotationActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Annotation');
        $annotation = $repository->findOneByText("Annotation TFG");

        $this->client->request('GET', '/api/annotation/' . $annotation->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Annotation TFG', $response);
    }

    public function testGetAnnotationByUserActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByName("AnnotationManager");

        $this->client->request('GET', '/api/annotation/user/' . $user->getId());
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Annotation TFG', $response);
        $this->assertContains('Annotation TFG2', $response);
    }

    public function testPostAnnotationActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/annotation';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => "Annotation POST"
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Annotation POST', $response);
    }

    public function testPostEmptyRequiredFieldsAnnotationActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/annotation';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'notFieldRequired' => 34
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $expectedError = Literals::TextEmpty;
        $this->assertContains('Annotation).text:', $response);
        $this->assertContains('This value should not be blank.', $response);
    }

    public function testPostEmptyContentAnnotationActionAPI()
    {
    	$method = 'POST';
        $uri = '/api/annotation';
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Annotation).text:', $response);
        $this->assertContains('This value should not be blank.', $response);
    }

    public function testPutAnnotationActionAPI()
    {

    	$repository = $this->em->getRepository('GeneralBundle:Annotation');
        $annotation = $repository->findOneByText("Annotation TFG");
    	$method = 'PUT';
        $uri = '/api/annotation/' . $annotation->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => 'Modify Annotation'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('"text":"Modify Annotation"', $response);
    }

    public function testPutNotFoundAnnotationActionAPI()
    {
    	$method = 'PUT';
        $uri = '/api/annotation/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => 'Modify Annotation'
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::AnnotationNotFound, $response);
    }

    public function testPutIncorrectTextAnnotationActionAPI()
    {
        $repository = $this->em->getRepository('GeneralBundle:Annotation');
        $annotation = $repository->findOneByText("Annotation TFG");

        $method = 'PUT';
        $uri = '/api/annotation/' . $annotation->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = json_encode(array(
            'text' => ''
        ));

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertContains('This value should not be blank.', $response);
    }

    public function testPutEmptyContentAnnotationActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Annotation');
        $annotation = $repository->findOneByText("Annotation TFG");

    	$method = 'PUT';
        $uri = '/api/annotation/' . $annotation->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = '';

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Annotation TFG', $response);
    }

    public function testDeleteTournamentActionAPI()
    {
    	$repository = $this->em->getRepository('GeneralBundle:Annotation');
        $annotation = $repository->findOneByText("Annotation TFGDELETE");

    	$method = 'DELETE';
        $uri = '/api/annotation/' . $annotation->getId();
        $parameters = array();
        $files = array();
        $server = array();
        $content = array();

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::AnnotationDeleted, $response);
    }

    public function testDeleteNotFoundAnnotationActionAPI()
    {
    	$method = 'DELETE';
        $uri = '/api/annotation/0';
        $parameters = array();
        $files = array();
        $server = array();
        $content = "";

        $this->client->request($method, $uri, $parameters, $files, $server, $content);
        $response = $this->client->getResponse()->getContent();
        
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertContains(Literals::AnnotationNotFound, $response);
    }
}
