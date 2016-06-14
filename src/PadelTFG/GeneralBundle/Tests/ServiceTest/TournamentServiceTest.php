<?php

namespace PadelTFG\GeneralBundle\Tests\ServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Entity\Game;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Service\TournamentService as TournamentService;

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
        $this->em->remove($this->category);
        $this->em->remove($this->tournament);
        $this->em->remove($this->admin);
        $this->em->flush();
    }

    public function setUpGroupsAndGames(){

        $this->user1 = new User();
        $this->user2 = new User();
        $this->user3 = new User();
        $this->user4 = new User();
        $this->user5 = new User();
        $this->user6 = new User();
        $this->user7 = new User();
        $this->user8 = new User();
        $this->user9 = new User();
        $this->user10 = new User();
        $this->user11 = new User();
        $this->user12 = new User();
        $this->user13 = new User();
        $this->user14 = new User();
        $this->user15 = new User();
        $this->user16 = new User();
        $this->user17 = new User();
        $this->user18 = new User();
        $this->user19 = new User();
        $this->user20 = new User();
        $this->user21 = new User();
        $this->user22 = new User();
        $this->pair1 = new Pair();
        $this->pair2 = new Pair();
        $this->pair3 = new Pair();
        $this->pair4 = new Pair();
        $this->pair5 = new Pair();
        $this->pair6 = new Pair();
        $this->pair7 = new Pair();
        $this->pair8 = new Pair();
        $this->pair9 = new Pair();
        $this->pair10 = new Pair();
        $this->pair11 = new Pair();
        $this->game1 = new Game();
        $this->game2 = new Game();
        $this->game3 = new Game();
        $this->game4 = new Game();
        $this->game5 = new Game();
        $this->game6 = new Game();
        $this->game7 = new Game();
        $this->game8 = new Game();
        $this->game9 = new Game();
        $this->game10 = new Game();
        $this->game11 = new Game();
        $this->game12 = new Game();
        $this->game13 = new Game();
        $this->game14 = new Game();
        $this->game15 = new Game();
        $this->group1 = new GroupCategory();
        $this->group2 = new GroupCategory();
        $this->group3 = new GroupCategory();
        $this->inscription1 = new Inscription();
        $this->inscription2 = new Inscription();
        $this->inscription3 = new Inscription();
        $this->inscription4 = new Inscription();
        $this->inscription5 = new Inscription();
        $this->inscription6 = new Inscription();
        $this->inscription7 = new Inscription();
        $this->inscription8 = new Inscription();
        $this->inscription9 = new Inscription();
        $this->inscription10 = new Inscription();
        $this->inscription11 = new Inscription();

        $this->group1->setTournament($this->tournament);
        $this->group1->setCategory($this->category);
        $this->group1->setName('TestGroup1');
        $this->group1->setNumPairs(3);
        $this->group2->setTournament($this->tournament);
        $this->group2->setCategory($this->category);
        $this->group2->setName('TestGroup2');
        $this->group2->setNumPairs(4);
        $this->group3->setTournament($this->tournament);
        $this->group3->setCategory($this->category);
        $this->group3->setName('TestGroup3');
        $this->group3->setNumPairs(4);

        $this->user1->setName('Test1');
        $this->user2->setName('Test2');
        $this->user3->setName('Test3');
        $this->user4->setName('Test4');
        $this->user5->setName('Test5');
        $this->user6->setName('Test6');
        $this->user7->setName('Test7');
        $this->user8->setName('Test8');
        $this->user9->setName('Test9');
        $this->user10->setName('Test10');
        $this->user11->setName('Test11');
        $this->user12->setName('Test12');
        $this->user13->setName('Test13');
        $this->user14->setName('Test14');
        $this->user15->setName('Test15');
        $this->user16->setName('Test16');
        $this->user17->setName('Test17');
        $this->user18->setName('Test18');
        $this->user19->setName('Test19');
        $this->user20->setName('Test20');
        $this->user21->setName('Test21');
        $this->user22->setName('Test22');
        $this->user1->setLastName('TestLast1');
        $this->user2->setLastName('TestLast2');
        $this->user3->setLastName('TestLast3');
        $this->user4->setLastName('TestLast4');
        $this->user5->setLastName('TestLast5');
        $this->user6->setLastName('TestLast6');
        $this->user7->setLastName('TestLast7');
        $this->user8->setLastName('TestLast8');
        $this->user9->setLastName('TestLast9');
        $this->user10->setLastName('TestLast10');
        $this->user11->setLastName('TestLast11');
        $this->user12->setLastName('TestLast12');
        $this->user13->setLastName('TestLast13');
        $this->user14->setLastName('TestLast14');
        $this->user15->setLastName('TestLast15');
        $this->user16->setLastName('TestLast16');
        $this->user17->setLastName('TestLast17');
        $this->user18->setLastName('TestLast18');
        $this->user19->setLastName('TestLast19');
        $this->user20->setLastName('TestLast20');
        $this->user21->setLastName('TestLast21');
        $this->user22->setLastName('TestLast22');
        $this->user1->setEmail('Email1');
        $this->user2->setEmail('Email2');
        $this->user3->setEmail('Email3');
        $this->user4->setEmail('Email4');
        $this->user5->setEmail('Email5');
        $this->user6->setEmail('Email6');
        $this->user7->setEmail('Email7');
        $this->user8->setEmail('Email8');
        $this->user9->setEmail('Email9');
        $this->user10->setEmail('Email10');
        $this->user11->setEmail('Email11');
        $this->user12->setEmail('Email12');
        $this->user13->setEmail('Email13');
        $this->user14->setEmail('Email14');
        $this->user15->setEmail('Email15');
        $this->user16->setEmail('Email16');
        $this->user17->setEmail('Email17');
        $this->user18->setEmail('Email18');
        $this->user19->setEmail('Email19');
        $this->user20->setEmail('Email20');
        $this->user21->setEmail('Email21');
        $this->user22->setEmail('Email22');
        
        $this->pair1->setUser1($this->user1);
        $this->pair1->setUser2($this->user2);
        $this->pair2->setUser1($this->user3);
        $this->pair2->setUser2($this->user4);
        $this->pair3->setUser1($this->user5);
        $this->pair3->setUser2($this->user6);
        $this->pair4->setUser1($this->user7);
        $this->pair4->setUser2($this->user8);
        $this->pair5->setUser1($this->user9);
        $this->pair5->setUser2($this->user10);
        $this->pair6->setUser1($this->user11);
        $this->pair6->setUser2($this->user12);
        $this->pair7->setUser1($this->user13);
        $this->pair7->setUser2($this->user14);
        $this->pair8->setUser1($this->user15);
        $this->pair8->setUser2($this->user16);
        $this->pair9->setUser1($this->user17);
        $this->pair9->setUser2($this->user18);
        $this->pair10->setUser1($this->user19);
        $this->pair10->setUser2($this->user20);
        $this->pair11->setUser1($this->user21);
        $this->pair11->setUser2($this->user22);

        $this->game1->setPair1($this->pair1);
        $this->game1->setPair2($this->pair2);
        $this->game2->setPair1($this->pair1);
        $this->game2->setPair2($this->pair3);
        $this->game3->setPair1($this->pair2);
        $this->game3->setPair2($this->pair3);
        $this->game4->setPair1($this->pair4);
        $this->game4->setPair2($this->pair5);
        $this->game5->setPair1($this->pair4);
        $this->game5->setPair2($this->pair6);
        $this->game6->setPair1($this->pair4);
        $this->game6->setPair2($this->pair7);
        $this->game7->setPair1($this->pair5);
        $this->game7->setPair2($this->pair6);
        $this->game8->setPair1($this->pair5);
        $this->game8->setPair2($this->pair7);
        $this->game9->setPair1($this->pair6);
        $this->game9->setPair2($this->pair7);
        $this->game10->setPair1($this->pair8);
        $this->game10->setPair2($this->pair9);
        $this->game11->setPair1($this->pair8);
        $this->game11->setPair2($this->pair10);
        $this->game12->setPair1($this->pair8);
        $this->game12->setPair2($this->pair11);
        $this->game13->setPair1($this->pair9);
        $this->game13->setPair2($this->pair10);
        $this->game14->setPair1($this->pair9);
        $this->game14->setPair2($this->pair11);
        $this->game15->setPair1($this->pair10);
        $this->game15->setPair2($this->pair11);

        $this->game1->setGroup($this->group1);
        $this->game2->setGroup($this->group1);
        $this->game3->setGroup($this->group1);
        $this->game4->setGroup($this->group2);
        $this->game5->setGroup($this->group2);
        $this->game6->setGroup($this->group2);
        $this->game7->setGroup($this->group2);
        $this->game8->setGroup($this->group2);
        $this->game9->setGroup($this->group2);
        $this->game10->setGroup($this->group3);
        $this->game11->setGroup($this->group3);
        $this->game12->setGroup($this->group3);
        $this->game13->setGroup($this->group3);
        $this->game14->setGroup($this->group3);
        $this->game15->setGroup($this->group3);

        $this->game1->setScore("6/1 6/3");
        $this->game2->setScore("6/2 6/2");
        $this->game3->setScore("6/3 6/1");
        $this->game4->setScore("6/2 6/4");
        $this->game5->setScore("6/1 6/2");
        $this->game6->setScore("6/1 6/1");
        $this->game7->setScore("6/0 6/4");
        $this->game8->setScore("6/0 6/3");
        $this->game9->setScore("6/1 6/2");
        $this->game10->setScore("6/1 6/3");
        $this->game11->setScore("6/1 6/1");
        $this->game12->setScore("6/0 6/4");
        $this->game13->setScore("6/0 6/3");
        $this->game14->setScore("6/1 6/2");
        $this->game15->setScore("6/1 6/3");

        $this->inscription1->setPair($this->pair1);
        $this->inscription2->setPair($this->pair2);
        $this->inscription3->setPair($this->pair3);
        $this->inscription4->setPair($this->pair4);
        $this->inscription5->setPair($this->pair5);
        $this->inscription6->setPair($this->pair6);
        $this->inscription7->setPair($this->pair7);
        $this->inscription8->setPair($this->pair8);
        $this->inscription9->setPair($this->pair9);
        $this->inscription10->setPair($this->pair10);
        $this->inscription11->setPair($this->pair11);
        $this->inscription1->setTournament($this->tournament);
        $this->inscription2->setTournament($this->tournament);
        $this->inscription3->setTournament($this->tournament);
        $this->inscription4->setTournament($this->tournament);
        $this->inscription5->setTournament($this->tournament);
        $this->inscription6->setTournament($this->tournament);
        $this->inscription7->setTournament($this->tournament);
        $this->inscription8->setTournament($this->tournament);
        $this->inscription9->setTournament($this->tournament);
        $this->inscription10->setTournament($this->tournament);
        $this->inscription11->setTournament($this->tournament);
        $this->inscription1->setCategory($this->category);
        $this->inscription2->setCategory($this->category);
        $this->inscription3->setCategory($this->category);
        $this->inscription4->setCategory($this->category);
        $this->inscription5->setCategory($this->category);
        $this->inscription6->setCategory($this->category);
        $this->inscription7->setCategory($this->category);
        $this->inscription8->setCategory($this->category);
        $this->inscription9->setCategory($this->category);
        $this->inscription10->setCategory($this->category);
        $this->inscription11->setCategory($this->category);
        $this->inscription1->setGroup($this->group1);
        $this->inscription2->setGroup($this->group1);
        $this->inscription3->setGroup($this->group1);
        $this->inscription4->setGroup($this->group2);
        $this->inscription5->setGroup($this->group2);
        $this->inscription6->setGroup($this->group2);
        $this->inscription7->setGroup($this->group2);
        $this->inscription8->setGroup($this->group3);
        $this->inscription9->setGroup($this->group3);
        $this->inscription10->setGroup($this->group3);
        $this->inscription11->setGroup($this->group3);

        $this->em->persist($this->group1);
        $this->em->persist($this->group2);
        $this->em->persist($this->group3);
        $this->em->persist($this->user1);
        $this->em->persist($this->user2);
        $this->em->persist($this->user3);
        $this->em->persist($this->user4);
        $this->em->persist($this->user5);
        $this->em->persist($this->user6);
        $this->em->persist($this->user7);
        $this->em->persist($this->user8);
        $this->em->persist($this->user9);
        $this->em->persist($this->user10);
        $this->em->persist($this->user11);
        $this->em->persist($this->user12);
        $this->em->persist($this->user13);
        $this->em->persist($this->user14);
        $this->em->persist($this->user15);
        $this->em->persist($this->user16);
        $this->em->persist($this->user17);
        $this->em->persist($this->user18);
        $this->em->persist($this->user19);
        $this->em->persist($this->user20);
        $this->em->persist($this->user21);
        $this->em->persist($this->user22);
        $this->em->persist($this->pair1);
        $this->em->persist($this->pair2);
        $this->em->persist($this->pair3);
        $this->em->persist($this->pair4);
        $this->em->persist($this->pair5);
        $this->em->persist($this->pair6);
        $this->em->persist($this->pair7);
        $this->em->persist($this->pair8);
        $this->em->persist($this->pair9);
        $this->em->persist($this->pair10);
        $this->em->persist($this->pair11);
        $this->em->persist($this->game1);
        $this->em->persist($this->game2);
        $this->em->persist($this->game3);
        $this->em->persist($this->game4);
        $this->em->persist($this->game5);
        $this->em->persist($this->game6);
        $this->em->persist($this->game7);
        $this->em->persist($this->game8);
        $this->em->persist($this->game9);
        $this->em->persist($this->game10);
        $this->em->persist($this->game11);
        $this->em->persist($this->game12);
        $this->em->persist($this->game13);
        $this->em->persist($this->game14);
        $this->em->persist($this->game15);
        $this->em->persist($this->inscription1);
        $this->em->persist($this->inscription2);
        $this->em->persist($this->inscription3);
        $this->em->persist($this->inscription4);
        $this->em->persist($this->inscription5);
        $this->em->persist($this->inscription6);
        $this->em->persist($this->inscription7);
        $this->em->persist($this->inscription8);
        $this->em->persist($this->inscription9);
        $this->em->persist($this->inscription10);
        $this->em->persist($this->inscription11);
        $this->em->flush();
    }

    public function tearDownGroupsAndGames(){
        $this->em->remove($this->group1);
        $this->em->remove($this->group2);
        $this->em->remove($this->group3);
        $this->em->remove($this->user1);
        $this->em->remove($this->user2);
        $this->em->remove($this->user3);
        $this->em->remove($this->user4);
        $this->em->remove($this->user5);
        $this->em->remove($this->user6);
        $this->em->remove($this->user7);
        $this->em->remove($this->user8);
        $this->em->remove($this->user9);
        $this->em->remove($this->user10);
        $this->em->remove($this->user11);
        $this->em->remove($this->user12);
        $this->em->remove($this->user13);
        $this->em->remove($this->user14);
        $this->em->remove($this->user15);
        $this->em->remove($this->user16);
        $this->em->remove($this->user17);
        $this->em->remove($this->user18);
        $this->em->remove($this->user19);
        $this->em->remove($this->user20);
        $this->em->remove($this->user21);
        $this->em->remove($this->user22);
        $this->em->remove($this->pair1);
        $this->em->remove($this->pair2);
        $this->em->remove($this->pair3);
        $this->em->remove($this->pair4);
        $this->em->remove($this->pair5);
        $this->em->remove($this->pair6);
        $this->em->remove($this->pair7);
        $this->em->remove($this->pair8);
        $this->em->remove($this->pair9);
        $this->em->remove($this->pair10);
        $this->em->remove($this->pair11);
        $this->em->remove($this->game1);
        $this->em->remove($this->game2);
        $this->em->remove($this->game3);
        $this->em->remove($this->game4);
        $this->em->remove($this->game5);
        $this->em->remove($this->game6);
        $this->em->remove($this->game7);
        $this->em->remove($this->game8);
        $this->em->remove($this->game9);
        $this->em->remove($this->game10);
        $this->em->remove($this->game11);
        $this->em->remove($this->game12);
        $this->em->remove($this->game13);
        $this->em->remove($this->game14);
        $this->em->remove($this->game15);
        $this->em->remove($this->inscription1);
        $this->em->remove($this->inscription2);
        $this->em->remove($this->inscription3);
        $this->em->remove($this->inscription4);
        $this->em->remove($this->inscription5);
        $this->em->remove($this->inscription6);
        $this->em->remove($this->inscription7);
        $this->em->remove($this->inscription8);
        $this->em->remove($this->inscription9);
        $this->em->remove($this->inscription10);
        $this->em->remove($this->inscription11);
        $this->em->flush();
    }

    public function testNumGroups_20Inscriptions()
    {
    	$tournamentService = new TournamentService();
    	$tournamentService->setManager($this->em);
    	$groups = $tournamentService->calculateNumGroups(20, 4, $this->category, $this->tournament);

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
    	$groups = $tournamentService->calculateNumGroups(22, 4, $this->category, $this->tournament);

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
    	$groups = $tournamentService->calculateNumGroups(23, 4, $this->category, $this->tournament);

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
        $groups = $tournamentService->calculateNumGroups(0, 4, $this->category, $this->tournament);

        $this->assertEquals(0, count($groups));
    }

    public function testCloseGroupTournament()
    {
        $this->setUpGroupsAndGames();
        $tournamentService = new TournamentService();
        $tournamentService->setManager($this->em);
        $groups = $tournamentService->closeGroupTournament($this->tournament);

        //$this->assertEquals("", json_encode($groups));

        $this->assertEquals($this->inscription1->getClassifiedPositionInGroup(), 0);
        $this->assertEquals($this->inscription1->getClassifiedPositionByGroups(), 2);

        $this->assertEquals($this->inscription2->getClassifiedPositionInGroup(), 1);
        $this->assertEquals($this->inscription2->getClassifiedPositionByGroups(), 2);

        $this->assertEquals($this->inscription3->getClassifiedPositionInGroup(), 2);
        $this->assertEquals($this->inscription3->getClassifiedPositionByGroups(), 2);

        $this->assertEquals($this->inscription4->getClassifiedPositionInGroup(), 0);
        $this->assertEquals($this->inscription4->getClassifiedPositionByGroups(), 1);

        $this->assertEquals($this->inscription5->getClassifiedPositionInGroup(), 1);
        $this->assertEquals($this->inscription5->getClassifiedPositionByGroups(), 0);

        $this->assertEquals($this->inscription6->getClassifiedPositionInGroup(), 2);
        $this->assertEquals($this->inscription6->getClassifiedPositionByGroups(), 0);

        $this->assertEquals($this->inscription7->getClassifiedPositionInGroup(), 3);
        $this->assertEquals($this->inscription7->getClassifiedPositionByGroups(), 1);

        $this->assertEquals($this->inscription8->getClassifiedPositionInGroup(), 0);
        $this->assertEquals($this->inscription8->getClassifiedPositionByGroups(), 0);

        $this->assertEquals($this->inscription9->getClassifiedPositionInGroup(), 1);
        $this->assertEquals($this->inscription9->getClassifiedPositionByGroups(), 1);

        $this->assertEquals($this->inscription10->getClassifiedPositionInGroup(), 2);
        $this->assertEquals($this->inscription10->getClassifiedPositionByGroups(), 1);

        $this->assertEquals($this->inscription11->getClassifiedPositionInGroup(), 3);
        $this->assertEquals($this->inscription11->getClassifiedPositionByGroups(), 0);

        $this->tearDownGroupsAndGames();
    }

    
}
