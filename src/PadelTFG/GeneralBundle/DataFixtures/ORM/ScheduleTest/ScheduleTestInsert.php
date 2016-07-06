<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\ScheduleTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Schedule;


class ScheduleTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Schedules = array(
			array('scheduleJson' => 'Schedule TFG', 'scheduleResourcesJson' => '1'),
			array('scheduleJson' => 'Schedule TFG2', 'scheduleResourcesJson' => '2'),
			array('scheduleJson' => 'Schedule TFG3', 'scheduleResourcesJson' => '3'),
			array('scheduleJson' => 'Schedule TFG4', 'scheduleResourcesJson' => '4'),
			array('scheduleJson' => 'Schedule TFG5', 'scheduleResourcesJson' => '5'),
			array('scheduleJson' => 'Schedule TFGDELETE', 'scheduleResourcesJson' => '6')
			);

		foreach ($Schedules as $key) {
			$entity = new Schedule();

			$entity->setScheduleJson($key['scheduleJson']);
			$entity->setScheduleResourcesJson($key['scheduleResourcesJson']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
