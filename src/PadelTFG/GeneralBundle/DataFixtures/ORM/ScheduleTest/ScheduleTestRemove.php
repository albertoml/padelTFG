<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\ScheduleTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Schedule;


class ScheduleTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$repository = $manager->getRepository('GeneralBundle:Schedule');

		$Schedules = array(
			array('scheduleJson' => 'Schedule TFG'),
			array('scheduleJson' => 'Schedule TFG2'),
			array('scheduleJson' => 'Schedule TFG3'),
			array('scheduleJson' => 'Schedule TFG4'),
			array('scheduleJson' => 'Schedule TFG5'),
			array('scheduleJson' => 'Schedule POST'),
			array('scheduleJson' => 'Schedule TFGModify'),
			array('scheduleJson' => 'Schedule TFGDELETE')
		);

		foreach ($Schedules as $key) {
			
			$entity = $repository->findOneByScheduleJson($key['scheduleJson']);
			if ($entity instanceof Schedule) {

				$manager->remove($entity);
			}	
		}
		$manager->flush();
	}
}
