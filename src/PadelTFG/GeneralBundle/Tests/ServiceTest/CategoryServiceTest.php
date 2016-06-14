<?php

namespace PadelTFG\GeneralBundle\Tests\ServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Service\CategoryService as CategoryService;

class CategoryServiceTest extends WebTestCase
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
        $this->user23 = new User();
        $this->user24 = new User();
        $this->user25 = new User();
        $this->user26 = new User();
        $this->user27 = new User();
        $this->user28 = new User();
        $this->user29 = new User();
        $this->user30 = new User();
        $this->user31 = new User();
        $this->user32 = new User();
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
        $this->pair12 = new Pair();
        $this->pair13 = new Pair();
        $this->pair14 = new Pair();
        $this->pair15 = new Pair();
        $this->pair16 = new Pair();
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
        $this->inscription12 = new Inscription();
        $this->inscription13 = new Inscription();
        $this->inscription14 = new Inscription();
        $this->inscription15 = new Inscription();
        $this->inscription16 = new Inscription();

        $this->admin->setName('UserTournamentTest');
        $this->admin->setLastName('UserLastName');
        $this->admin->setEmail('emailTournamentTest');
        $this->admin->setPassword('password');

        $this->tournament->setName($this->admin);
        $this->tournament->setName('Tournament');
        $this->category->setTournament($this->tournament);
        $this->category->setName('Category');

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
        $this->user23->setName('Test23');
        $this->user24->setName('Test24');
        $this->user25->setName('Test25');
        $this->user26->setName('Test26');
        $this->user27->setName('Test27');
        $this->user28->setName('Test28');
        $this->user29->setName('Test29');
        $this->user30->setName('Test30');
        $this->user31->setName('Test31');
        $this->user32->setName('Test32');
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
        $this->user23->setLastName('TestLast23');
        $this->user24->setLastName('TestLast24');
        $this->user25->setLastName('TestLast25');
        $this->user26->setLastName('TestLast26');
        $this->user27->setLastName('TestLast27');
        $this->user28->setLastName('TestLast28');
        $this->user29->setLastName('TestLast29');
        $this->user30->setLastName('TestLast30');
        $this->user31->setLastName('TestLast31');
        $this->user32->setLastName('TestLast32');
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
        $this->user23->setEmail('Email23');
        $this->user24->setEmail('Email24');
        $this->user25->setEmail('Email25');
        $this->user26->setEmail('Email26');
        $this->user27->setEmail('Email27');
        $this->user28->setEmail('Email28');
        $this->user29->setEmail('Email29');
        $this->user30->setEmail('Email30');
        $this->user31->setEmail('Email31');
        $this->user32->setEmail('Email32');

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
        $this->pair11->setUser2($this->user21);
        $this->pair11->setUser2($this->user22);
        $this->pair12->setUser2($this->user23);
        $this->pair12->setUser2($this->user24);
        $this->pair13->setUser2($this->user25);
        $this->pair13->setUser2($this->user26);
        $this->pair14->setUser2($this->user27);
        $this->pair14->setUser2($this->user28);
        $this->pair15->setUser2($this->user29);
        $this->pair15->setUser2($this->user30);
        $this->pair16->setUser2($this->user31);
        $this->pair16->setUser2($this->user32);

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
        $this->inscription12->setPair($this->pair12);
        $this->inscription13->setPair($this->pair13);
        $this->inscription14->setPair($this->pair14);
        $this->inscription15->setPair($this->pair15);
        $this->inscription16->setPair($this->pair16);
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
        $this->inscription12->setTournament($this->tournament);
        $this->inscription13->setTournament($this->tournament);
        $this->inscription14->setTournament($this->tournament);
        $this->inscription15->setTournament($this->tournament);
        $this->inscription16->setTournament($this->tournament);
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
        $this->inscription12->setCategory($this->category);
        $this->inscription13->setCategory($this->category);
        $this->inscription14->setCategory($this->category);
        $this->inscription15->setCategory($this->category);
        $this->inscription16->setCategory($this->category);

        $this->em->persist($this->admin);
        $this->em->persist($this->tournament);
        $this->em->persist($this->category);
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
        $this->em->persist($this->user23);
        $this->em->persist($this->user24);
        $this->em->persist($this->user25);
        $this->em->persist($this->user26);
        $this->em->persist($this->user27);
        $this->em->persist($this->user28);
        $this->em->persist($this->user29);
        $this->em->persist($this->user30);
        $this->em->persist($this->user31);
        $this->em->persist($this->user32);
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
        $this->em->persist($this->pair12);
        $this->em->persist($this->pair13);
        $this->em->persist($this->pair14);
        $this->em->persist($this->pair15);
        $this->em->persist($this->pair16);
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
        $this->em->persist($this->inscription12);
        $this->em->persist($this->inscription13);
        $this->em->persist($this->inscription14);
        $this->em->persist($this->inscription15);
        $this->em->persist($this->inscription16);
        $this->em->flush();
    }

    protected function tearDown()
    {
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
        $this->em->remove($this->user23);
        $this->em->remove($this->user24);
        $this->em->remove($this->user25);
        $this->em->remove($this->user26);
        $this->em->remove($this->user27);
        $this->em->remove($this->user28);
        $this->em->remove($this->user29);
        $this->em->remove($this->user30);
        $this->em->remove($this->user31);
        $this->em->remove($this->user32);
        $this->em->remove($this->tournament);
        $this->em->remove($this->category);
        $this->em->flush();
        foreach ($this->games as $game) {
            $this->em->remove($game);
        }
        $this->em->flush();
    }

    /*public function testGenerateDraw16()
    {
        $inscriptions = [$this->inscription1, $this->inscription2, $this->inscription3, $this->inscription4, $this->inscription5, $this->inscription6, $this->inscription7, $this->inscription8, $this->inscription9, $this->inscription10, $this->inscription11, $this->inscription12, $this->inscription13, $this->inscription14, $this->inscription15, $this->inscription16];

        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);
        $this->games = $categoryService->generateDraw($inscriptions, 8, $this->category->getId(), $this->tournament->getId());

        $this->assertEquals(json_encode($this->games), ' ');
    }

    public function testGenerateDraw8()
    {
        $inscriptions = [$this->inscription1, $this->inscription2, $this->inscription3, $this->inscription4, $this->inscription5, $this->inscription6, $this->inscription7, $this->inscription8];

        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);

        $category = 0;
        $tournament = 1;
        $this->games = $categoryService->generateDraw($inscriptions, 4, $category, $tournament);

        $this->assertEquals(json_encode($this->games), ' ');
    }

    public function testGenerateDraw4()
    {
        $inscriptions = [$this->inscription1, $this->inscription2, $this->inscription3, $this->inscription4];

        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);

        $category = 0;
        $tournament = 1;
        $this->games = $categoryService->generateDraw($inscriptions, 2, $category, $tournament);

        $this->assertEquals(json_encode($this->games), ' ');
    }*/

    public function testGenerateDraw2()
    {
        $inscriptions = [$this->inscription1, $this->inscription2];

        $categoryService = new CategoryService();
        $categoryService->setManager($this->em);

        $category = 0;
        $tournament = 1;
        $this->games = $categoryService->generateDraw($inscriptions, 1, $category, $tournament);

        $this->assertEquals(json_encode($this->games), ' ');
    }

}
