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
	        $entityPreference->setGender(false);
	        $manager->persist($entityPreference);
		}
		$manager->flush();


		$Categories = array(
			array('name' => 'Category Femenina', 'gender' => 'Female'),
			array('name' => 'Category Masculina', 'gender' => 'Male'),
			array('name' => 'Category Mixta', 'gender' => 'Mixed')
		);

		foreach ($Categories as $key) {
			$entity = new Category();
			$entity->setName($key['name']);
			$entity->setGender($key['gender']);
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
			array('user1' => $user1Pair1, 'user2' => $user2Pair1, 'gender' => 'Female'),
			array('user1' => $user1Pair2, 'user2' => $user2Pair2, 'gender' => 'Mixed'),
			array('user1' => $user1Pair3, 'user2' => $user2Pair3, 'gender' => 'Female'),
			array('user1' => $user1Pair4, 'user2' => $user2Pair4, 'gender' => 'Female'),
			array('user1' => $user1Pair5, 'user2' => $user2Pair5, 'gender' => 'Male')

		);

		foreach ($Pairs as $key) {
			$entity = new Pair();
			$entity->setUser1($key['user1']);
			$entity->setUser2($key['user2']);
			$entity->setGender($key['gender']);
			$manager->persist($entity);	
		}
		$manager->flush();
	}
}
