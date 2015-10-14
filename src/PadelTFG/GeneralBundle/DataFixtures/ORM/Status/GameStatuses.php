<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\GameStatus;


class GameStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$GameStatuses = array(
			array('id' => '1', 'value' => 'created'),
			array('id' => '2', 'value' => 'in process to change'),
			array('id' => '3', 'value' => 'pending'),
			array('id' => '4', 'value' => 'played'),
			array('id' => '5', 'value' => 'won'),
			array('id' => '6', 'value' => 'losed'),
		);

		foreach ($GameStatuses as $key) {
			$entity = new GameStatus();

			$entity->setId($key['id']);
			$entity->setValue($key['value']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
