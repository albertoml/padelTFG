<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\UserRole;


class UserRoles implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$UserRoles = array(
			array('id' => '1', 'value' => 'Player'),
			array('id' => '2', 'value' => 'TournamentAdmin'),
			array('id' => '3', 'value' => 'Admin')
		);

		foreach ($UserRoles as $key) {
			$entity = new UserRole();

			$entity->setId($key['id']);
			$entity->setValue($key['value']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
