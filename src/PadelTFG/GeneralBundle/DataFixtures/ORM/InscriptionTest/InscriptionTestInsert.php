<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\InscriptionTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\GroupCategory;
use PadelTFG\GeneralBundle\Entity\Inscription;


class InscriptionTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Users = array(
			array('name' => 'UserInscriptionTest', 'lastName' => 'UserInscriptionTest', 'email' => 'emailInscriptionTest',
				'password' => 'password'),
			array('name' => 'UserInscriptionTest1', 'lastName' => 'UserInscriptionTest1', 'email' => 'emailInscriptionTest1',
				'password' => 'password'),
			array('name' => 'User1Pair1PairTest', 'lastName' => 'User1Pair1PairTest', 'email' => 'User1Pair1PairTest',
				'password' => 'password'),
			array('name' => 'User2Pair1PairTest', 'lastName' => 'User2Pair1PairTest', 'email' => 'User2Pair1PairTest',
				'password' => 'password'),
			array('name' => 'User1Pair2PairTest', 'lastName' => 'User1Pair2PairTest', 'email' => 'User1Pair2PairTest',
				'password' => 'password'),
			array('name' => 'User2Pair2PairTest', 'lastName' => 'User2Pair2PairTest', 'email' => 'User2Pair2PairTest',
				'password' => 'password'),
			array('name' => 'User1Pair3PairTest', 'lastName' => 'User1Pair3PairTest', 'email' => 'User1Pair3PairTest',
				'password' => 'password'),
			array('name' => 'User2Pair3PairTest', 'lastName' => 'User2Pair3PairTest', 'email' => 'User2Pair3PairTest',
				'password' => 'password'),
			array('name' => 'User1Pair4PairTest', 'lastName' => 'User1Pair4PairTest', 'email' => 'User1Pair4PairTest',
				'password' => 'password'),
			array('name' => 'User2Pair4PairTest', 'lastName' => 'User2Pair4PairTest', 'email' => 'User2Pair4PairTest',
				'password' => 'password'),
			array('name' => 'User1Pair5PairTest', 'lastName' => 'User1Pair4PairTest', 'email' => 'User1Pair4PairTest',
				'password' => 'password'),
			array('name' => 'User2Pair5PairTest', 'lastName' => 'User2Pair4PairTest', 'email' => 'User2Pair4PairTest',
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
		$tournament2 = new Tournament();
		$tournament3 = new Tournament();
		$repository = $manager->getRepository('GeneralBundle:User');
		$userAdmin = $repository->findOneByName('UserInscriptionTest');
		$tournament->setAdmin($userAdmin);
		$tournament->setName('CategoryTournamentName');
		$userAdmin = $repository->findOneByName('UserInscriptionTest1');
		$tournament2->setAdmin($userAdmin);
		$tournament2->setName('CategoryTournamentName1');
		$tournament2->setRegisteredLimit(3);
		$tournament3->setAdmin($userAdmin);
		$tournament3->setName('TournamentToDeleteInscription');

		$manager->persist($tournament);	
		$manager->persist($tournament2);	
		$manager->persist($tournament3);	
		$manager->flush();

		$Categories = array(
			array('name' => 'Category Tournament', 'registeredLimitMax' => null, 'tournament' => $tournament),
			array('name' => 'Category Tournament1', 'registeredLimitMax' => 3, 'tournament' => $tournament),
			array('name' => 'Category Tournament2', 'registeredLimitMax' => null, 'tournament' => $tournament2),
			array('name' => 'Category Tournament3', 'registeredLimitMax' => null, 'tournament' => $tournament3)
			);

		foreach ($Categories as $key) {
			$entity = new Category();
			$entity->setName($key['name']);
			$entity->setTournament($key['tournament']);
			$entity->setRegisteredLimitMax($key['registeredLimitMax']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:Category');
		$category1 = $repository->findOneByName('Category Tournament');
		$category2 = $repository->findOneByName('Category Tournament1');
		$category3 = $repository->findOneByName('Category Tournament2');
		$category4 = $repository->findOneByName('Category Tournament3');

		$repository = $manager->getRepository('GeneralBundle:User');
		$user1Pair1 = $repository->findOneByName('User1Pair1PairTest');
		$user2Pair1 = $repository->findOneByName('User2Pair1PairTest');
		$user1Pair2 = $repository->findOneByName('User1Pair2PairTest');
		$user2Pair2 = $repository->findOneByName('User2Pair2PairTest');
		$user1Pair3 = $repository->findOneByName('User1Pair3PairTest');
		$user2Pair3 = $repository->findOneByName('User2Pair3PairTest');
		$user1Pair4 = $repository->findOneByName('User1Pair4PairTest');
		$user2Pair4 = $repository->findOneByName('User2Pair4PairTest');
		$user1Pair5 = $repository->findOneByName('User1Pair5PairTest');
		$user2Pair5 = $repository->findOneByName('User2Pair5PairTest');

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

		$Groups = array(
			array('name' => 'Group A', 'category' => $category2, 'tournament' => $tournament),
			array('name' => 'Group B', 'category' => $category2, 'tournament' => $tournament),
			array('name' => 'Group C', 'category' => $category3, 'tournament' => $tournament2),
			array('name' => 'Group D', 'category' => $category4, 'tournament' => $tournament3)
			);

		foreach ($Groups as $key) {
			$entity = new GroupCategory();
			$entity->setName($key['name']);
			$entity->setCategory($key['category']);
			$entity->setTournament($key['tournament']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:Pair');
		$pair1 = $repository->findOneByUser1($user1Pair1);
		$pair2 = $repository->findOneByUser1($user1Pair2);
		$pair3 = $repository->findOneByUser1($user1Pair3);
		$pair4 = $repository->findOneByUser1($user1Pair4);
		$pair5 = $repository->findOneByUser1($user1Pair5);
		$repository = $manager->getRepository('GeneralBundle:Tournament');
		$tournament = $repository->findOneByName('CategoryTournamentName');
		$tournament1 = $repository->findOneByName('CategoryTournamentName1');
		$tournament2 = $repository->findOneByName('TournamentToDeleteInscription');
		$repository = $manager->getRepository('GeneralBundle:GroupCategory');
		$groupA = $repository->findOneByName('Group A');
		$groupB = $repository->findOneByName('Group B');
		$groupC = $repository->findOneByName('Group C');
		$groupD = $repository->findOneByName('Group D');


		$Inscriptions = array(
			array('pair' => $pair1, 'tournament' => $tournament, 'category' => $category2, 'group' => $groupA),
			array('pair' => $pair2, 'tournament' => $tournament, 'category' => $category2, 'group' => $groupB),
			array('pair' => $pair2, 'tournament' => $tournament1, 'category' => $category3, 'group' => $groupC),
			array('pair' => $pair3, 'tournament' => $tournament, 'category' => $category2, 'group' => $groupB),
			array('pair' => $pair4, 'tournament' => $tournament1, 'category' => $category3, 'group' => $groupC),
			array('pair' => $pair5, 'tournament' => $tournament2, 'category' => $category4, 'group' => $groupD)
			);

		foreach ($Inscriptions as $key) {
			$entity = new Inscription();
			$entity->setPair($key['pair']);
			$entity->setTournament($key['tournament']);
			$entity->setCategory($key['category']);
			$entity->setGroup($key['group']);
			$manager->persist($entity);	
		}
		$manager->flush();
	}
}
