<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\GroupTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\GroupCategory;


class GroupTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Users = array(
			array('email' => 'emailCategoryTest'),
			array('email' => 'User1Pair1'),
			array('email' => 'User2Pair1'),
			array('email' => 'User1Pair2'),
			array('email' => 'User2Pair2'),
			array('email' => 'User1Pair3'),
			array('email' => 'User2Pair3'),
			array('email' => 'User1Pair4'),
			array('email' => 'User2Pair4'),
			array('email' => 'User1Pair5'),
			array('email' => 'User2Pair5'),
			array('email' => 'User1Pair6'),
			array('email' => 'User2Pair6'),
			array('email' => 'User1Pair7'),
			array('email' => 'User2Pair7'),
			array('email' => 'User1Pair8'),
			array('email' => 'User2Pair8'),
			array('email' => 'User1Pair9'),
			array('email' => 'User2Pair9'),
			array('email' => 'User1Pair10'),
			array('email' => 'User2Pair10')
		);

		$repository = $manager->getRepository('GeneralBundle:GroupCategory');
		$groupA = $repository->findOneByName('Group A Test');
		if(!$groupA instanceof GroupCategory ){
			$groupA = $repository->findOneByName('Group A TestP');
		}
		$groupB = $repository->findOneByName('Group B Test');
		if(!$groupB instanceof GroupCategory ){
			$groupB = $repository->findOneByName('Group B TestP');
		}
		$groupC = $repository->findOneByName('Group C Test');
		if(!$groupC instanceof GroupCategory ){
			$groupC = $repository->findOneByName('Group C TestP');
		}

		$groupsNoName = $repository->findByName('No name');
		foreach ($groupsNoName as $group) {
			if ($group instanceof GroupCategory) {

				$manager->remove($group);
			}
		}

		$Groups = array('gA' => $groupA, 'gB' => $groupB, 'gC' => $groupC);

		$repository = $manager->getRepository('GeneralBundle:Inscription');
        foreach ($Groups as $group) {
	        
	        if ($group instanceof GroupCategory) {

		        $inscriptions = $repository->findByGroup($group);
		        foreach ($inscriptions as $inscription) {
		        	if ($inscription instanceof Inscription) {

						$manager->remove($inscription);
					}
		        }
		    }
	    }	

		$manager->flush();

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
