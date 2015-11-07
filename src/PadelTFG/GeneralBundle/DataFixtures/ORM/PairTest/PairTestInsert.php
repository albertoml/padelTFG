<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\PairTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;


class PairTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Users = array(
			array('name' => 'UserPairTest', 'lastName' => 'UserCategoryLastName', 'email' => 'emailCategoryTest',
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

		$repository = $manager->getRepository('GeneralBundle:User');

		$user1Pair1 = $repository->findOneByName('User1Pair1PairTest');
		$user2Pair1 = $repository->findOneByName('User2Pair1PairTest');
		$user1Pair2 = $repository->findOneByName('User1Pair2PairTest');
		$user2Pair2 = $repository->findOneByName('User2Pair2PairTest');
		$user1Pair3 = $repository->findOneByName('User1Pair3PairTest');
		$user2Pair3 = $repository->findOneByName('User2Pair3PairTest');

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
	}
}
