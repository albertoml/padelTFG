<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\CategoryTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;


class CategoryTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Users = array(
			array('name' => 'UserCategoryTest', 'lastName' => 'UserCategoryLastName', 'email' => 'emailCategoryTest',
				'password' => 'password'),
			array('name' => 'User1Pair1CategoryTest', 'lastName' => 'User1Pair1CategoryTest', 'email' => 'User1Pair1CategoryTest',
				'password' => 'password'),
			array('name' => 'User2Pair1CategoryTest', 'lastName' => 'User2Pair1CategoryTest', 'email' => 'User2Pair1CategoryTest',
				'password' => 'password'),
			array('name' => 'User1Pair2CategoryTest', 'lastName' => 'User1Pair2CategoryTest', 'email' => 'User1Pair2CategoryTest',
				'password' => 'password'),
			array('name' => 'User2Pair2CategoryTest', 'lastName' => 'User2Pair2CategoryTest', 'email' => 'User2Pair2CategoryTest',
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
		$userAdmin = $repository->findOneByName('UserCategoryTest');

		$tournament->setAdmin($userAdmin);
		$tournament->setName('CategoryTournamentName');
		$tournament->setCreationDate(new \DateTime());

		$manager->persist($tournament);	
		$manager->flush();

		$Categories = array(
			array('name' => 'Category TFG'),
			array('name' => 'Category TFG2'),
			array('name' => 'Category TFG3'),
			array('name' => 'Category TFG4'),
			array('name' => 'Category TFG5'),
			array('name' => 'Category TFGDELETE')
			);

		foreach ($Categories as $key) {
			$entity = new Category();
			$entity->setName($key['name']);
			$manager->persist($entity);	
		}
		$manager->flush();

		$Categories = array(
			array('name' => 'Category Tournament', 'tournament' => $tournament),
			array('name' => 'Category Tournament1', 'tournament' => $tournament)
			);

		foreach ($Categories as $key) {
			$entity = new Category();
			$entity->setName($key['name']);
			$entity->setTournament($key['tournament']);
			$manager->persist($entity);	
		}
		$manager->flush();
	}
}
