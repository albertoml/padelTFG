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
				'startFinalDate' => new \DateTime(), 'endFinalDate' => new \DateTime(), 'registeredLimit' => 10, 'image' => '')
		);

		$entity = new Tournament();

		$entity->setAdmin($admin);
		$entity->setName('Torneo TFG');
		$entity->setStartInscriptionDate(new \DateTime());
		$entity->setEndInscriptionDate(new \DateTime());
		$entity->setStartGroupDate(new \DateTime());
		$entity->setEndGroupDate(new \DateTime());
		$entity->setStartFinalDate(new \DateTime());
		$entity->setEndFinalDate(new \DateTime());
		$entity->setRegisteredLimit(10);
		$entity->setImage('');

		$manager->persist($entity);	

		$Categories = array(
			array('name' => 'Category Tournament', 'tournament' => $entity),
			array('name' => 'Category Tournament1', 'tournament' => $entity)
			);

		foreach ($Categories as $key) {
			$entity2 = new Category();
			$entity2->setName($key['name']);
			$entity2->setTournament($key['tournament']);
			$manager->persist($entity2);	
		}

		$manager->flush();
	}
}
