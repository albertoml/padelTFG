<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\TournamentTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;


class TournamentTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$repository = $manager->getRepository('GeneralBundle:Tournament');

		$Tournaments = array(
			array('name' => 'Torneo TFG'),
			array('name' => 'Torneo TFG2'),
			array('name' => 'Torneo TFG3'),
			array('name' => 'Torneo TFG4'),
			array('name' => 'Torneo TFG5'),
			array('name' => 'Torneo POST'),
			array('name' => 'Torneo TFGDELETE')
		);

		foreach ($Tournaments as $key) {
			
			$entity = $repository->findOneByName($key['name']);
			if ($entity instanceof Tournament) {

				$manager->remove($entity);
			}	
		}

		$repository = $manager->getRepository('GeneralBundle:User');
		$entity = $repository->findOneByEmail('emailTournamentTest');
		if ($entity instanceof User) {

			$manager->remove($entity);
		}	

		$manager->flush();
	}
}
