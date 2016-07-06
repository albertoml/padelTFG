<?php

namespace PadelTFG\GeneralBundle\Tests\ServiceTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Game;
use PadelTFG\GeneralBundle\Entity\Schedule;
use PadelTFG\GeneralBundle\Entity\ScheduleDate;
use PadelTFG\GeneralBundle\Entity\ScheduleRange;
use PadelTFG\GeneralBundle\Entity\ScheduleRangeDate;
use PadelTFG\GeneralBundle\Entity\ScheduleTrack;
use PadelTFG\GeneralBundle\Service\ScheduleService as ScheduleService;
use PadelTFG\GeneralBundle\Service\ScheduleTrackService as ScheduleTrackService;
use PadelTFG\GeneralBundle\Service\ScheduleDateService as ScheduleDateService;
use PadelTFG\GeneralBundle\Service\ScheduleRangeService as ScheduleRangeService;
use PadelTFG\GeneralBundle\Service\ScheduleRangeDateService as ScheduleRangeDateService;

class ScheduleServiceTest extends WebTestCase
{
	private $em;
	private $client;

	public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    	
        $this->tournament = new Tournament();
        $this->tournament1 = new Tournament();
    	$this->tournament2 = new Tournament();
    	$this->category = new Category();
        $this->admin = new User();
        $this->user1 = new User();
        $this->user2 = new User();
        $this->user3 = new User();
    	$this->user4 = new User();
        $this->pair1 = new Pair();
        $this->pair2 = new Pair();
        $this->game1 = new Game();
        $this->game2 = new Game();
        $this->game3 = new Game();
        $this->game4 = new Game();
        $this->game5 = new Game();

		$this->admin->setName('UserTournamentTest');
		$this->admin->setLastName('UserLastName');
		$this->admin->setEmail('emailTournamentTest');
		$this->admin->setPassword('password');

        $this->tournament->setName($this->admin);
        $this->tournament1->setName($this->admin);
    	$this->tournament2->setName($this->admin);
        $this->tournament->setName('Tournament');
        $this->tournament1->setName('Tournament1');
    	$this->tournament2->setName('Tournament2');
    	$this->category->setTournament($this->tournament);
    	$this->category->setName('Category');

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

        $this->pair1->setUser1($this->user1);
        $this->pair1->setUser2($this->user2);
        $this->pair2->setUser1($this->user3);
        $this->pair2->setUser2($this->user4);

        $this->game1->setPair1($this->pair1);
        $this->game1->setPair2($this->pair2);
        $this->game1->setBgColor('negro');
        $this->game2->setPair1($this->pair1);
        $this->game2->setPair2($this->pair2);
        $this->game2->setBgColor('blanco');
        $this->game3->setPair1($this->pair1);
        $this->game3->setPair2($this->pair2);
        $this->game3->setBgColor('gris');
        $this->game4->setPair1($this->pair1);
        $this->game4->setPair2($this->pair2);
        $this->game4->setBgColor('amarillo');
        $this->game5->setPair1($this->pair1);
        $this->game5->setPair2($this->pair2);
        $this->game5->setBgColor('rojo');

    	$this->em->persist($this->admin);
    	$this->em->persist($this->tournament);
        $this->em->persist($this->category);
        $this->em->persist($this->user1);
        $this->em->persist($this->user2);
        $this->em->persist($this->user3);
        $this->em->persist($this->user4);
        $this->em->persist($this->pair1);
        $this->em->persist($this->pair2);
        $this->em->persist($this->game1);
        $this->em->persist($this->game2);
        $this->em->persist($this->game3);
        $this->em->persist($this->game4);
        $this->em->persist($this->game5);
    	$this->em->flush();

        $this->schedule = new Schedule();
        $this->schedule->setTournament($this->tournament);
        $this->em->persist($this->schedule);
        $this->em->flush();

