<?php

namespace PadelTFG\GeneralBundle\Tests\ServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Game;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Service\GroupService as GroupService;

class GroupServiceTest extends WebTestCase
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
        $this->pair1 = new Pair();
        $this->pair2 = new Pair();
        $this->pair3 = new Pair();
        $this->pair4 = new Pair();
        $this->pair5 = new Pair();
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
        $this->group = new GroupCategory();
        $this->inscription1 = new Inscription();
        $this->inscription2 = new Inscription();
        $this->inscription3 = new Inscription();
        $this->inscription4 = new Inscription();
        $this->inscription5 = new Inscription();

        $this->admin->setName('UserTournamentTest');
        $this->admin->setLastName('UserLastName');
        $this->admin->setEmail('emailTournamentTest');
        $this->admin->setPassword('password');

        $this->tournament->setName($this->admin);
        $this->tournament->setName('Tournament');
        $this->category->setTournament($this->tournament);
        $this->category->setName('Category');

        $this->group->setTournament($this->tournament);
        $this->group->setCategory($this->category);
        $this->group->setName('TestGroup');
        $this->group->setNumPairs(5);

        $this->user1->setName('Test1');
        $this->user2->setName('Test2');
        $this->user3->setName('Test3');
        $this->user4->setName('Test4');
        $this->user1->setLastName('TestLast1');
        $this->user2->setLastName('TestLast2');
        $this->user3->setLastName('TestLast3');
        $this->user4->setLastName('TestLast4');
        $this->user1->setEmail('Email1');
        $this->user2->setEmail('Email2');
        $this->user3->setEmail('Email3');
        $this->user4->setEmail('Email4');
        $this->user5->setName('Test5');
        $this->user6->setName('Test6');
        $this->user7->setName('Test7');
        $this->user8->setName('Test8');
        $this->user5->setLastName('TestLast5');
        $this->user6->setLastName('TestLast6');
        $this->user7->setLastName('TestLast7');
        $this->user8->setLastName('TestLast8');
        $this->user5->setEmail('Email5');
        $this->user6->setEmail('Email6');
        $this->user7->setEmail('Email7');
        $this->user8->setEmail('Email8');
        $this->user9->setName('Test9');
        $this->user10->setName('Test10');
        $this->user9->setLastName('TestLast9');
        $this->user10->setLastName('TestLast10');
        $this->user9->setEmail('Email9');
        $this->user10->setEmail('Email10');

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

        $this->game1->setPair1($this->pair1);
        $this->game1->setPair2($this->pair2);
        $this->game2->setPair1($this->pair1);
        $this->game2->setPair2($this->pair3);
        $this->game3->setPair1($this->pair1);
        $this->game3->setPair2($this->pair4);
        $this->game4->setPair1($this->pair1);
        $this->game4->setPair2($this->pair5);
        $this->game5->setPair1($this->pair2);
        $this->game5->setPair2($this->pair3);
        $this->game6->setPair1($this->pair2);
        $this->game6->setPair2($this->pair4);
        $this->game7->setPair1($this->pair2);
        $this->game7->setPair2($this->pair5);
        $this->game8->setPair1($this->pair3);
        $this->game8->setPair2($this->pair4);
        $this->game9->setPair1($this->pair3);
        $this->game9->setPair2($this->pair5);
        $this->game10->setPair1($this->pair4);
        $this->game10->setPair2($this->pair5);

        $this->game1->setGroup($this->group);
        $this->game2->setGroup($this->group);
        $this->game3->setGroup($this->group);
        $this->game4->setGroup($this->group);
        $this->game5->setGroup($this->group);
        $this->game6->setGroup($this->group);
        $this->game7->setGroup($this->group);
        $this->game8->setGroup($this->group);
        $this->game9->setGroup($this->group);
        $this->game10->setGroup($this->group);


        $this->game1->setScore("6/1 6/3");
        $this->game2->setScore("2/6 1/6");
        $this->game3->setScore("4/6 2/6");
        $this->game4->setScore("1/6 2/6");
        $this->game5->setScore("6/1 6/2");
        $this->game6->setScore("6/1 6/1");
        $this->game7->setScore("6/0 6/4");
        $this->game8->setScore("6/0 6/3");
        $this->game9->setScore("6/1 6/2");
        $this->game10->setScore("6/1 6/3");

        $this->inscription1->setPair($this->pair1);
        $this->inscription2->setPair($this->pair2);
        $this->inscription3->setPair($this->pair3);
        $this->inscription4->setPair($this->pair4);
        $this->inscription5->setPair($this->pair5);
        $this->inscription1->setTournament($this->tournament);
        $this->inscription2->setTournament($this->tournament);
        $this->inscription3->setTournament($this->tournament);
        $this->inscription4->setTournament($this->tournament);
        $this->inscription5->setTournament($this->tournament);
        $this->inscription1->setCategory($this->category);
        $this->inscription2->setCategory($this->category);
        $this->inscription3->setCategory($this->category);
        $this->inscription4->setCategory($this->category);
        $this->inscription5->setCategory($this->category);
        $this->inscription1->setGroup($this->group);
        $this->inscription2->setGroup($this->group);
        $this->inscription3->setGroup($this->group);
        $this->inscription4->setGroup($this->group);
        $this->inscription5->setGroup($this->group);

        $this->em->persist($this->admin);
        $this->em->persist($this->tournament);
        $this->em->persist($this->category);
        $this->em->persist($this->group);
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
        $this->em->persist($this->pair1);
        $this->em->persist($this->pair2);
        $this->em->persist($this->pair3);
        $this->em->persist($this->pair4);
        $this->em->persist($this->pair5);
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
        $this->em->persist($this->inscription1);
        $this->em->persist($this->inscription2);
        $this->em->persist($this->inscription3);
        $this->em->persist($this->inscription4);
        $this->em->persist($this->inscription5);
        $this->em->flush();

        
    }

    protected function tearDown()
    {
        $this->em->remove($this->group);
        $this->em->remove($this->admin);
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
        $this->em->remove($this->tournament);
        $this->em->remove($this->category);
        $this->em->flush();
    }

    public function testSortByScore()
    {
        $rank[1] = array('matchsWon' => 2, 'setsWon' => 5, 'setsLost' => 3, 'gamesWon' => 35, 'gamesLost' => 38, 'points' => 6, 'pair' => 1);
        $rank[2] = array('matchsWon' => 1, 'setsWon' => 3, 'setsLost' => 5, 'gamesWon' => 38, 'gamesLost' => 35, 'points' => 3, 'pair' => 2);
        $rank[3] = array('matchsWon' => 3, 'setsWon' => 6, 'setsLost' => 0, 'gamesWon' => 55, 'gamesLost' => 20, 'points' => 9, 'pair' => 3);
        $rank[4] = array('matchsWon' => 0, 'setsWon' => 2, 'setsLost' => 8, 'gamesWon' => 21, 'gamesLost' => 60, 'points' => 0, 'pair' => 4);
        $rank[5] = array('matchsWon' => 2, 'setsWon' => 4, 'setsLost' => 0, 'gamesWon' => 40, 'gamesLost' => 20, 'points' => 6, 'pair' => 5);
        $rank[6] = array('matchsWon' => 1, 'setsWon' => 3, 'setsLost' => 5, 'gamesWon' => 39, 'gamesLost' => 35, 'points' => 3, 'pair' => 6);

        $groupService = new GroupService();
        $groupService->setManager($this->em);
        $rankResult = $groupService->sortByScore($rank);
        $this->assertEquals(json_encode($rankResult), '[{"matchsWon":3,"setsWon":6,"setsLost":0,"gamesWon":55,"gamesLost":20,"points":9,"pair":3},{"matchsWon":2,"setsWon":5,"setsLost":3,"gamesWon":35,"gamesLost":38,"points":6,"pair":1},{"matchsWon":2,"setsWon":4,"setsLost":0,"gamesWon":40,"gamesLost":20,"points":6,"pair":5},{"matchsWon":1,"setsWon":3,"setsLost":5,"gamesWon":39,"gamesLost":35,"points":3,"pair":6},{"matchsWon":1,"setsWon":3,"setsLost":5,"gamesWon":38,"gamesLost":35,"points":3,"pair":2},{"matchsWon":0,"setsWon":2,"setsLost":8,"gamesWon":21,"gamesLost":60,"points":0,"pair":4}]');
    }

    public function testSortByScorePointsMajorSets()
    {
        $rank[1] = array('matchsWon' => 2, 'setsWon' => 5, 'setsLost' => 3, 'gamesWon' => 35, 'gamesLost' => 38, 'points' => 3);
        $rank[2] = array('matchsWon' => 1, 'setsWon' => 3, 'setsLost' => 5, 'gamesWon' => 38, 'gamesLost' => 35, 'points' => 3);

        $groupService = new GroupService();
        $groupService->setManager($this->em);
        $rankResult = $groupService->sortByScorePoints($rank[1], $rank[2]);
        $this->assertEquals($rankResult, -1);
    }
    public function testSortByScorePointsMajor()
    {
        $rank[1] = array('matchsWon' => 2, 'setsWon' => 5, 'setsLost' => 3, 'gamesWon' => 35, 'gamesLost' => 38, 'points' => 4);
        $rank[2] = array('matchsWon' => 1, 'setsWon' => 3, 'setsLost' => 5, 'gamesWon' => 38, 'gamesLost' => 35, 'points' => 3);

        $groupService = new GroupService();
        $groupService->setManager($this->em);
        $rankResult = $groupService->sortByScorePoints($rank[1], $rank[2]);
        $this->assertEquals($rankResult, -1);
    }
    public function testSortByScorePointsMinor()
    {
        $rank[1] = array('matchsWon' => 2, 'setsWon' => 5, 'setsLost' => 3, 'gamesWon' => 35, 'gamesLost' => 38, 'points' => 3);
        $rank[2] = array('matchsWon' => 1, 'setsWon' => 3, 'setsLost' => 5, 'gamesWon' => 38, 'gamesLost' => 35, 'points' => 4);

        $groupService = new GroupService();
        $groupService->setManager($this->em);
        $rankResult = $groupService->sortByScorePoints($rank[1], $rank[2]);
        $this->assertEquals($rankResult, 1);
    }
    public function testCalculateClassficationByGroupPointsOrder()
    {
        $this->game1->setScore("6/1 6/3");
        $this->game2->setScore("6/2 7/5");
        $this->game3->setScore("6/1 7/6");
        $this->game4->setScore("6/4 6/3");
        $this->game5->setScore("6/1 6/2");
        $this->game6->setScore("6/1 6/1");
        $this->game7->setScore("6/0 6/4");
        $this->game8->setScore("6/0 6/3");
        $this->game9->setScore("6/1 6/2");
        $this->game10->setScore("6/1 6/3");
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


        $inscriptions = [$this->inscription1, $this->inscription2, $this->inscription3, $this->inscription4, $this->inscription5];
        $groupService = new GroupService();
        $groupService->setManager($this->em);
        $rankResult = $groupService->calculateClassficationByGroup($inscriptions, $this->group);
        $this->assertEquals(json_encode($rankResult), '[{"matchsWon":4,"setsWon":8,"setsLost":0,"gamesWon":50,"gamesLost":25,"points":12,"pair":' . $this->pair1->getId() . '},{"matchsWon":3,"setsWon":6,"setsLost":2,"gamesWon":40,"gamesLost":21,"points":9,"pair":' . $this->pair2->getId() . '},{"matchsWon":2,"setsWon":4,"setsLost":4,"gamesWon":34,"gamesLost":31,"points":6,"pair":' . $this->pair3->getId() . '},{"matchsWon":1,"setsWon":2,"setsLost":6,"gamesWon":24,"gamesLost":41,"points":3,"pair":' . $this->pair4->getId() . '},{"matchsWon":0,"setsWon":0,"setsLost":8,"gamesWon":18,"gamesLost":48,"points":0,"pair":' . $this->pair5->getId() . '}]');

        $this->assertEquals($this->inscription1->getClassifiedPositionInGroup(), 0);
        $this->assertEquals($this->inscription2->getClassifiedPositionInGroup(), 1);
        $this->assertEquals($this->inscription3->getClassifiedPositionInGroup(), 2);
        $this->assertEquals($this->inscription4->getClassifiedPositionInGroup(), 3);
        $this->assertEquals($this->inscription5->getClassifiedPositionInGroup(), 4);
    }

    public function testCalculateClassficationByGroupTripleTie()
    {
        $this->game1->setScore("6/1 6/3");
        $this->game2->setScore("1/6 2/6");
        $this->game3->setScore("6/1 7/6");
        $this->game4->setScore("6/4 6/3");
        $this->game5->setScore("6/1 6/2");
        $this->game6->setScore("6/1 6/1");
        $this->game7->setScore("6/0 6/4");
        $this->game8->setScore("6/0 6/3");
        $this->game9->setScore("6/1 6/2");
        $this->game10->setScore("6/1 6/3");
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


        $inscriptions = [$this->inscription1, $this->inscription2, $this->inscription3, $this->inscription4, $this->inscription5];
        $groupService = new GroupService();
        $groupService->setManager($this->em);
        $rankResult = $groupService->calculateClassficationByGroup($inscriptions, $this->group);
        $this->assertEquals(json_encode($rankResult), '[{"matchsWon":3,"setsWon":6,"setsLost":2,"gamesWon":40,"gamesLost":21,"points":9,"pair":' . $this->pair2->getId() . '},{"matchsWon":3,"setsWon":6,"setsLost":2,"gamesWon":40,"gamesLost":30,"points":9,"pair":' . $this->pair1->getId() . '},{"matchsWon":3,"setsWon":6,"setsLost":2,"gamesWon":39,"gamesLost":21,"points":9,"pair":' . $this->pair3->getId() . '},{"matchsWon":1,"setsWon":2,"setsLost":6,"gamesWon":24,"gamesLost":41,"points":3,"pair":' . $this->pair4->getId() . '},{"matchsWon":0,"setsWon":0,"setsLost":8,"gamesWon":18,"gamesLost":48,"points":0,"pair":' . $this->pair5->getId() . '}]');
    
        $this->assertEquals($this->inscription1->getClassifiedPositionInGroup(), 1);
        $this->assertEquals($this->inscription2->getClassifiedPositionInGroup(), 0);
        $this->assertEquals($this->inscription3->getClassifiedPositionInGroup(), 2);
        $this->assertEquals($this->inscription4->getClassifiedPositionInGroup(), 3);
        $this->assertEquals($this->inscription5->getClassifiedPositionInGroup(), 4);
    }

    public function testCalculateClassficationByGroupDoubleTie()
    {
        $this->game1->setScore("6/0 6/0");
        $this->game2->setScore("2/6 1/6");
        $this->game3->setScore("4/6 2/6");
        $this->game4->setScore("1/6 2/6");
        $this->game5->setScore("6/1 6/2");
        $this->game6->setScore("6/1 6/1");
        $this->game7->setScore("6/0 6/4");
        $this->game8->setScore("6/0 6/3");
        $this->game9->setScore("6/1 6/2");
        $this->game10->setScore("6/1 6/3");
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


        $inscriptions = [$this->inscription1, $this->inscription2, $this->inscription3, $this->inscription4, $this->inscription5];
        $groupService = new GroupService();
        $groupService->setManager($this->em);
        $rankResult = $groupService->calculateClassficationByGroup($inscriptions, $this->group);
        $this->assertEquals(json_encode($rankResult), '[{"matchsWon":3,"setsWon":6,"setsLost":2,"gamesWon":36,"gamesLost":21,"points":9,"pair":' . $this->pair2->getId() . '},{"matchsWon":3,"setsWon":6,"setsLost":2,"gamesWon":39,"gamesLost":21,"points":9,"pair":' . $this->pair3->getId() . '},{"matchsWon":2,"setsWon":4,"setsLost":4,"gamesWon":29,"gamesLost":34,"points":6,"pair":' . $this->pair4->getId() . '},{"matchsWon":1,"setsWon":2,"setsLost":6,"gamesWon":23,"gamesLost":39,"points":3,"pair":' . $this->pair5->getId() . '},{"matchsWon":1,"setsWon":2,"setsLost":6,"gamesWon":24,"gamesLost":36,"points":3,"pair":' . $this->pair1->getId() . '}]');

        $this->assertEquals($this->inscription1->getClassifiedPositionInGroup(), 4);
        $this->assertEquals($this->inscription2->getClassifiedPositionInGroup(), 0);
        $this->assertEquals($this->inscription3->getClassifiedPositionInGroup(), 1);
        $this->assertEquals($this->inscription4->getClassifiedPositionInGroup(), 2);
        $this->assertEquals($this->inscription5->getClassifiedPositionInGroup(), 3);
    }
}
