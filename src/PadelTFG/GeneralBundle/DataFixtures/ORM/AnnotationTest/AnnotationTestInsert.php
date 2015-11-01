<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\AnnotationTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Annotation;


class AnnotationTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){
        
		$Annotations = array(
			array('text' => 'Annotation TFG'),
			array('text' => 'Annotation TFG2'),
			array('text' => 'Annotation TFG3'),
			array('text' => 'Annotation TFG4'),
			array('text' => 'Annotation TFG5'),
			array('text' => 'Annotation TFGDELETE')
			);

		foreach ($Annotations as $key) {
			$entity = new Annotation();

			$entity->setText($key['text']);
			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
