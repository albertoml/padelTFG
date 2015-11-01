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

		$admin = new User();

		$admin->setName('UserCategoryTest');
		$admin->setLastName('UserCategoryLastName');
		$admin->setEmail('emailCategoryTest');
		$admin->setPassword('password');

		$manager->persist($admin);	
		$manager->flush();

		$tournament = new Tournament();

		$tournament->setAdmin($admin);
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
