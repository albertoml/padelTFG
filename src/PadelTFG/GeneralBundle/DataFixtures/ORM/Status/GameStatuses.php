<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\GameStatus;


class GameStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$GameStatuses = array(
			array('id' => '1', 'value' => 'Created'),
			array('id' => '2', 'value' => 'In Process to Change'),
			array('id' => '3', 'value' => 'Pending'),
			array('id' => '4', 'value' => 'Played'),
			array('id' => '5', 'value' => 'Won'),
			array('id' => '6', 'value' => 'Losed'),
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
