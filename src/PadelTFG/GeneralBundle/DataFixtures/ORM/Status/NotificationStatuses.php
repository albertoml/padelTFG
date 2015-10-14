<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\NotificationStatus;


class NotificationStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$NotificationStatuses = array(
			array('id' => '1', 'value' => 'created'),
			array('id' => '2', 'value' => 'sent'),
			array('id' => '3', 'value' => 'read'),
			array('id' => '4', 'value' => 'hidden'),
			array('id' => '5', 'value' => 'deleted'),
		);

		foreach ($NotificationStatuses as $key) {
			$entity = new NotificationStatus();

			$entity->setId($key['id']);
			$entity->setValue($key['value']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
