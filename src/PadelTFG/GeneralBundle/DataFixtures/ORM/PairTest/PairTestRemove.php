<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\PairTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\User;


class PairTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Users = array(
			array('email' => 'emailCategoryTest'),
			array('email' => 'User1Pair1PairTest'),
			array('email' => 'User2Pair1PairTest'),
			array('email' => 'User1Pair2PairTest'),
			array('email' => 'User2Pair2PairTest'),
			array('email' => 'User1Pair3PairTest'),
			array('email' => 'User2Pair3PairTest'),
			array('email' => 'User1Pair4PairTest'),
			array('email' => 'User2Pair4PairTest'),
			array('email' => 'User1Pair5PairTest'),
			array('email' => 'User2Pair5PairTest'),
			array('email' => 'User1Pair6PairTest'),
			array('email' => 'User2Pair6PairTest'),
			array('email' => 'User1Pair7PairTest'),
			array('email' => 'User2Pair7PairTest')
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
