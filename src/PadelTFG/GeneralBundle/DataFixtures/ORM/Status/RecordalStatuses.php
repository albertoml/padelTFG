<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\RecordalStatus;


class RecordalStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$RecordalStatuses = array(
			array('id' => '1', 'value' => 'Created'),
			array('id' => '2', 'value' => 'Read'),
			array('id' => '3', 'value' => 'Hidden'),
			array('id' => '4', 'value' => 'Deleted'),
		);

		foreach ($RecordalStatuses as $key) {
			$entity = new RecordalStatus();

			$entity->setId($key['id']);
			$entity->setValue($key['value']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
