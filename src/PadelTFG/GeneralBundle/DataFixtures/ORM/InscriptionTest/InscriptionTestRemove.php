<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\InscriptionTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Pair;
use PadelTFG\GeneralBundle\Entity\Inscription;


class InscriptionTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Users = array(
			array('email' => 'User1Pair1PairTest'),
			array('email' => 'User2Pair1PairTest'),
			array('email' => 'User1Pair2PairTest'),
			array('email' => 'User2Pair2PairTest'),
			array('email' => 'User1Pair3PairTest'),
			array('email' => 'User2Pair3PairTest'),
			array('email' => 'User1Pair4PairTest'),
			array('email' => 'User2Pair4PairTest'),
			array('email' => 'emailInscriptionTest'),
			array('email' => 'emailInscriptionTest1')
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
