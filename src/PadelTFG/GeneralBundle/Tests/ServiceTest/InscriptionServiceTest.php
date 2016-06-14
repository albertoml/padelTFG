<?php

namespace PadelTFG\GeneralBundle\Tests\ServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Service\InscriptionService as InscriptionService;

class InscriptionServiceTest extends WebTestCase
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
        $this->group1 = new GroupCategory();
        $this->group2 = new GroupCategory();
        $this->group3 = new GroupCategory();
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

        $this->group1->setTournament($this->tournament);
        $this->group1->setCategory($this->category);
        $this->group1->setName('TestGroup1');
        $this->group1->setNumPairs(2);
        $this->group2->setTournament($this->tournament);
        $this->group2->setCategory($this->category);
        $this->group2->setName('TestGroup2');
        $this->group2->setNumPairs(2);
        $this->group3->setTournament($this->tournament);
        $this->group3->setCategory($this->category);
        $this->group3->setName('TestGroup3');
        $this->group3->setNumPairs(1);

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

        $this->inscription1->setGroup($this->group1);
        $this->inscription2->setGroup($this->group1);
        $this->inscription3->setGroup($this->group2);
        $this->inscription4->setGroup($this->group2);
        $this->inscription5->setGroup($this->group3);

        $this->inscription1->setClassifiedPositionInGroup(1);
        $this->inscription2->setClassifiedPositionInGroup(0);
        $this->inscription3->setClassifiedPositionInGroup(0);
        $this->inscription4->setClassifiedPositionInGroup(1);
        $this->inscription5->setClassifiedPositionInGroup(0);

        $this->inscription1->setClassifiedPositionByGroups(1);
        $this->inscription2->setClassifiedPositionByGroups(0);
        $this->inscription3->setClassifiedPositionByGroups(1);
        $this->inscription4->setClassifiedPositionByGroups(0);
        $this->inscription5->setClassifiedPositionByGroups(2);



        $this->em->persist($this->admin);
        $this->em->persist($this->tournament);
        $this->em->persist($this->category);
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
        $this->em->persist($this->pair1);
        $this->em->persist($this->pair2);
        $this->em->persist($this->pair3);
        $this->em->persist($this->pair4);
        $this->em->persist($this->pair5);
        $this->em->persist($this->inscription1);
        $this->em->persist($this->inscription2);
        $this->em->persist($this->inscription3);
        $this->em->persist($this->inscription4);
        $this->em->persist($this->inscription5);
        $this->em->flush();

        
    }

    protected function tearDown()
    {
        $this->em->remove($this->group1);
        $this->em->remove($this->group2);
        $this->em->remove($this->group3);
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

    public function testSortByClassified()
    {
        $inscriptions = [$this->inscription1, $this->inscription2, $this->inscription3, $this->inscription4, $this->inscription5];

        $inscriptionsExpected = [$this->inscription2, $this->inscription3, $this->inscription5, $this->inscription4, $this->inscription1];

        $inscriptionService = new InscriptionService();
        $inscriptionService->setManager($this->em);

        $inscriptionsResult = $inscriptionService->orderByClassified($inscriptions);

        $this->assertEquals($inscriptionsResult, $inscriptionsExpected);
    }

}