        $this->scheduleTrack1 = new ScheduleTrack();
        $this->scheduleTrack2 = new ScheduleTrack();
        $this->scheduleTrack3 = new ScheduleTrack();
        $this->scheduleTrack1->setSchedule($this->schedule);
        $this->scheduleTrack2->setSchedule($this->schedule);
        $this->scheduleTrack3->setSchedule($this->schedule);
        $this->scheduleTrack1->setTitle("TestTrack 1");
        $this->scheduleTrack2->setTitle("TestTrack 2");
        $this->scheduleTrack3->setTitle("TestTrack 3");
        $this->em->persist($this->scheduleTrack1);
        $this->em->persist($this->scheduleTrack2);
        $this->em->persist($this->scheduleTrack3);
        $this->em->flush();

        $this->scheduleRangeDate1 = new ScheduleRangeDate();
        $this->scheduleRangeDate2 = new ScheduleRangeDate();
        $this->scheduleRangeDate1->setSchedule($this->schedule);
        $this->scheduleRangeDate2->setSchedule($this->schedule);
        $this->em->persist($this->scheduleRangeDate1);
        $this->em->persist($this->scheduleRangeDate2);
        $this->em->flush();

        $this->scheduleDate1 = new ScheduleDate();
        $this->scheduleDate2 = new ScheduleDate();
        $this->scheduleDate3 = new ScheduleDate();
        $this->scheduleDate1->setScheduleRangeDate($this->scheduleRangeDate1);
        $this->scheduleDate2->setScheduleRangeDate($this->scheduleRangeDate2);
        $this->scheduleDate3->setScheduleRangeDate($this->scheduleRangeDate2);
        $this->scheduleDate1->setDate(new \DateTime('2016-08-24'));
        $this->scheduleDate2->setDate(new \DateTime('2016-08-25'));
        $this->scheduleDate3->setDate(new \DateTime('2016-08-26'));
        $this->em->persist($this->scheduleDate1);
        $this->em->persist($this->scheduleDate2);
        $this->em->persist($this->scheduleDate3);
        $this->em->flush();

