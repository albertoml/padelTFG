<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\UserPreference;
use PadelTFG\GeneralBundle\Entity\UserStatus;
use PadelTFG\GeneralBundle\Entity\UserUserRole;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Inscription;


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
			$manager->persist($entity);
			$manager->flush();
			$entityRole = new UserUserRole();
			$entityRole->setId($entity->getId());
			if($key['name'] == 'Alberto'){
				$entityRole->setRole($tournamentAdminRole);
			}
			else{
				$entityRole->setRole($playerRole);
			}
			$manager->persist($entityRole);
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
		$tournamentStatus = $repository->findOneByValue('Created');

		$Tournaments = array(
			array('name' => 'Torneo Zoombie', 'admin' => $userAdmin, 'regLimit' => 50, 'startIns' => new \DateTime('2016-06-01'), 'endIns' => new \DateTime('2016-06-07'), 'startGroup' => new \DateTime('2016-06-09'), 'endGroup' => new \DateTime('2016-06-15'), 'startFinal' => new \DateTime('2016-06-16'), 'endFinal' => new \DateTime('2016-06-20')),
			array('name' => 'Torneo UA', 'admin' => $userAdmin, 'regLimit' => 4, 'startIns' => new \DateTime('2016-06-01'), 'endIns' => new \DateTime('2016-06-07'), 'startGroup' => new \DateTime('2016-06-09'), 'endGroup' => new \DateTime('2016-06-15'), 'startFinal' => new \DateTime('2016-06-16'), 'endFinal' => new \DateTime('2016-06-20'))
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
			$entity->setStatus($tournamentStatus);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:Tournament');
		$tournament1 = $repository->findOneByName('Torneo Zoombie');
		$tournament2 = $repository->findOneByName('Torneo UA');

		$Categories = array(
			array('name' => 'Category Femenina', 'gender' => 'Female', 'tournament' => $tournament1, 'bgColor' => '#1d1363'),
			array('name' => 'Category Masculina', 'gender' => 'Male', 'tournament' => $tournament1, 'bgColor' => '#cccccc'),
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

		$Inscriptions = array(
			array('pair' => $pair1, 'tournament' => $tournament1, 'category' => $category1),
			array('pair' => $pair3, 'tournament' => $tournament1, 'category' => $category1),
			array('pair' => $pair4, 'tournament' => $tournament1, 'category' => $category1),
			array('pair' => $pair1, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair2, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair3, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair4, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair5, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair6, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair7, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair8, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair9, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair10, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair11, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair12, 'tournament' => $tournament1, 'category' => $category2),
			array('pair' => $pair2, 'tournament' => $tournament2, 'category' => $category3),
			array('pair' => $pair6, 'tournament' => $tournament2, 'category' => $category3),
			array('pair' => $pair7, 'tournament' => $tournament2, 'category' => $category3),
			array('pair' => $pair8, 'tournament' => $tournament2, 'category' => $category3),
			array('pair' => $pair9, 'tournament' => $tournament2, 'category' => $category3),
			array('pair' => $pair11, 'tournament' => $tournament2, 'category' => $category3),
		);

		foreach ($Inscriptions as $key) {
			$entity = new Inscription();
			$entity->setPair($key['pair']);
			$entity->setTournament($key['tournament']);
			$entity->setCategory($key['category']);
			$manager->persist($entity);	
		}
		$manager->flush();
	}
}
