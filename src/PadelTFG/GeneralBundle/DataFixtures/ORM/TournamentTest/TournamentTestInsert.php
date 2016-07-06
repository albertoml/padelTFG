<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\TournamentTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Category;


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
			array('admin' => $admin, 'name' => 'Torneo TFG', 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => ''),
			array('admin' => $admin, 'name' => 'Torneo TFG2', 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => ''),
			array('admin' => $admin, 'name' => 'Torneo TFG3', 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => ''),
			array('admin' => $admin, 'name' => 'Torneo TFG4', 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => ''),
			array('admin' => $admin, 'name' => 'Torneo TFG5', 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => ''),
			array('admin' => $admin, 'name' => 'Torneo TFGDELETE', 'startInscriptionDate' => new \DateTime(),
				'endInscriptionDate' => new \DateTime(), 'startGroupDate' => new \DateTime(), 'endGroupDate' => new \DateTime(),
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => '')
		);

		foreach ($Tournaments as $key) {
			$entity = new Tournament();

			$entity->setAdmin($key['admin']);
			$entity->setName($key['name']);
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

		$repository = $manager->getRepository('GeneralBundle:Tournament');
		$tournament = $repository->findOneByName('Torneo TFG');

		$Categories = array(
			array('name' => 'Category Tournament', 'tournament' => $tournament),
			array('name' => 'Category Tournament1', 'tournament' => $tournament)
			);

		foreach ($Categories as $key) {
			$entity = new Category();
			$entity->setName($key['name']);
			$entity->setTournament($key['tournament']);
			$manager->persist($entity);	
		}
		$manager->flush();
	}
}
