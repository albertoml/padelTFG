<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\ObservationTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\User;


class ObservationTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Users = array(
			array('email' => 'User1Pair1ObservationTest'),
			array('email' => 'User2Pair1ObservationTest'),
			array('email' => 'User1Pair2ObservationTest'),
			array('email' => 'User2Pair2ObservationTest'),
			array('email' => 'emailObservationTest')
		);

		$repository = $manager->getRepository('GeneralBundle:User');
		foreach ($Users as $key) {
			
			$entity = $repository->findOneByEmail($key['email']);
			if ($entity instanceof User) {

				$manager->remove($entity);
			}	
		}	

		$manager->flush();
	}
}
