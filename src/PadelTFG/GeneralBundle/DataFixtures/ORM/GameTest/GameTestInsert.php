<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\GameTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Game;
use PadelTFG\GeneralBundle\Entity\GroupCategory;


class GameTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Users = array(
			array('name' => 'UserCategoryTest', 'lastName' => 'UserCategoryLastName', 'email' => 'emailCategoryTest',
				'password' => 'password'),
			array('name' => 'User1Pair1', 'lastName' => 'User1Pair1', 'email' => 'User1Pair1', 'password' => 'pass'),
			array('name' => 'User2Pair1', 'lastName' => 'User2Pair1', 'email' => 'User2Pair1', 'password' => 'pass'),
			array('name' => 'User1Pair2', 'lastName' => 'User1Pair2', 'email' => 'User1Pair2', 'password' => 'pass'),
			array('name' => 'User2Pair2', 'lastName' => 'User2Pair2', 'email' => 'User2Pair2', 'password' => 'pass'),
			array('name' => 'User1Pair3', 'lastName' => 'User1Pair3', 'email' => 'User1Pair3', 'password' => 'pass'),
			array('name' => 'User2Pair3', 'lastName' => 'User2Pair3', 'email' => 'User2Pair3', 'password' => 'pass'),
			array('name' => 'User1Pair4', 'lastName' => 'User1Pair4', 'email' => 'User1Pair4', 'password' => 'pass'),
			array('name' => 'User2Pair4', 'lastName' => 'User2Pair4', 'email' => 'User2Pair4', 'password' => 'pass'),
			array('name' => 'User1Pair5', 'lastName' => 'User1Pair5', 'email' => 'User1Pair5', 'password' => 'pass'),
			array('name' => 'User2Pair5', 'lastName' => 'User2Pair5', 'email' => 'User2Pair5', 'password' => 'pass'),
			array('name' => 'User1Pair6', 'lastName' => 'User1Pair6', 'email' => 'User1Pair6', 'password' => 'pass'),
			array('name' => 'User2Pair6', 'lastName' => 'User2Pair6', 'email' => 'User2Pair6', 'password' => 'pass'),
			array('name' => 'User1Pair7', 'lastName' => 'User1Pair7', 'email' => 'User1Pair7', 'password' => 'pass'),
			array('name' => 'User2Pair7', 'lastName' => 'User2Pair7', 'email' => 'User2Pair7', 'password' => 'pass'),
			array('name' => 'User1Pair8', 'lastName' => 'User1Pair8', 'email' => 'User1Pair8', 'password' => 'pass'),
			array('name' => 'User2Pair8', 'lastName' => 'User2Pair8', 'email' => 'User2Pair8', 'password' => 'pass'),
			array('name' => 'User1Pair9', 'lastName' => 'User1Pair9', 'email' => 'User1Pair9', 'password' => 'pass'),
			array('name' => 'User2Pair9', 'lastName' => 'User2Pair9', 'email' => 'User2Pair9', 'password' => 'pass'),
			array('name' => 'User1Pair10', 'lastName' => 'User1Pair10', 'email' => 'User1Pair10', 'password' => 'pass'),
			array('name' => 'User2Pair10', 'lastName' => 'User2Pair10', 'email' => 'User2Pair10', 'password' => 'pass')
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

		

		$repository = $manager->getRepository('GeneralBundle:User');
		$userAdmin = $repository->findOneByName('UserCategoryTest');

		$tournament = new Tournament();
		$tournament->setAdmin($userAdmin);
		$tournament->setName('TournamentName');

		$category = new Category();
		$category->setName('Category Test');
		$category->setTournament($tournament);

		$tournament1 = new Tournament();
		$tournament1->setAdmin($userAdmin);
		$tournament1->setName('TournamentName1');

		$category1 = new Category();
		$category1->setName('Category Test1');
		$category1->setTournament($tournament1);

		$manager->persist($category);	
		$manager->persist($tournament);
		$manager->persist($category1);	
		$manager->persist($tournament1);	
		$manager->flush();

		$repository = $manager->getRepository('GeneralBundle:User');
		$user1Pair1 = $repository->findOneByName('User1Pair1');
		$user2Pair1 = $repository->findOneByName('User2Pair1');
		$user1Pair2 = $repository->findOneByName('User1Pair2');
		$user2Pair2 = $repository->findOneByName('User2Pair2');
		$user1Pair3 = $repository->findOneByName('User1Pair3');
		$user2Pair3 = $repository->findOneByName('User2Pair3');
		$user1Pair4 = $repository->findOneByName('User1Pair4');
		$user2Pair4 = $repository->findOneByName('User2Pair4');
		$user1Pair5 = $repository->findOneByName('User1Pair5');
		$user2Pair5 = $repository->findOneByName('User2Pair5');
		$user1Pair6 = $repository->findOneByName('User1Pair6');
		$user2Pair6 = $repository->findOneByName('User2Pair6');
		$user1Pair7 = $repository->findOneByName('User1Pair7');
		$user2Pair7 = $repository->findOneByName('User2Pair7');
		$user1Pair8 = $repository->findOneByName('User1Pair8');
		$user2Pair8 = $repository->findOneByName('User2Pair8');
		$user1Pair9 = $repository->findOneByName('User1Pair9');
		$user2Pair9 = $repository->findOneByName('User2Pair9');
		$user1Pair10 = $repository->findOneByName('User1Pair10');
		$user2Pair10 = $repository->findOneByName('User2Pair10');

		$Pairs = array(
			array('user1' => $user1Pair1, 'user2' => $user2Pair1),
			array('user1' => $user1Pair2, 'user2' => $user2Pair2),
			array('user1' => $user1Pair3, 'user2' => $user2Pair3),
			array('user1' => $user1Pair4, 'user2' => $user2Pair4),
			array('user1' => $user1Pair5, 'user2' => $user2Pair5),
			array('user1' => $user1Pair6, 'user2' => $user2Pair6),
			array('user1' => $user1Pair7, 'user2' => $user2Pair7),
			array('user1' => $user1Pair8, 'user2' => $user2Pair8),
			array('user1' => $user1Pair9, 'user2' => $user2Pair9),
			array('user1' => $user1Pair10, 'user2' => $user2Pair10)
			);

		foreach ($Pairs as $key) {
			$entity = new Pair();
			$entity->setUser1($key['user1']);
			$entity->setUser2($key['user2']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$Groups = array(
			array('name' => 'Group A Test', 'category' => $category, 'tournament' => $tournament),
			array('name' => 'Group B Test', 'category' => $category, 'tournament' => $tournament),
			array('name' => 'Group C Test', 'category' => $category1, 'tournament' => $tournament1)
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
		$pair6 = $repository->findOneByUser1($user1Pair6);
		$pair7 = $repository->findOneByUser1($user1Pair7);
		$pair8 = $repository->findOneByUser1($user1Pair8);
		$pair9 = $repository->findOneByUser1($user1Pair9);
		$pair10 = $repository->findOneByUser1($user1Pair10);
		$repository = $manager->getRepository('GeneralBundle:GroupCategory');
		$groupA = $repository->findOneByName('Group A Test');
		$groupB = $repository->findOneByName('Group B Test');
		$groupC = $repository->findOneByName('Group C Test');


		$Inscriptions = array(
			array('pair' => $pair1, 'tournament' => $tournament, 'category' => $category, 'group' => $groupA),
			array('pair' => $pair2, 'tournament' => $tournament, 'category' => $category, 'group' => $groupA),
			array('pair' => $pair3, 'tournament' => $tournament, 'category' => $category, 'group' => $groupB),
			array('pair' => $pair4, 'tournament' => $tournament, 'category' => $category, 'group' => $groupB),
			array('pair' => $pair5, 'tournament' => $tournament, 'category' => $category, 'group' => $groupB),
			array('pair' => $pair6, 'tournament' => $tournament1, 'category' => $category1, 'group' => $groupC),
			array('pair' => $pair7, 'tournament' => $tournament1, 'category' => $category1, 'group' => $groupC),
			array('pair' => $pair8, 'tournament' => $tournament1, 'category' => $category1, 'group' => $groupC),
			array('pair' => $pair9, 'tournament' => $tournament1, 'category' => $category1, 'group' => $groupC),
			array('pair' => $pair10, 'tournament' => $tournament1, 'category' => $category1, 'group' => $groupC)
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

		$Games = array(
			array('pair1' => $pair1, 'pair2' => $pair2, 'tournament' => $tournament, 'category' => $category, 'group' => $groupA, 'description' => 'Game Test 1', 'score' => '6/0 - 6/0'),
			array('pair1' => $pair3, 'pair2' => $pair4, 'tournament' => $tournament, 'category' => $category, 'group' => $groupB, 'description' => 'Game Test 2', 'score' => '6/3 - 6/3'),
			array('pair1' => $pair4, 'pair2' => $pair5, 'tournament' => $tournament, 'category' => $category, 'group' => $groupB, 'description' => 'Game Test 3', 'score' => '7/5 - 6/2'),
			array('pair1' => $pair5, 'pair2' => $pair3, 'tournament' => $tournament, 'category' => $category, 'group' => $groupB, 'description' => 'Game Test 4', 'score' => '6/3 - 3/6 - 7/5'),
			array('pair1' => null, 'pair2' => null, 'tournament' => $tournament, 'category' => $category, 'group' => $groupB, 'description' => 'Game DELETED', 'score' => '6/3 - 3/6 - 7/5')
			);

		foreach ($Games as $key) {
			$entity = new Game();
			$entity->setPair1($key['pair1']);
			$entity->setPair2($key['pair2']);
			$entity->setDescription($key['description']);
			$entity->setScore($key['score']);
			$entity->setTournament($key['tournament']);
			$entity->setCategory($key['category']);
			$entity->setGroup($key['group']);
			$manager->persist($entity);	
		}
		$manager->flush();
	}
}
