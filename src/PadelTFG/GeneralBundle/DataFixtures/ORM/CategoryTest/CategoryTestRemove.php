<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\CategoryTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Category;
use PadelTFG\GeneralBundle\Entity\Tournament;
use PadelTFG\GeneralBundle\Entity\User;


class CategoryTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Categories = array(
			array('name' => 'Category TFG'),
			array('name' => 'Category TFG2'),
			array('name' => 'Category TFG3'),
			array('name' => 'Category TFG4'),
			array('name' => 'Category TFG5'),
			array('name' => 'Category TFGDELETE')
			);
		$repository = $manager->getRepository('GeneralBundle:Category');

		foreach ($Categories as $key) {
			$entity = $repository->findOneByName($key['name']);
			if ($entity instanceof Category) {

				$manager->remove($entity);
			}
		}

		$Users = array(
			array('email' => 'emailCategoryTest'),
			array('email' => 'User1Pair1CategoryTest'),
			array('email' => 'User2Pair1CategoryTest'),
			array('email' => 'User1Pair2CategoryTest'),
			array('email' => 'User2Pair2CategoryTest')
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
