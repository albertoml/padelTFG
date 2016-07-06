<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\AnnotationTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Annotation;
use PadelTFG\GeneralBundle\Entity\User;


class AnnotationTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$userEntity = new User();
		$userEntity->setName('AnnotationManager');
		$userEntity->setLastName('AnnotationLastName');
		$userEntity->setEmail('AnnotationEmail');
		$userEntity->setPassword('AnnotationPassword');
		$manager->persist($userEntity);	
		$manager->flush();
        
		$Annotations = array(
			array('text' => 'Annotation TFG', 'user' => $userEntity),
			array('text' => 'Annotation TFG2', 'user' => $userEntity),
			array('text' => 'Annotation TFG3'),
			array('text' => 'Annotation TFG4'),
			array('text' => 'Annotation TFG5'),
			array('text' => 'Annotation TFGDELETE')
		);

		foreach ($Annotations as $key) {
			$entity = new Annotation();

			$entity->setText($key['text']);
			if(!empty($key['user'])){
				$entity->setUser($key['user']);
			}
			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
