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
		$repository = $manager->getRepository('GeneralBundle:UserStatus');
		$userStatus = $repository->findOneByValue('Registered');

		$Users = array(
			array('name' => 'Natalia', 'lastName' => 'Perez', 'email' => 'nape@PadelTFG.com',
				'password' => 'password'),
			array('name' => 'Servando', 'lastName' => 'Escobar', 'email' => 'sees@PadelTFG.com',
				'password' => 'password'),
			array('name' => 'Victoria', 'lastName' => 'Abril', 'email' => 'viab@PadelTFG.com',
				'password' => 'password'),
			array('name' => 'Alberto', 'lastName' => 'Martinez', 'email' => 'alma@PadelTFG.com',
				'password' => 'password'),
			array('name' => 'Cecilia', 'lastName' => 'Soriano', 'email' => 'ceso@PadelTFG.com',
				'password' => 'password'),
			array('name' => 'Saray', 'lastName' => 'Lozano', 'email' => 'salo@PadelTFG.com',
				'password' => 'password'),
			array('name' => 'Ana', 'lastName' => 'Guardiola', 'email' => 'angu@PadelTFG.com',
				'password' => 'password'),
			array('name' => 'Andrea', 'lastName' => 'Garcia', 'email' => 'anga@PadelTFG.com',
				'password' => 'password'),
			array('name' => 'Rocio', 'lastName' => 'Lopez', 'email' => 'rolo@PadelTFG.com',
				'password' => 'password'),
			array('name' => 'Francisco', 'lastName' => 'Garcia', 'email' => 'frga@PadelTFG.com',
				'password' => 'password'),
			array('name' => 'Juan', 'lastName' => 'Martinez', 'email' => 'juma@PadelTFG.com',
				'password' => 'password')
			);

		foreach ($Users as $key) {
			$entity = new User();
			$entity->setName($key['name']);
			$entity->setLastName($key['lastName']);
			$entity->setEmail($key['email']);
			$entity->setPassword($key['password']);
			$entity->setStatus($userStatus);
			$entityRole = new UserUserRole();
			$entityRole->setUser($entity);
			$entityRole->setRole($playerRole);
			$manager->persist($entityRole);
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
	        $manager->persist($entityPreference);
		}
		$manager->flush();


		$Categories = array(
			array('name' => 'Category Femenina'),
			array('name' => 'Category Masculina'),
			array('name' => 'Category Mixta')
		);

		foreach ($Categories as $key) {
			$entity = new Category();
			$entity->setName($key['name']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:Category');
		$category1 = $repository->findOneByName('Category Femenina');
		$category2 = $repository->findOneByName('Category Masculina');
		$category3 = $repository->findOneByName('Category Mixta');

		$tournament = new Tournament();
		$tournament2 = new Tournament();
		$repository = $manager->getRepository('GeneralBundle:User');
		$userAdmin = $repository->findOneByName('Servando');
		$tournament->setAdmin($userAdmin);
		$tournament->setName('Los Rebeldes');
		$tournament->addCategory($category1);
		$tournament->addCategory($category2);
		$tournament2->setAdmin($userAdmin);
		$tournament2->setName('Los Vampiros');
		$tournament2->addCategory($category3);
		$tournament2->setRegisteredLimit(4);

		$manager->persist($tournament);	
		$manager->persist($tournament2);	
		$manager->flush();

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

		$Pairs = array(
			array('user1' => $user1Pair1, 'user2' => $user2Pair1),
			array('user1' => $user1Pair2, 'user2' => $user2Pair2),
			array('user1' => $user1Pair3, 'user2' => $user2Pair3),
			array('user1' => $user1Pair4, 'user2' => $user2Pair4),
			array('user1' => $user1Pair5, 'user2' => $user2Pair5)

		);

		foreach ($Pairs as $key) {
			$entity = new Pair();
			$entity->setUser1($key['user1']);
			$entity->setUser2($key['user2']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:Pair');
		$pair1 = $repository->findOneByUser1($user1Pair1);
		$pair2 = $repository->findOneByUser1($user1Pair2);
		$pair3 = $repository->findOneByUser1($user1Pair3);
		$pair4 = $repository->findOneByUser1($user1Pair4);
		$repository = $manager->getRepository('GeneralBundle:Tournament');
		$tournament = $repository->findOneByName('Los Rebeldes');
		$tournament1 = $repository->findOneByName('Los Vampiros');


		$Inscriptions = array(
			array('pair' => $pair1, 'tournament' => $tournament, 'category' => $category1),
			array('pair' => $pair2, 'tournament' => $tournament, 'category' => $category2),
			array('pair' => $pair3, 'tournament' => $tournament, 'category' => $category2),
			array('pair' => $pair4, 'tournament' => $tournament, 'category' => $category1),
			array('pair' => $pair1, 'tournament' => $tournament1, 'category' => $category3),
			array('pair' => $pair2, 'tournament' => $tournament1, 'category' => $category3),
			array('pair' => $pair3, 'tournament' => $tournament1, 'category' => $category3),
			array('pair' => $pair4, 'tournament' => $tournament1, 'category' => $category3)
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
