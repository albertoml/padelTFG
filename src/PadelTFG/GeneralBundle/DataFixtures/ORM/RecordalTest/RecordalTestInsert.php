<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\RecordalTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Recordal;


class RecordalTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$recordalDate = new \DateTime();
        $recordalDate->modify('+1 day');
        
		$Recordals = array(
			array('text' => 'Recordal TFG', 'recordalDate' => $recordalDate),
			array('text' => 'Recordal TFG2', 'recordalDate' => $recordalDate),
			array('text' => 'Recordal TFG3', 'recordalDate' => $recordalDate),
			array('text' => 'Recordal TFG4', 'recordalDate' => $recordalDate),
			array('text' => 'Recordal TFG5', 'recordalDate' => $recordalDate),
			array('text' => 'Recordal TFGDELETE', 'recordalDate' => $recordalDate)
			);

		foreach ($Recordals as $key) {
			$entity = new Recordal();

			$entity->setText($key['text']);
			$entity->setRecordalDate($key['recordalDate']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
