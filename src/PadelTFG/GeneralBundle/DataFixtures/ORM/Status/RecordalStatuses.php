<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\RecordalStatus;


class RecordalStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$RecordalStatuses = array(
			array('id' => '1', 'value' => 'created'),
			array('id' => '2', 'value' => 'read'),
			array('id' => '3', 'value' => 'hidden'),
			array('id' => '4', 'value' => 'deleted'),
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
