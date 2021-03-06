<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\ObservationTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Observation;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Inscription;


class ObservationTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Users = array(
			array('name' => 'UserObservationTest', 'lastName' => 'UserObservationTest', 'email' => 'emailObservationTest',
				'password' => 'password'),
			array('name' => 'User1Pair1ObservationTest', 'lastName' => 'User1Pair1ObservationTest', 'email' => 'User1Pair1ObservationTest',
				'password' => 'password'),
			array('name' => 'User2Pair1ObservationTest', 'lastName' => 'User2Pair1ObservationTest', 'email' => 'User2Pair1ObservationTest',
				'password' => 'password'),
			array('name' => 'User1Pair2ObservationTest', 'lastName' => 'User1Pair2ObservationTest', 'email' => 'User1Pair2ObservationTest',
				'password' => 'password'),
			array('name' => 'User2Pair2ObservationTest', 'lastName' => 'User2Pair2ObservationTest', 'email' => 'User2Pair2ObservationTest',
				'password' => 'password'),
			array('name' => 'User1Pair3ObservationTest', 'lastName' => 'User1Pair3ObservationTest', 'email' => 'User1Pair3ObservationTest',
				'password' => 'password'),
			array('name' => 'User2Pair3ObservationTest', 'lastName' => 'User2Pair3ObservationTest', 'email' => 'User2Pair3ObservationTest',
				'password' => 'password')
			);

		foreach ($Users as $key) {
			$entity = new User();
			$entity->setName($key['name']);
			$entity->setLastName($key['lastName']);
			$entity->setEmail($key['email']);
			$entity->setPassword($key['password']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$tournament = new Tournament();
		$repository = $manager->getRepository('GeneralBundle:User');
		$userAdmin = $repository->findOneByName('UserObservationTest');
		$tournament->setAdmin($userAdmin);
		$tournament->setName('ObservationTournamentName');
		$manager->persist($tournament);	
		$manager->flush();

		$category = new Category();
		$category->setName('Category Observation');
		$category->setTournament($tournament);
		$manager->persist($category);	
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:User');
		$user1Pair1 = $repository->findOneByName('User1Pair1ObservationTest');
		$user2Pair1 = $repository->findOneByName('User2Pair1ObservationTest');
		$user1Pair2 = $repository->findOneByName('User1Pair2ObservationTest');
		$user2Pair2 = $repository->findOneByName('User2Pair2ObservationTest');
		$user1Pair3 = $repository->findOneByName('User1Pair3ObservationTest');
		$user2Pair3 = $repository->findOneByName('User2Pair3ObservationTest');

		$Pairs = array(
			array('user1' => $user1Pair1, 'user2' => $user2Pair1),
			array('user1' => $user1Pair2, 'user2' => $user2Pair2),
			array('user1' => $user1Pair3, 'user2' => $user2Pair3)
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


		$Inscriptions = array(
			array('pair' => $pair1, 'tournament' => $tournament, 'category' => $category),
			array('pair' => $pair2, 'tournament' => $tournament, 'category' => $category),
			array('pair' => $pair3, 'tournament' => $tournament, 'category' => $category)
			);

		foreach ($Inscriptions as $key) {
			$entity = new Inscription();
			$entity->setPair($key['pair']);
			$entity->setTournament($key['tournament']);
			$entity->setCategory($key['category']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:Inscription');
		$inscription1 = $repository->findOneByPair($pair1);
		$inscription2 = $repository->findOneByPair($pair2);
		$inscription3 = $repository->findOneByPair($pair3);

		$Observations = array(
			array('date' => new \DateTime(), 'fromHour' => 10, 'toHour' => 14, 'inscription' => $inscription1),
			array('date' => new \DateTime(), 'fromHour' => 16, 'toHour' => 18, 'inscription' => $inscription2),
			array('date' => new \DateTime(), 'fromHour' => 16, 'toHour' => 18, 'inscription' => $inscription3)
			);

		foreach ($Observations as $key) {
			$entity = new Observation();
			$entity->setDate($key['date']);
			$entity->setFromHour($key['fromHour']);
			$entity->setToHour($key['toHour']);
			$entity->setInscription($key['inscription']);
			$manager->persist($entity);	
		}
		$manager->flush();
	}
}
