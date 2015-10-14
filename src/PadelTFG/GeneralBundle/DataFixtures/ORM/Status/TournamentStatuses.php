<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\TournamentStatus;


class TournamentStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$TournamentStatuses = array(
			array('id' => '1', 'value' => 'created'),
			array('id' => '2', 'value' => 'in inscription date'),
			array('id' => '3', 'value' => 'in group date'),
			array('id' => '4', 'value' => 'in finals date'),
			array('id' => '5', 'value' => 'finished'),
			array('id' => '6', 'value' => 'deleted'),
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
