<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\GameTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\Game;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Entity\Inscription;
use PadelTFG\GeneralBundle\Entity\GroupCategory;


class GameTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$query = $manager->createQuery(
            'SELECT i FROM GeneralBundle:Game i WHERE i.category IS NULL AND i.pair1 IS NULL AND i.pair2 IS NULL AND i.group IS NULL AND i.tournament IS NULL'
        );
        $games = $query->getResult();

        foreach ($games as $game) {
	        if ($game instanceof Game) {
				$manager->remove($game);
		    }
	    }

	    $manager->flush();

		$repository = $manager->getRepository('GeneralBundle:Game');
		$games = $repository->findAll('TournamentName1');

	    foreach ($games as $game) {
	        if ($game instanceof Game) {
				$manager->remove($game);
		    }
	    }

	    $manager->flush();

		$repository = $manager->getRepository('GeneralBundle:GroupCategory');
		$groupA = $repository->findOneByName('Group A Test');
		$groupB = $repository->findOneByName('Group B Test');
		$groupC = $repository->findOneByName('Group C Test');

		$Groups = array($groupA, $groupB, $groupC);

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
