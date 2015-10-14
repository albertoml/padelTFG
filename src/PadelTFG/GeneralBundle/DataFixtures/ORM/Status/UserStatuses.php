<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\UserStatus;


class UserStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$UserStatuses = array(
			array('id' => '1', 'value' => 'registered'),
			array('id' => '2', 'value' => 'in tournament'),
			array('id' => '3', 'value' => 'tournament admin'),
			array('id' => '4', 'value' => 'deleted'),
		);

		foreach ($UserStatuses as $key) {
			$entity = new UserStatus();

			$entity->setId($key['id']);
			$entity->setValue($key['value']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
