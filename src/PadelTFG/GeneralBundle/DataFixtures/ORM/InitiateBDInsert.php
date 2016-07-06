<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Game;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\UserPreference;
use PadelTFG\GeneralBundle\Entity\UserStatus;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\Schedule;
use PadelTFG\GeneralBundle\Entity\ScheduleTrack;
use PadelTFG\GeneralBundle\Entity\ScheduleDate;
use PadelTFG\GeneralBundle\Entity\ScheduleRange;
use PadelTFG\GeneralBundle\Entity\ScheduleRangeDate;


class InitiateBDInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$repository = $manager->getRepository('GeneralBundle:UserRole');
		$playerRole = $repository->findOneByValue('Player');
		$tournamentAdminRole = $repository->findOneByValue('TournamentAdmin');
		$repository = $manager->getRepository('GeneralBundle:UserStatus');
		$userStatus = $repository->findOneByValue('Registered');

		$Users = array(
			array('name' => 'Natalia', 'lastName' => 'Perez', 'email' => 'nape@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Servando', 'lastName' => 'Escobar', 'email' => 'sees@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Victoria', 'lastName' => 'Abril', 'email' => 'viab@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Alberto', 'lastName' => 'Martinez', 'email' => 'alma@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Cecilia', 'lastName' => 'Soriano', 'email' => 'ceso@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Saray', 'lastName' => 'Lozano', 'email' => 'salo@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Ana', 'lastName' => 'Guardiola', 'email' => 'angu@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Andrea', 'lastName' => 'Garcia', 'email' => 'anga@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Rocio', 'lastName' => 'Lopez', 'email' => 'rolo@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Francisco', 'lastName' => 'Garcia', 'email' => 'frga@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Juan', 'lastName' => 'Martinez', 'email' => 'juma@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Macarena', 'lastName' => 'Villalon', 'email' => 'mavi@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Rodrigo', 'lastName' => 'Eduarte', 'email' => 'roed@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Lorenzo', 'lastName' => 'Pascual', 'email' => 'lopa@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Maria', 'lastName' => 'Gomez', 'email' => 'mago@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Angel', 'lastName' => 'Llacer', 'email' => 'anll@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Monica', 'lastName' => 'Naranjo', 'email' => 'mona@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Nerea', 'lastName' => 'Tomas', 'email' => 'neto@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Risto', 'lastName' => 'Mejide', 'email' => 'rime@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Jose', 'lastName' => 'Garcia', 'email' => 'joga@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Carlos', 'lastName' => 'Latre', 'email' => 'cala@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Silvia', 'lastName' => 'Abril', 'email' => 'siab@PadelTFG.com',
				'password' => 'password', 'gender' => 'Female'),
			array('name' => 'Santiago', 'lastName' => 'Segura', 'email' => 'sase@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male'),
			array('name' => 'Mario', 'lastName' => 'Vaquerizo', 'email' => 'mava@PadelTFG.com',
				'password' => 'password', 'gender' => 'Male')
			);

		foreach ($Users as $key) {
			$entity = new User();
			$entity->setName($key['name']);
			$entity->setLastName($key['lastName']);
			$entity->setEmail($key['email']);
			$entity->setPassword($key['password']);
			$entity->setGender($key['gender']);
			$entity->setStatus($userStatus);
			if($key['name'] == 'Alberto'){
				$entity->addRole($tournamentAdminRole);
				$entity->addRole($playerRole);
			}
			else{
				$entity->addRole($playerRole);
			}
			$manager->persist($entity);
			$manager->flush();
			$entityPreference = new UserPreference();
			$entityPreference->setId($entity->getId());
			$entityPreference->setName(true);
	        $entityPreference->setLastName(true);
	        $entityPreference->setEmail(true);
	        $entityPreference->setStatus(true);
	        $entityPreference->setFirstPhone(true);
	        $entityPreference->setCity(true);
	        $entityPreference->setSecondPhone(false);
	        $entityPreference->setRole(false);
	        $entityPreference->setAddress(false);
	        $entityPreference->setCountry(false);
	        $entityPreference->setCP(false);
	        $entityPreference->setBirthDate(false);
	        $entityPreference->setProfileImage(false);
	        $entityPreference->setGameLevel(false);
	        $entityPreference->setAlias(false);
	        $entityPreference->setNotification(false);
	        $entityPreference->setRegistrationDate(false);
	        $entityPreference->setGender(false);
	        $manager->persist($entityPreference);
		}
		$manager->flush();


		$repository = $manager->getRepository('GeneralBundle:User');
		$userAdmin = $repository->findOneByName('Alberto');

		$repository = $manager->getRepository('GeneralBundle:TournamentStatus');
		$tournamentStatusCreated = $repository->findOneByValue('Created');
		$tournamentStatusMatchs = $repository->findOneByValue('Group phase (Matchs done)');

		$Tournaments = array(
			array('name' => 'Torneo Zoombie', 'admin' => $userAdmin, 'regLimit' => 50, 'startIns' => new \DateTime('2016-06-01'), 'endIns' => new \DateTime('2016-06-07'), 'startGroup' => new \DateTime('2016-06-09'), 'endGroup' => new \DateTime('2016-06-15'), 'startFinal' => new \DateTime('2016-06-16'), 'endFinal' => new \DateTime('2016-06-20'), 'status' => $tournamentStatusMatchs),
			array('name' => 'Torneo UA', 'admin' => $userAdmin, 'regLimit' => 4, 'startIns' => new \DateTime('2016-06-01'), 'endIns' => new \DateTime('2016-06-07'), 'startGroup' => new \DateTime('2016-06-09'), 'endGroup' => new \DateTime('2016-06-15'), 'startFinal' => new \DateTime('2016-06-16'), 'endFinal' => new \DateTime('2016-06-20'), 'status' => $tournamentStatusCreated)
		);

		foreach ($Tournaments as $key) {
			$entity = new Tournament();
			$entity->setName($key['name']);
			$entity->setAdmin($key['admin']);
			$entity->setRegisteredLimit($key['regLimit']);
			$entity->setStartInscriptionDate($key['startIns']);
			$entity->setEndInscriptionDate($key['endIns']);
			$entity->setStartGroupDate($key['startGroup']);
			$entity->setEndGroupDate($key['endGroup']);
			$entity->setStartFinalDate($key['startFinal']);
			$entity->setEndFinalDate($key['endFinal']);
			$entity->setStatus($key['status']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:Tournament');
		$tournament1 = $repository->findOneByName('Torneo Zoombie');
		$tournament2 = $repository->findOneByName('Torneo UA');

		$Categories = array(
			array('name' => 'Category Femenina', 'gender' => 'Female', 'tournament' => $tournament1, 'bgColor' => '#1d1363'),
			array('name' => 'Category Masculina', 'gender' => 'Male', 'tournament' => $tournament1, 'bgColor' => '#BFFF00'),
			array('name' => 'Category Mixta', 'gender' => 'Mixed', 'tournament' => $tournament2, 'bgColor' => '#1d1363')
		);

		foreach ($Categories as $key) {
			$entity = new Category();
			$entity->setName($key['name']);
			$entity->setGender($key['gender']);
			$entity->setTournament($key['tournament']);
			$entity->setBgColor($key['bgColor']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:Category');
		$category1 = $repository->findOneByName('Category Femenina');
		$category2 = $repository->findOneByName('Category Masculina');
		$category3 = $repository->findOneByName('Category Mixta');

		$Groups = array(
			array('name' => 'Group 1F', 'category' => $category1, 'tournament' => $tournament1, 'numPairs' => 3),
			array('name' => 'Group 1M', 'category' => $category2, 'tournament' => $tournament1, 'numPairs' => 4),
			array('name' => 'Group 2M', 'category' => $category2, 'tournament' => $tournament1, 'numPairs' => 4),
			array('name' => 'Group 3M', 'category' => $category2, 'tournament' => $tournament1, 'numPairs' => 4)
		);

		foreach ($Groups as $key) {
			$entity = new GroupCategory();
			$entity->setName($key['name']);
			$entity->setTournament($key['tournament']);
			$entity->setCategory($key['category']);
			$entity->setNumPairs($key['numPairs']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:GroupCategory');
		$group1F = $repository->findOneByName('Group 1F');
		$group1M = $repository->findOneByName('Group 1M');
		$group2M = $repository->findOneByName('Group 2M');
		$group3M = $repository->findOneByName('Group 3M');


		$repository = $manager->getRepository('GeneralBundle:User');
		$user1Pair1 = $repository->findOneByName('Natalia');
		$user2Pair1 = $repository->findOneByName('Victoria');
		$user1Pair2 = $repository->findOneByName('Alberto');
		$user2Pair2 = $repository->findOneByName('Cecilia');
		$user1Pair3 = $repository->findOneByName('Saray');
		$user2Pair3 = $repository->findOneByName('Ana');
		$user1Pair4 = $repository->findOneByName('Andrea');
		$user2Pair4 = $repository->findOneByName('Rocio');
		$user1Pair5 = $repository->findOneByName('Francisco');
		$user2Pair5 = $repository->findOneByName('Juan');
		$user1Pair6 = $repository->findOneByName('Macarena');
		$user2Pair6 = $repository->findOneByName('Rodrigo');
		$user1Pair7 = $repository->findOneByName('Lorenzo');
		$user2Pair7 = $repository->findOneByName('Maria');
		$user1Pair8 = $repository->findOneByName('Angel');
		$user2Pair8 = $repository->findOneByName('Monica');
		$user1Pair9 = $repository->findOneByName('Nerea');
		$user2Pair9 = $repository->findOneByName('Risto');
		$user1Pair10 = $repository->findOneByName('Jose');
		$user2Pair10 = $repository->findOneByName('Carlos');
		$user1Pair11 = $repository->findOneByName('Silvia');
		$user2Pair11 = $repository->findOneByName('Santiago');
		$user1Pair12 = $repository->findOneByName('Mario');
		$user2Pair12 = $repository->findOneByName('Servando');

		$Pairs = array(
			array('user1' => $user1Pair1, 'user2' => $user2Pair1, 'gender' => 'Female'),
			array('user1' => $user1Pair2, 'user2' => $user2Pair2, 'gender' => 'Mixed'),
			array('user1' => $user1Pair3, 'user2' => $user2Pair3, 'gender' => 'Female'),
			array('user1' => $user1Pair4, 'user2' => $user2Pair4, 'gender' => 'Female'),
			array('user1' => $user1Pair5, 'user2' => $user2Pair5, 'gender' => 'Male'),
			array('user1' => $user1Pair6, 'user2' => $user2Pair6, 'gender' => 'Mixed'),
			array('user1' => $user1Pair7, 'user2' => $user2Pair7, 'gender' => 'Mixed'),
			array('user1' => $user1Pair8, 'user2' => $user2Pair8, 'gender' => 'Mixed'),
			array('user1' => $user1Pair9, 'user2' => $user2Pair9, 'gender' => 'Mixed'),
			array('user1' => $user1Pair10, 'user2' => $user2Pair10, 'gender' => 'Male'),
			array('user1' => $user1Pair11, 'user2' => $user2Pair11, 'gender' => 'Mixed'),
			array('user1' => $user1Pair12, 'user2' => $user2Pair12, 'gender' => 'Male')
		);

		foreach ($Pairs as $key) {
			$entity = new Pair();
			$entity->setUser1($key['user1']);
			$entity->setUser2($key['user2']);
			$entity->setGender($key['gender']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:Pair');
		$pair1 = $repository->findOneByUser1($user1Pair1);
		$pair2 = $repository->findOneByUser1($user1Pair2);
		$pair3 = $repository->findOneByUser1($user1Pair3);
		$pair4 = $repository->findOneByUser1($user1Pair4);
		$pair5 = $repository->findOneByUser1($user1Pair5);
		$pair6 = $repository->findOneByUser1($user1Pair6);
		$pair7 = $repository->findOneByUser1($user1Pair7);
		$pair8 = $repository->findOneByUser1($user1Pair8);
		$pair9 = $repository->findOneByUser1($user1Pair9);
		$pair10 = $repository->findOneByUser1($user1Pair10);
		$pair11 = $repository->findOneByUser1($user1Pair11);
		$pair12 = $repository->findOneByUser1($user1Pair12);

		$repository = $manager->getRepository('GeneralBundle:InscriptionStatus');
		$inscriptionStatusNotClassified = $repository->findOneByValue('Not Classified');

		$Inscriptions = array(
			array('pair' => $pair1, 'tournament' => $tournament1, 'category' => $category1, 'group' => $group1F),
			array('pair' => $pair3, 'tournament' => $tournament1, 'category' => $category1, 'group' => $group1F),
			array('pair' => $pair4, 'tournament' => $tournament1, 'category' => $category1, 'group' => $group1F),
			array('pair' => $pair1, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group1M),
			array('pair' => $pair2, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group1M),
			array('pair' => $pair3, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group1M),
			array('pair' => $pair4, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group1M),
			array('pair' => $pair5, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group2M),
			array('pair' => $pair6, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group2M),
			array('pair' => $pair7, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group2M),
			array('pair' => $pair8, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group2M),
			array('pair' => $pair9, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group3M),
			array('pair' => $pair10, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group3M),
			array('pair' => $pair11, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group3M),
			array('pair' => $pair12, 'tournament' => $tournament1, 'category' => $category2, 'group' => $group3M)
		);

		foreach ($Inscriptions as $key) {
			$entity = new Inscription();
			$entity->setPair($key['pair']);
			$entity->setTournament($key['tournament']);
			$entity->setCategory($key['category']);
			$entity->setGroup($key['group']);
			$entity->setHasObservations(false);
			$entity->setStatus($inscriptionStatusNotClassified);
			$manager->persist($entity);	
		}
		$manager->flush();

		$schedule = new Schedule();
		$schedule->setStartDate('2016-06-10');
		$schedule->setMaxRange('11:00:00');
		$schedule->setMinRange('09:00:00');
		$schedule->setTournament($tournament1);
		$manager->persist($schedule);
		$manager->flush();

		$tournament1->setSchedule($schedule);
		$manager->persist($tournament1);
		$manager->flush();

		$Tracks = array(
			array('title' => 'Track 1', 'schedule' => $schedule),
			array('title' => 'Track 2', 'schedule' => $schedule),
			array('title' => 'Track 3', 'schedule' => $schedule),
			array('title' => 'Track 4', 'schedule' => $schedule)
		);

		foreach ($Tracks as $key) {
			$entity = new ScheduleTrack();
			$entity->setTitle($key['title']);
			$entity->setSchedule($key['schedule']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$scheduleRangeDate = new ScheduleRangeDate();
		$scheduleRangeDate->setSchedule($schedule);
		$manager->persist($scheduleRangeDate);	
		$manager->flush();

		$ScheduleDate = array(
			array('date' => new \DateTime('2016-06-10 00:00:00'), 'scheduleRangeDate' => $scheduleRangeDate),
			array('date' => new \DateTime('2016-06-11 00:00:00'), 'scheduleRangeDate' => $scheduleRangeDate),
			array('date' => new \DateTime('2016-06-12 00:00:00'), 'scheduleRangeDate' => $scheduleRangeDate)
		);

		foreach ($ScheduleDate as $key) {
			$entity = new ScheduleDate();
			$entity->setScheduleRangeDate($key['scheduleRangeDate']);
			$entity->setDate($key['date']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$ScheduleRange = array(
			array('fromHour' => 'T09:00:00', 'toHour' => 'T10:00:00', 'scheduleRangeDate' => $scheduleRangeDate),
			array('fromHour' => 'T10:00:00', 'toHour' => 'T11:00:00', 'scheduleRangeDate' => $scheduleRangeDate)
		);

		foreach ($ScheduleRange as $key) {
			$entity = new ScheduleRange();
			$entity->setScheduleRangeDate($key['scheduleRangeDate']);
			$entity->setToHour($key['toHour']);
			$entity->setFromHour($key['fromHour']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:ScheduleTrack');
		$track1 = $repository->findOneByTitle('Track 1');
		$track2 = $repository->findOneByTitle('Track 2');
		$track3 = $repository->findOneByTitle('Track 3');
		$track4 = $repository->findOneByTitle('Track 4');

		$schedule->setScheduleJson('[{"id":"1","start":"2016-06-10T09:00:00","end":"2016-06-10T10:00:00","resourceId":' . $track1->getId() . ',"title":"Natalia-Victoria\\nSaray-Ana","backgroundColor":"#1d1363"},{"id":"2","start":"2016-06-10T09:00:00","end":"2016-06-10T10:00:00","resourceId":' . $track2->getId() . ',"title":"Natalia-Victoria\\nAndrea-Rocio","backgroundColor":"#1d1363"},{"id":"3","start":"2016-06-10T09:00:00","end":"2016-06-10T10:00:00","resourceId":' . $track3->getId() . ',"title":"Saray-Ana\\nAndrea-Rocio","backgroundColor":"#1d1363"},{"id":"4","start":"2016-06-10T09:00:00","end":"2016-06-10T10:00:00","resourceId":' . $track4->getId() . ',"title":"Natalia-Victoria\\nAlberto-Cecilia","backgroundColor":"#BFFF00"},{"id":"5","start":"2016-06-10T10:00:00","end":"2016-06-10T11:00:00","resourceId":' . $track1->getId() . ',"title":"Natalia-Victoria\\nSaray-Ana","backgroundColor":"#BFFF00"},{"id":"6","start":"2016-06-10T10:00:00","end":"2016-06-10T11:00:00","resourceId":' . $track2->getId() . ',"title":"Natalia-Victoria\\nJose-Carlos","backgroundColor":"#BFFF00"},{"id":"7","start":"2016-06-10T10:00:00","end":"2016-06-10T11:00:00","resourceId":' . $track3->getId() . ',"title":"Alberto-Cecilia\\nSaray-Ana","backgroundColor":"#BFFF00"},{"id":"8","start":"2016-06-10T10:00:00","end":"2016-06-10T11:00:00","resourceId":' . $track4->getId() . ',"title":"Alberto-Cecilia\\nJose-Carlos","backgroundColor":"#BFFF00"},{"id":"9","start":"2016-06-11T09:00:00","end":"2016-06-11T10:00:00","resourceId":' . $track1->getId() . ',"title":"Saray-Ana\\nJose-Carlos","backgroundColor":"#BFFF00"},{"id":"10","start":"2016-06-11T09:00:00","end":"2016-06-11T10:00:00","resourceId":' . $track2->getId() . ',"title":"Andrea-Rocio\\nFrancisco-Juan","backgroundColor":"#BFFF00"},{"id":"11","start":"2016-06-11T09:00:00","end":"2016-06-11T10:00:00","resourceId":' . $track3->getId() . ',"title":"Andrea-Rocio\\nMacarena-Rodrigo","backgroundColor":"#BFFF00"},{"id":"12","start":"2016-06-11T09:00:00","end":"2016-06-11T10:00:00","resourceId":' . $track4->getId() . ',"title":"Andrea-Rocio\\nSilvia-Santiago","backgroundColor":"#BFFF00"},{"id":"13","start":"2016-06-11T10:00:00","end":"2016-06-11T11:00:00","resourceId":' . $track1->getId() . ',"title":"Francisco-Juan\\nMacarena-Rodrigo","backgroundColor":"#BFFF00"},{"id":"14","start":"2016-06-11T10:00:00","end":"2016-06-11T11:00:00","resourceId":' . $track2->getId() . ',"title":"Francisco-Juan\\nSilvia-Santiago","backgroundColor":"#BFFF00"},{"id":"15","start":"2016-06-11T10:00:00","end":"2016-06-11T11:00:00","resourceId":' . $track3->getId() . ',"title":"Macarena-Rodrigo\\nSilvia-Santiago","backgroundColor":"#BFFF00"},{"id":"16","start":"2016-06-11T10:00:00","end":"2016-06-11T11:00:00","resourceId":' . $track4->getId() . ',"title":"Lorenzo-Maria\\nAngel-Monica","backgroundColor":"#BFFF00"},{"id":"17","start":"2016-06-12T09:00:00","end":"2016-06-12T10:00:00","resourceId":' . $track1->getId() . ',"title":"Lorenzo-Maria\\nNerea-Risto","backgroundColor":"#BFFF00"},{"id":"18","start":"2016-06-12T09:00:00","end":"2016-06-12T10:00:00","resourceId":' . $track2->getId() . ',"title":"Lorenzo-Maria\\nMario-Servando","backgroundColor":"#BFFF00"},{"id":"19","start":"2016-06-12T09:00:00","end":"2016-06-12T10:00:00","resourceId":' . $track3->getId() . ',"title":"Angel-Monica\\nNerea-Risto","backgroundColor":"#BFFF00"},{"id":"20","start":"2016-06-12T09:00:00","end":"2016-06-12T10:00:00","resourceId":' . $track4->getId() . ',"title":"Angel-Monica\\nMario-Servando","backgroundColor":"#BFFF00"},{"id":"21","start":"2016-06-12T10:00:00","end":"2016-06-12T11:00:00","resourceId":' . $track1->getId() . ',"title":"Nerea-Risto\\nMario-Servando","backgroundColor":"#BFFF00"},{"id":"22","start":"2016-06-12T10:00:00","end":"2016-06-12T11:00:00","resourceId":' . $track2->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"23","start":"2016-06-12T10:00:00","end":"2016-06-12T11:00:00","resourceId":' . $track3->getId() . ',"title":"Not Set","backgroundColor":"Not Set"},{"id":"24","start":"2016-06-12T10:00:00","end":"2016-06-12T11:00:00","resourceId":' . $track4->getId() . ',"title":"Not Set","backgroundColor":"Not Set"}]');
		$schedule->setScheduleResourcesJson('[{"id":' . $track1->getId() . ',"title":"Track 1"},{"id":' . $track2->getId() . ',"title":"Track 2"},{"id":' . $track3->getId() . ',"title":"Track 3"},{"id":' . $track4->getId() . ',"title":"Track 4"}]');

		$manager->persist($schedule);
		$manager->flush();


		$repository = $manager->getRepository('GeneralBundle:GameStatus');
		$gameStatus = $repository->findOneByValue('Created');
	
		$Games = array(
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category1, 'group' => $group1F, 'startDate' => '2016-06-10T09:00:00', 'endDate' => '2016-06-10T10:00:00', 'pair1' => $pair1, 'pair2' => $pair3, 'numOfScheduleRange' => 1, 'bgColor' => $category1->getBgColor(), 'score' => '6/4 7/5', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category1, 'group' => $group1F, 'startDate' => '2016-06-10T09:00:00', 'endDate' => '2016-06-10T10:00:00', 'pair1' => $pair1, 'pair2' => $pair4, 'numOfScheduleRange' => 2, 'bgColor' => $category1->getBgColor(), 'score' => '3/6 2/6', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category1, 'group' => $group1F, 'startDate' => '2016-06-10T09:00:00', 'endDate' => '2016-06-10T10:00:00', 'pair1' => $pair3, 'pair2' => $pair4, 'numOfScheduleRange' => 3, 'bgColor' => $category1->getBgColor(), 'score' => '6/1 4/6 6/1', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group1M, 'startDate' => '2016-06-10T09:00:00', 'endDate' => '2016-06-10T10:00:00', 'pair1' => $pair1, 'pair2' => $pair2, 'numOfScheduleRange' => 4, 'bgColor' => $category2->getBgColor(), 'score' => '0/6 0/6', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group1M, 'startDate' => '2016-06-10T09:00:00', 'endDate' => '2016-06-10T10:00:00', 'pair1' => $pair1, 'pair2' => $pair3, 'numOfScheduleRange' => 5, 'bgColor' => $category2->getBgColor(), 'score' => '4/6 1/6', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group1M, 'startDate' => '2016-06-10T09:00:00', 'endDate' => '2016-06-10T10:00:00', 'pair1' => $pair1, 'pair2' => $pair4, 'numOfScheduleRange' => 6, 'bgColor' => $category2->getBgColor(), 'score' => '6/3 6/2', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group1M, 'startDate' => '2016-06-10T09:00:00', 'endDate' => '2016-06-10T10:00:00', 'pair1' => $pair2, 'pair2' => $pair3, 'numOfScheduleRange' => 7, 'bgColor' => $category2->getBgColor(), 'score' => '6/4 1/6 6/4', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group1M, 'startDate' => '2016-06-10T09:00:00', 'endDate' => '2016-06-10T10:00:00', 'pair1' => $pair2, 'pair2' => $pair4, 'numOfScheduleRange' => 8, 'bgColor' => $category2->getBgColor(), 'score' => '3/6 7/5 1/6', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group1M, 'startDate' => '2016-06-11T09:00:00', 'endDate' => '2016-06-11T10:00:00', 'pair1' => $pair3, 'pair2' => $pair4, 'numOfScheduleRange' => 9, 'bgColor' => $category2->getBgColor(), 'score' => '6/4 7/5', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group2M, 'startDate' => '2016-06-11T09:00:00', 'endDate' => '2016-06-11T10:00:00', 'pair1' => $pair5, 'pair2' => $pair6, 'numOfScheduleRange' => 10, 'bgColor' => $category2->getBgColor(), 'score' => '6/4 7/5', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group2M, 'startDate' => '2016-06-11T09:00:00', 'endDate' => '2016-06-11T10:00:00', 'pair1' => $pair5, 'pair2' => $pair7, 'numOfScheduleRange' => 11, 'bgColor' => $category2->getBgColor(), 'score' => '6/1 2/6 6/1', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group2M, 'startDate' => '2016-06-11T09:00:00', 'endDate' => '2016-06-11T10:00:00', 'pair1' => $pair5, 'pair2' => $pair8, 'numOfScheduleRange' => 12, 'bgColor' => $category2->getBgColor(), 'score' => '6/4 7/5', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group2M, 'startDate' => '2016-06-11T09:00:00', 'endDate' => '2016-06-11T10:00:00', 'pair1' => $pair6, 'pair2' => $pair7, 'numOfScheduleRange' => 13, 'bgColor' => $category2->getBgColor(), 'score' => '6/1 6/2', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group2M, 'startDate' => '2016-06-11T09:00:00', 'endDate' => '2016-06-11T10:00:00', 'pair1' => $pair6, 'pair2' => $pair8, 'numOfScheduleRange' => 14, 'bgColor' => $category2->getBgColor(), 'score' => '2/6 7/5 5/7', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group2M, 'startDate' => '2016-06-11T09:00:00', 'endDate' => '2016-06-11T10:00:00', 'pair1' => $pair7, 'pair2' => $pair8, 'numOfScheduleRange' => 15, 'bgColor' => $category2->getBgColor(), 'score' => '6/4 7/5', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group3M, 'startDate' => '2016-06-11T09:00:00', 'endDate' => '2016-06-11T10:00:00', 'pair1' => $pair9, 'pair2' => $pair10, 'numOfScheduleRange' => 16, 'bgColor' => $category2->getBgColor(), 'score' => '6/7 6/7', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group3M, 'startDate' => '2016-06-12T09:00:00', 'endDate' => '2016-06-12T10:00:00', 'pair1' => $pair9, 'pair2' => $pair11, 'numOfScheduleRange' => 17, 'bgColor' => $category2->getBgColor(), 'score' => '1/6 7/6 5/7', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group3M, 'startDate' => '2016-06-12T09:00:00', 'endDate' => '2016-06-12T10:00:00', 'pair1' => $pair9, 'pair2' => $pair12, 'numOfScheduleRange' => 18, 'bgColor' => $category2->getBgColor(), 'score' => '6/4 7/5', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group3M, 'startDate' => '2016-06-12T09:00:00', 'endDate' => '2016-06-12T10:00:00', 'pair1' => $pair10, 'pair2' => $pair11, 'numOfScheduleRange' => 19, 'bgColor' => $category2->getBgColor(), 'score' => '6/4 7/5', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group3M, 'startDate' => '2016-06-12T09:00:00', 'endDate' => '2016-06-12T10:00:00', 'pair1' => $pair10, 'pair2' => $pair12, 'numOfScheduleRange' => 20, 'bgColor' => $category2->getBgColor(), 'score' => '6/4 7/5', 'isDrawGame' => false),
			array('description' => 'torneo zoombie', 'tournament' => $tournament1, 'category' => $category2, 'group' => $group3M, 'startDate' => '2016-06-12T09:00:00', 'endDate' => '2016-06-12T10:00:00', 'pair1' => $pair11, 'pair2' => $pair12, 'numOfScheduleRange' => 21, 'bgColor' => $category2->getBgColor(), 'score' => '6/4 7/5', 'isDrawGame' => false)

		);

		foreach ($Games as $key) {
			$entity = new Game();
			$entity->setDescription($key['description']);
			$entity->setTournament($key['tournament']);
			$entity->setCategory($key['category']);
			$entity->setGroup($key['group']);
			$entity->setStartDate($key['startDate']);
			$entity->setEndDate($key['endDate']);
			$entity->setPair1($key['pair1']);
			$entity->setPair2($key['pair2']);
			$entity->setScheduleId($key['numOfScheduleRange']);
			$entity->setScore($key['score']);
			$entity->setBgColor($key['bgColor']);
			$entity->setStatus($gameStatus);
			$entity->setIsDrawGame($key['isDrawGame']);
			$manager->persist($entity);	
		}
		$manager->flush();
	}
}
