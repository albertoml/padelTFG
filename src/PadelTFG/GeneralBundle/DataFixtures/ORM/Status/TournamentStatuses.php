<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\TournamentStatus;


class TournamentStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$TournamentStatuses = array(
			array('id' => '1', 'value' => 'Created'),
			array('id' => '2', 'value' => 'In Inscription Date'),
			array('id' => '3', 'value' => 'In Group Date'),
			array('id' => '4', 'value' => 'Group phase (Matchs done)'),
			array('id' => '5', 'value' => 'In Finals Date'),
			array('id' => '6', 'value' => 'Finished'),
			array('id' => '7', 'value' => 'Deleted'),
		);

		foreach ($TournamentStatuses as $key) {
			$entity = new TournamentStatus();

			$entity->setId($key['id']);
			$entity->setValue($key['value']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
