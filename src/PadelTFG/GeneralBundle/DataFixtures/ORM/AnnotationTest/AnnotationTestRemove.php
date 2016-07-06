<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\AnnotationTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Annotation;
use PadelTFG\GeneralBundle\Entity\User;


class AnnotationTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$repository = $manager->getRepository('GeneralBundle:User');

		$userEntity = $repository->findOneByEmail('AnnotationEmail');
		if ($userEntity instanceof User) {

			$manager->remove($userEntity);
		}	
		
		$repository = $manager->getRepository('GeneralBundle:Annotation');

		$Annotations = array(
			array('text' => 'Annotation TFG'),
			array('text' => 'Annotation TFG2'),
			array('text' => 'Annotation TFG3'),
			array('text' => 'Annotation TFG4'),
			array('text' => 'Annotation TFG5'),
			array('text' => 'Annotation POST'),
			array('text' => 'Modify Annotation'),
			array('text' => 'Annotation TFGDELETE')
		);

		foreach ($Annotations as $key) {
			
			$entity = $repository->findOneByText($key['text']);
			if ($entity instanceof Annotation) {

				$manager->remove($entity);
			}	
		}
		$manager->flush();
	}
}
