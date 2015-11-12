<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\InscriptionStatus;


class InscriptionStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$InscriptionStatuses = array(
			array('id' => '1', 'value' => 'tournament not started'),
			array('id' => '2', 'value' => 'not classified'),
			array('id' => '3', 'value' => 'classified'),
			array('id' => '4', 'value' => 'finished')
		);

		foreach ($InscriptionStatuses as $key) {
			$entity = new InscriptionStatus();

			$entity->setId($key['id']);
			$entity->setValue($key['value']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
