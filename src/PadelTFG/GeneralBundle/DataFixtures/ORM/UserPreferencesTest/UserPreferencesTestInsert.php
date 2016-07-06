<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\UserPreferencesTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\UserPreference;


class UserPreferencesTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$Users = array(
			array('name' => 'alberto', 'lastName' => 'martinez lopez', 'firstPhone' => '626289021', 'email' => 'amlTest58@alu.ua.es', 
				'address' => 'C/ falsa 123', 'city' => 'Villena', 'cp' => '03400', 'password' => 'hola',
				 'gameLevel' => '10', 'alias' => 'champion'),
			array('name' => 'rocio', 'lastName' => 'carrasco soriano', 'firstPhone' => '626289021', 'email' => 'rcsTestDelete58@alu.ua.es', 
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

		$repository = $manager->getRepository('GeneralBundle:User');
		$user1 = $repository->findOneByName('alberto');
		$user2 = $repository->findOneByName('rocio');

		$userPreference = new UserPreference();
		$userPreference->setId($user1->getId());
        $userPreference->setName(true);
        $userPreference->setLastName(true);
        $userPreference->setEmail(true);
        $userPreference->setStatus(true);
        $userPreference->setFirstPhone(true);
        $userPreference->setCity(true);
        $userPreference->setRole(false);
        $userPreference->setSecondPhone(false);
        $userPreference->setAddress(false);
        $userPreference->setCountry(false);
        $userPreference->setCP(false);
        $userPreference->setBirthDate(false);
        $userPreference->setProfileImage(false);
        $userPreference->setGameLevel(false);
        $userPreference->setAlias(false);
        $userPreference->setNotification(false);
        $userPreference->setRegistrationDate(false);
        $userPreference->setGender(false);

        $userPreference1 = new UserPreference();
        $userPreference1->setId($user2->getId());
        $userPreference1->setName(true);
        $userPreference1->setLastName(true);
        $userPreference1->setEmail(true);
        $userPreference1->setStatus(true);
        $userPreference1->setFirstPhone(true);
        $userPreference1->setCity(true);
        $userPreference1->setRole(false);
        $userPreference1->setSecondPhone(false);
        $userPreference1->setAddress(false);
        $userPreference1->setCountry(false);
        $userPreference1->setCP(false);
        $userPreference1->setBirthDate(false);
        $userPreference1->setProfileImage(false);
        $userPreference1->setGameLevel(false);
        $userPreference1->setAlias(false);
        $userPreference1->setNotification(false);
        $userPreference1->setRegistrationDate(false);
        $userPreference1->setGender(false);

        $manager->persist($userPreference);	
        $manager->persist($userPreference1);	
		$manager->flush();

	}
}
