<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\TournamentTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Category;


class TournamentTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Tournaments = array(
			array('name' => 'Torneo TFG'),
			array('name' => 'Torneo TFG2'),
			array('name' => 'Torneo TFG3'),
			array('name' => 'Torneo TFG4'),
			array('name' => 'Torneo TFG5'),
			array('name' => 'Torneo POST'),
			array('name' => 'Torneo TFGDELETE')
		);
		$Categories = array(
			array('name' => 'CategoryTournamentTest1'),
			array('name' => 'CategoryTournamentTest2'),
			array('name' => 'CategoryTournamentTest3')
		);

		$repository = $manager->getRepository('GeneralBundle:Category');
		foreach ($Categories as $key) {
			
			$entity = $repository->findOneByName($key['name']);
			if ($entity instanceof Category) {

				$manager->remove($entity);
			}	
		}

		$repository = $manager->getRepository('GeneralBundle:Tournament');
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
