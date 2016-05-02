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

		$repository = $manager->getRepository('GeneralBundle:User');
		$entity = $repository->findOneByEmail('emailTournamentTest');
		if ($entity instanceof User) {

			$manager->remove($entity);
		}	

		$manager->flush();
	}
}
