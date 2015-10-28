<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Users;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\User;


class Users implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$Users = array(
			array('name' => 'alberto', 'lastName' => 'martinez lopez', 'firstPhone' => '626289021', 'email' => 'aml58@alu.ua.es', 
				'address' => 'C/ falsa 123', 'city' => 'Villena', 'cp' => '03400', 'user' => 'amartlop', 'password' => 'hola', 'salt' => '123',
				 'status' => '1', 'registrationDate' => '14/10/2015', 'birthDate' => '21/08/1991', 'gameLevel' => '10', 'alias' => 'champion'),
			array('name' => 'rocio', 'lastName' => 'carrasco soriano', 'firstPhone' => '626289021', 'email' => 'rcs19@alu.ua.es', 
				'address' => 'C/ falsa 123', 'city' => 'Villena', 'cp' => '03400', 'user' => 'roccarsor', 'password' => 'hola', 'salt' => '123',
				 'status' => '1', 'registrationDate' => '14/10/2015', 'birthDate' => '24/02/1991', 'gameLevel' => '10', 'alias' => 'champion'),
			array('name' => 'ana', 'lastName' => 'lopez grau', 'firstPhone' => '626289021', 'email' => 'alg8@alu.ua.es', 
				'address' => 'C/ falsa 123', 'city' => 'Villena', 'cp' => '03400', 'user' => 'analopgra', 'password' => 'hola', 'salt' => '123',
				 'status' => '1', 'registrationDate' => '14/10/2015', 'birthDate' => '15/01/1991', 'gameLevel' => '10', 'alias' => 'champion'),
			array('name' => 'joaquin', 'lastName' => 'sanchez grau', 'firstPhone' => '626289021', 'email' => 'jsg81@alu.ua.es', 
				'address' => 'C/ falsa 123', 'city' => 'Villena', 'cp' => '03400', 'user' => 'joasangra', 'password' => 'hola', 'salt' => '123',
				 'status' => '1', 'registrationDate' => '14/10/2015', 'birthDate' => '25/11/1985', 'gameLevel' => '10', 'alias' => 'champion'),
			array('name' => 'david', 'lastName' => 'sanchez lopez', 'firstPhone' => '626289021', 'email' => 'dsl90@alu.ua.es', 
				'address' => 'C/ falsa 123', 'city' => 'Villena', 'cp' => '03400', 'user' => 'davsanlop', 'password' => 'hola', 'salt' => '123',
				 'status' => '1', 'registrationDate' => '14/10/2015', 'birthDate' => '11/04/1998', 'gameLevel' => '10', 'alias' => 'champion'),
		);

		foreach ($Users as $key) {
			$entity = new User();

			$entity->setValue($key['name']);
			$entity->setValue($key['lastName']);
			$entity->setValue($key['firstPhone']);
			$entity->setValue($key['email']);
			$entity->setValue($key['address']);
			$entity->setValue($key['city']);
			$entity->setValue($key['cp']);
			$entity->setValue($key['user']);
			$entity->setValue($key['password']);
			$entity->setValue($key['salt']);
			$entity->setValue($key['status']);
			$entity->setValue($key['registrationDate']);
			$entity->setValue($key['birthDate']);
			$entity->setValue($key['gameLevel']);
			$entity->setValue($key['alias']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
