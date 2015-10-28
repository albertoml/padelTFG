<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\UserTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\User;


class UserTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$repository = $manager->getRepository('GeneralBundle:User');

		$Users = array(
			array('email' => 'amlTest58@alu.ua.es'),
			array('email' => 'rcsTestDelete58@alu.ua.es'),
			array('email' => 'rcsTest58@alu.ua.es'),
			array('email' => 'pcs58POSTTest@alu.ua.es'),
			array('email' => 'pcs58POSTTestEmailRegistered@alu.ua.es')
		);

		foreach ($Users as $key) {
			
			$entity = $repository->findOneByEmail($key['email']);
			if ($entity instanceof User) {

				$manager->remove($entity);
			}	
		}
		$manager->flush();
	}
}