        $this->scheduleRange1 = new ScheduleRange();
        $this->scheduleRange2 = new ScheduleRange();
        $this->scheduleRange3 = new ScheduleRange();
        $this->scheduleRange4 = new ScheduleRange();
        $this->scheduleRange1->setScheduleRangeDate($this->scheduleRangeDate1);
        $this->scheduleRange2->setScheduleRangeDate($this->scheduleRangeDate1);
        $this->scheduleRange3->setScheduleRangeDate($this->scheduleRangeDate1);
        $this->scheduleRange4->setScheduleRangeDate($this->scheduleRangeDate2);
        $this->scheduleRange1->setFromHour("T9:00:00");
        $this->scheduleRange2->setFromHour("T11:00:00");
        $this->scheduleRange3->setFromHour("T12:00:00");
        $this->scheduleRange4->setFromHour("T12:00:00");
        $this->scheduleRange1->setToHour("T10:00:00");
        $this->scheduleRange2->setToHour("T12:00:00");
        $this->scheduleRange3->setToHour("T13:00:00");
        $this->scheduleRange4->setToHour("T13:00:00");
        $this->em->persist($this->scheduleRange1);
        $this->em->persist($this->scheduleRange2);
        $this->em->persist($this->scheduleRange3);
        $this->em->persist($this->scheduleRange4);
        $this->em->flush();
    }

    protected function tearDown()
    {
        $this->em->remove($this->admin);
        $this->em->remove($this->user1);
        $this->em->remove($this->user2);
        $this->em->remove($this->user3);
        $this->em->remove($this->user4);
        $this->em->remove($this->tournament);
        $this->em->remove($this->tournament1);
    	$this->em->remove($this->tournament2);
    	$this->em->remove($this->category);
    	$this->em->flush();
    }

    public function testScheduleCompose()
    {
    	$scheduleService = new ScheduleService();
    	$scheduleService->setManager($this->em);
        $scheduleItem = $scheduleService->scheduleCompose($this->schedule->getId(), null, 0);

    	$this->assertEquals('{"scheduleTest":[{"id":"1","start":"2016-08-24T9:00:00","end":"2016-08-24T10:00:00","resourceId":' . $this->scheduleTrack1->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"2","start":"2016-08-24T9:00:00","end":"2016-08-24T10:00:00","resourceId":' . $this->scheduleTrack2->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"3","start":"2016-08-24T9:00:00","end":"2016-08-24T10:00:00","resourceId":' . $this->scheduleTrack3->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"4","start":"2016-08-24T11:00:00","end":"2016-08-24T12:00:00","resourceId":' . $this->scheduleTrack1->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"5","start":"2016-08-24T11:00:00","end":"2016-08-24T12:00:00","resourceId":' . $this->scheduleTrack2->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"6","start":"2016-08-24T11:00:00","end":"2016-08-24T12:00:00","resourceId":' . $this->scheduleTrack3->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"7","start":"2016-08-24T12:00:00","end":"2016-08-24T13:00:00","resourceId":' . $this->scheduleTrack1->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"8","start":"2016-08-24T12:00:00","end":"2016-08-24T13:00:00","resourceId":' . $this->scheduleTrack2->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"9","start":"2016-08-24T12:00:00","end":"2016-08-24T13:00:00","resourceId":' . $this->scheduleTrack3->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"10","start":"2016-08-25T12:00:00","end":"2016-08-25T13:00:00","resourceId":' . $this->scheduleTrack1->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"11","start":"2016-08-25T12:00:00","end":"2016-08-25T13:00:00","resourceId":' . $this->scheduleTrack2->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"12","start":"2016-08-25T12:00:00","end":"2016-08-25T13:00:00","resourceId":' . $this->scheduleTrack3->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"13","start":"2016-08-26T12:00:00","end":"2016-08-26T13:00:00","resourceId":' . $this->scheduleTrack1->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"14","start":"2016-08-26T12:00:00","end":"2016-08-26T13:00:00","resourceId":' . $this->scheduleTrack2->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"15","start":"2016-08-26T12:00:00","end":"2016-08-26T13:00:00","resourceId":' . $this->scheduleTrack3->getId() . ',"title":"Not Set","backgroundColor":"Not Set"}]}',
        json_encode(array('scheduleTest' => $scheduleItem)));
    }

    public function testInsertInSchedule(){

        $scheduleService = new ScheduleService();
        $scheduleService->setManager($this->em);
        $scheduleCompose = $scheduleService->scheduleCompose($this->schedule->getId(), null, 0);
        $schedule = $scheduleService->insertInSchedule($this->game1, $scheduleCompose, $this->schedule);


        $this->assertNotContains('"id":"1"', json_encode($schedule));
        $this->assertContains('2016-08-24T9:00:00', $this->game1->getStartDate());
        $this->assertContains('2016-08-24T10:00:00', $this->game1->getEndDate());
    }

    public function testSetDatesToMatchsInTournament(){

        $scheduleService = new ScheduleService();
        $scheduleService->setManager($this->em);
        $gamesInTournament = array($this->game1, $this->game2, $this->game3, $this->game4, $this->game5);
        $scheduleItem = $scheduleService->setDatesToMatchsInTournament($gamesInTournament, null, $this->tournament->getId());

        $this->assertContains('negro', $scheduleItem);
        $this->assertContains('amarillo', $scheduleItem);
        $this->assertContains('blanco', $scheduleItem);
        $this->assertContains('rojo', $scheduleItem);
        $this->assertContains('gris', $scheduleItem);
    }

    public function testScheduleCalculateStartDate(){

        $scheduleService = new ScheduleService();
        $scheduleService->setManager($this->em);
        $startDate = $scheduleService->scheduleCalculateStartDate($this->schedule->getId());

        $this->assertEquals('2016-08-24', $startDate);
    }

    public function testScheduleCalculateMaxRange(){

        $scheduleService = new ScheduleService();
        $scheduleService->setManager($this->em);
        $maxRange = $scheduleService->scheduleCalculateMaxRange($this->schedule->getId());

        $this->assertEquals('13:00:00', $maxRange);
    }

    public function testScheduleCalculateMinRange(){

        $scheduleService = new ScheduleService();
        $scheduleService->setManager($this->em);
        $minRange = $scheduleService->scheduleCalculateMinRange($this->schedule->getId());

        $this->assertEquals('9:00:00', $minRange);
    }

    public function testGetScheduleRangeDate(){

        $sheduleRangeDateService = new ScheduleRangeDateService();
        $sheduleRangeDateService->setManager($this->em);
        $scheduleRangeDate = $sheduleRangeDateService->getScheduleRangeDate($this->scheduleRangeDate1->getId());

        $this->assertContains('' . $this->schedule->getId(), json_encode($scheduleRangeDate));
    }

    public function testGetScheduleDate(){

        $sheduleDateService = new ScheduleDateService();
        $sheduleDateService->setManager($this->em);
        $scheduleDate = $sheduleDateService->getScheduleDate($this->scheduleDate1->getId());

        $this->assertContains('2016-08-24', json_encode($scheduleDate));
    }

    public function testGetScheduleRange(){

        $scheduleRangeService = new ScheduleRangeService();
        $scheduleRangeService->setManager($this->em);
        $scheduleRange = $scheduleRangeService->getScheduleRange($this->scheduleRange1->getId());

        $this->assertContains('T9:00:00', json_encode($scheduleRange));
        $this->assertContains('T10:00:00', json_encode($scheduleRange));
    }

    public function testGetScheduleTrack(){

        $scheduleTrackService = new ScheduleTrackService();
        $scheduleTrackService->setManager($this->em);
        $scheduleRange = $scheduleTrackService->getScheduleTrack($this->scheduleTrack1->getId());

        $this->assertContains('TestTrack 1', json_encode($scheduleRange));
    }

    public function testSaveScheduleIdInt(){
        $sheduleService = new ScheduleService();
        $sheduleService->setManager($this->em);
        $params = array('tournament' => $this->tournament1->getId());
        $schedule= $sheduleService->saveSchedule($params);

        $this->assertContains('ok', json_encode($schedule));
        $this->em->remove($schedule['message']);
    }

    public function testSaveScheduleDate(){
        $sheduleDateService = new ScheduleDateService();
        $sheduleDateService->setManager($this->em);
        $scheduleDate = $sheduleDateService->saveScheduleDate($this->scheduleRangeDate1, '2016-08-26');

        $this->assertContains('2016-08-26', json_encode($scheduleDate));
    }

    public function testSaveScheduleRange(){
        $scheduleRangeService = new ScheduleRangeService();
        $scheduleRangeService->setManager($this->em);
        $params = array('toHour' => 'T12:00:00', 'fromHour' => 'T18:00:00');
        $scheduleRange = $scheduleRangeService->saveScheduleRange($this->scheduleRangeDate1, $params);

        $this->assertContains('T12:00:00', json_encode($scheduleRange));
        $this->assertContains('T18:00:00', json_encode($scheduleRange));
    }

    public function testSaveScheduleRangeDate(){
        $sheduleRangeDateService = new ScheduleRangeDateService();
        $sheduleRangeDateService->setManager($this->em);
        $scheduleDate = $sheduleRangeDateService->saveScheduleRangeDate($this->schedule);

        $this->assertContains('' . $this->schedule->getId(), json_encode($scheduleDate));
    }

    public function testSaveScheduleTrack(){
        $scheduleTrackService = new ScheduleTrackService();
        $scheduleTrackService->setManager($this->em);
        $scheduleTrack = $scheduleTrackService->saveScheduleTrack($this->schedule, 3);

        $this->assertContains('Track 3', json_encode($scheduleTrack));
    }
}
