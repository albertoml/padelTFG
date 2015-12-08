<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\InscriptionStatus;


class InscriptionStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$InscriptionStatuses = array(
			array('id' => '1', 'value' => 'Tournament Not Started'),
			array('id' => '2', 'value' => 'Not Classified'),
			array('id' => '3', 'value' => 'Classified'),
			array('id' => '4', 'value' => 'Finished')
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
