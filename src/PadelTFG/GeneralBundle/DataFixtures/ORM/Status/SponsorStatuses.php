<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\Status;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\SponsorStatus;


class SponsorStatuses implements FixtureInterface
{
	public function load(ObjectManager $manager){
		$SponsorStatuses = array(
			array('id' => '1', 'value' => 'Active'),
			array('id' => '2', 'value' => 'Defaulter'),
			array('id' => '3', 'value' => 'Hidden'),
			array('id' => '4', 'value' => 'Deleted'),
		);

		foreach ($SponsorStatuses as $key) {
			$entity = new SponsorStatus();

			$entity->setId($key['id']);
			$entity->setValue($key['value']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
