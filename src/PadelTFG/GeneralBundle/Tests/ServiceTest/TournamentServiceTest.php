<?php

namespace PadelTFG\GeneralBundle\Tests\ServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;
use PadelTFG\GeneralBundle\Service\GroupService as GroupService;

class TournamentServiceTest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    	
    	$this->tournament = new Tournament();
    	$this->category = new Category();
    	$this->admin = new User();

		$this->admin->setName('UserTournamentTest');
		$this->admin->setLastName('UserLastName');
		$this->admin->setEmail('emailTournamentTest');
		$this->admin->setPassword('password');

    	$this->tournament->setName($this->admin);
    	$this->tournament->setName('Tournament');
    	$this->category->setTournament($this->tournament);
    	$this->category->setName('Category');

    	$this->em->persist($this->admin);
    	$this->em->persist($this->tournament);
    	$this->em->persist($this->category);
    	$this->em->flush();
    }

    protected function tearDown()
    {
        $this->em->remove($this->admin);
    	$this->em->remove($this->tournament);
    	$this->em->remove($this->category);
    	$this->em->flush();
    }

    public function testNumGroups_20Inscriptions()
    {
    	$tournamentService = new TournamentService();
    	$tournamentService->setManager($this->em);
    	$groupService = new GroupService();
    	$groupService->setManager($this->em);
    	$groups = $tournamentService->calculateNumGroups(20, 4, $this->category, $this->tournament, $groupService);

    	$this->assertEquals(5, count($groups));
    	$this->assertEquals(4, $groups[0]->getNumPairs());
    	$this->assertEquals(4, $groups[1]->getNumPairs());
    	$this->assertEquals(4, $groups[2]->getNumPairs());
    	$this->assertEquals(4, $groups[3]->getNumPairs());
    	$this->assertEquals(4, $groups[4]->getNumPairs());
    }
    public function testNumGroups_22Inscriptions()
    {
    	$tournamentService = new TournamentService();
    	$tournamentService->setManager($this->em);
    	$groupService = new GroupService();
    	$groupService->setManager($this->em);
    	$groups = $tournamentService->calculateNumGroups(22, 4, $this->category, $this->tournament, $groupService);

    	$this->assertEquals(5, count($groups));
    	$this->assertEquals(4, $groups[0]->getNumPairs());
    	$this->assertEquals(4, $groups[1]->getNumPairs());
    	$this->assertEquals(4, $groups[2]->getNumPairs());
    	$this->assertEquals(5, $groups[3]->getNumPairs());
    	$this->assertEquals(5, $groups[4]->getNumPairs());
    }
    public function testNumGroups_23Inscriptions()
    {
    	$tournamentService = new TournamentService();
    	$tournamentService->setManager($this->em);
    	$groupService = new GroupService();
    	$groupService->setManager($this->em);
    	$groups = $tournamentService->calculateNumGroups(23, 4, $this->category, $this->tournament, $groupService);

    	$this->assertEquals(6, count($groups));
    	$this->assertEquals(4, $groups[0]->getNumPairs());
    	$this->assertEquals(4, $groups[1]->getNumPairs());
    	$this->assertEquals(4, $groups[2]->getNumPairs());
    	$this->assertEquals(4, $groups[3]->getNumPairs());
    	$this->assertEquals(4, $groups[4]->getNumPairs());
    	$this->assertEquals(3, $groups[5]->getNumPairs());
    }
    public function testNumGroups_0Inscriptions()
    {
        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->em);
        $groupService = new GroupService();
        $groupService->setManager($this->em);
        $groups = $tournamentService->calculateNumGroups(0, 4, $this->category, $this->tournament, $groupService);

        $this->assertEquals(0, count($groups));
    }

    
}
