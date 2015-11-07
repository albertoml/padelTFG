<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\NotificationTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Notification;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;


class NotificationTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$repository = $manager->getRepository('GeneralBundle:Notification');

		$Notifications = array(
			array('text' => 'Notification TFG'),
			array('text' => 'Notification TFG2'),
			array('text' => 'Notification TFG3'),
			array('text' => 'Notification TFG4'),
			array('text' => 'Notification TFG5'),
			array('text' => 'Notification POST'),
			array('text' => 'Modify Notification'),
			array('text' => 'Notification TFGDELETE')
		);

		foreach ($Notifications as $key) {
			
			$entity = $repository->findOneByText($key['text']);
			if ($entity instanceof Notification) {

				$manager->remove($entity);
			}
		}
		
		$repository = $manager->getRepository('GeneralBundle:Tournament');
		$entity = $repository->findOneByName('TournamentNotificationTest');
		if ($entity instanceof Tournament) {

			$manager->remove($entity);
		}

		$repository = $manager->getRepository('GeneralBundle:User');
		$entity = $repository->findOneByEmail('emailNotificationTest');
		if ($entity instanceof User) {

			$manager->remove($entity);
		}

		$manager->flush();
	}
}
