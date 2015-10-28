<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\NotificationTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Notification;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;

class NotificationTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$notificationDate = new \DateTime();
        $notificationDate->modify('+1 day');

        $admin = new User();
		$admin->setName('UserNotificationTest');
		$admin->setLastName('UserLastName');
		$admin->setEmail('emailNotificationTest');
		$admin->setPassword('password');
		$manager->persist($admin);	
		$manager->flush();

        $tournament = new Tournament();
		$tournament->setAdmin($admin);
		$tournament->setName('TournamentNotificationTest');
		$tournament->setCreationDate(new \DateTime());
		$manager->persist($tournament);
		$manager->flush();

        
		$Notifications = array(
			array('text' => 'Notification TFG', 'notificationDate' => $notificationDate, 'tournament' => $tournament),
			array('text' => 'Notification TFG2', 'notificationDate' => $notificationDate, 'tournament' => $tournament),
			array('text' => 'Notification TFG3', 'notificationDate' => $notificationDate, 'tournament' => $tournament),
			array('text' => 'Notification TFG4', 'notificationDate' => $notificationDate, 'tournament' => $tournament),
			array('text' => 'Notification TFG5', 'notificationDate' => $notificationDate, 'tournament' => $tournament),
			array('text' => 'Notification TFGDELETE', 'notificationDate' => $notificationDate, 'tournament' => $tournament)
			);

		foreach ($Notifications as $key) {
			$entity = new Notification();

			$entity->setText($key['text']);
			$entity->setNotificationDate($key['notificationDate']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
