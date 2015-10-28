<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\TournamentTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;


class TournamentTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$admin = new User();

		$admin->setName('UserTournamentTest');
		$admin->setLastName('UserLastName');
		$admin->setEmail('emailTournamentTest');
		$admin->setPassword('password');

		$manager->persist($admin);	
		$manager->flush();

		$Tournaments = array(
			array('admin' => $admin, 'name' => 'Torneo TFG', 'creationDate' => new \DateTime(), 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => ''),
			array('admin' => $admin, 'name' => 'Torneo TFG2', 'creationDate' => new \DateTime(), 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => ''),
			array('admin' => $admin, 'name' => 'Torneo TFG3', 'creationDate' => new \DateTime(), 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => ''),
			array('admin' => $admin, 'name' => 'Torneo TFG4', 'creationDate' => new \DateTime(), 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => ''),
			array('admin' => $admin, 'name' => 'Torneo TFG5', 'creationDate' => new \DateTime(), 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => ''),
			array('admin' => $admin, 'name' => 'Torneo TFGDELETE', 'creationDate' => new \DateTime(), 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => '')
		);

		foreach ($Tournaments as $key) {
			$entity = new Tournament();

			$entity->setAdmin($key['admin']);
			$entity->setName($key['name']);
			$entity->setCreationDate($key['creationDate']);
			$entity->setStartInscriptionDate($key['startInscriptionDate']);
			$entity->setEndInscriptionDate($key['endInscriptionDate']);
			$entity->setStartGroupDate($key['startGroupDate']);
			$entity->setEndGroupDate($key['endGroupDate']);
			$entity->setStartFinalDate($key['startFinalDate']);
			$entity->setEndFinalDate($key['endFinalDate']);
			$entity->setRegisteredLimit($key['registeredLimit']);
			$entity->setImage($key['image']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
