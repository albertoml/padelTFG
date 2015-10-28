<?php

namespace PadelTFG\GeneralBundle\DataFixtures\ORM\SponsorTest;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PadelTFG\GeneralBundle\Entity\Sponsor;


class SponsorTestInsert implements FixtureInterface
{
	public function load(ObjectManager $manager){

		$Sponsors = array(
			array('name' => 'Sponsor TFG', 'cif' => '111111111A'),
			array('name' => 'Sponsor TFG2', 'cif' => '222222222B'),
			array('name' => 'Sponsor TFG3', 'cif' => '333333333C'),
			array('name' => 'Sponsor TFG4', 'cif' => '444444444D'),
			array('name' => 'Sponsor TFG5', 'cif' => '555555555E'),
			array('name' => 'Sponsor TFGDELETE', 'cif' => '666666666F')
			);

		foreach ($Sponsors as $key) {
			$entity = new Sponsor();

			$entity->setCif($key['cif']);
			$entity->setName($key['name']);

			$manager->persist($entity);	
		}

		$manager->flush();
	}
}
