<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\UserTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\User;


class UserTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$Users = array(
			array('name' => 'alberto', 'lastName' => 'martinez lopez', 'firstPhone' => '626289021', 'email' => 'amlTest58@alu.ua.es', 
				'address' => 'C/ falsa 123', 'city' => 'Villena', 'cp' => '03400', 'password' => 'hola',
				 'gameLevel' => '10', 'alias' => 'champion'),
			array('name' => 'rocio', 'lastName' => 'carrasco soriano', 'firstPhone' => '626289021', 'email' => 'rcsTestDelete58@alu.ua.es', 
				'address' => 'C/ falsa 123', 'city' => 'Villena', 'cp' => '03400', 'password' => 'hola',
				 'gameLevel' => '10', 'alias' => 'champion'),
			array('name' => 'rocio', 'lastName' => 'carrasco soriano', 'firstPhone' => '626289021', 'email' => 'rcsTest58@alu.ua.es', 
				'address' => 'C/ falsa 123', 'city' => 'Villena', 'cp' => '03400', 'password' => 'hola',
				 'gameLevel' => '10', 'alias' => 'champion')
		);

		foreach ($Users as $key) {
			$entity = new User();

			$entity->setName($key['name']);
			$entity->setLastName($key['lastName']);
			$entity->setFirstPhone($key['firstPhone']);
			$entity->setEmail($key['email']);
			$entity->setAddress($key['address']);
			$entity->setCity($key['city']);
			$entity->setCP($key['cp']);
			$entity->setPassword($key['password']);
			$entity->setGameLevel($key['gameLevel']);
			$entity->setAlias($key['alias']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
