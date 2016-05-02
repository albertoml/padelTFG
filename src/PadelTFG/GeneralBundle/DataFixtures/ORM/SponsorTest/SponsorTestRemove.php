<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\SponsorTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Sponsor;


class SponsorTestRemove implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$repository = $manager->getRepository('GeneralBundle:Sponsor');

		$Sponsors = array(
			array('name' => 'Sponsor TFG'),
			array('name' => 'Sponsor TFG2'),
			array('name' => 'Sponsor TFG3'),
			array('name' => 'Sponsor TFG4'),
			array('name' => 'Sponsor TFG5'),
			array('name' => 'Sponsor POST'),
			array('name' => 'Sponsor TFGModify'),
			array('name' => 'Sponsor TFGDELETE')
		);

		foreach ($Sponsors as $key) {
			
			$entity = $repository->findOneByName($key['name']);
			if ($entity instanceof Sponsor) {

				$manager->remove($entity);
			}	
		}
		$manager->flush();
	}
}
