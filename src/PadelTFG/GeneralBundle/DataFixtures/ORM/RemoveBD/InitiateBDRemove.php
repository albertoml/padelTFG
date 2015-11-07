<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\RemoveBD;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Inscription;


class RemoveBDRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Users = array(
			array('name' => 'Natalia'),
			array('name' => 'Servando'),
			array('name' => 'Victoria'),
			array('name' => 'Alberto'),
			array('name' => 'Cecilia'),
			array('name' => 'Saray'),
			array('name' => 'Ana'),
			array('name' => 'Andrea'),
			array('name' => 'Rocio')
		);

		$repository = $manager->getRepository('GeneralBundle:User');
		foreach ($Users as $key) {
			
			$entity = $repository->findOneByName($key['name']);
			if ($entity instanceof User) {

				$manager->remove($entity);
			}	
		}	

		$manager->flush();
	}
}
