<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\RecordalTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Recordal;


class RecordalTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$repository = $manager->getRepository('GeneralBundle:Recordal');

		$Recordals = array(
			array('text' => 'Recordal TFG'),
			array('text' => 'Recordal TFG2'),
			array('text' => 'Recordal TFG3'),
			array('text' => 'Recordal TFG4'),
			array('text' => 'Recordal TFG5'),
			array('text' => 'Recordal POST'),
			array('text' => 'Modify Recordal'),
			array('text' => 'Recordal TFGDELETE')
		);

		foreach ($Recordals as $key) {
			
			$entity = $repository->findOneByText($key['text']);
			if ($entity instanceof Recordal) {

				$manager->remove($entity);
			}	
		}
		$manager->flush();
	}
}
