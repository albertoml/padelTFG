<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
*/

class User
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=100) */
	protected $name;

	/** @ORM\Column(type="string", length=100) */
	protected $lastName;

	/** @ORM\Column(type="string", length=9) */
	protected $firstPhone;

	/** @ORM\Column(type="string", length=9) */
	protected $secondPhone;

	/** @ORM\Column(type="string", length=100) */
	protected $email;

	/** @ORM\Column(type="string", length=200) */
	protected $address;

	/** @ORM\Column(type="string", length=100) */
	protected $city;

	/** @ORM\Column(type="string", length=100) */
	protected $country;

	/** @ORM\Column(type="string", length=10) */
	protected $cp;

	/** @ORM\Column(type="string", length=50) */
	protected $user;

	/** @ORM\Column(type="string", length=500) */
	protected $password;

	/** @ORM\Column(type="string", length=500) */
	protected $salt;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\UserStatus") */
	protected $status;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Notification", mappedBy="user") */
	protected $notification;

	/** @ORM\Column(type="datetime") */
	protected $registrationDate;

	/** @ORM\Column(type="datetime") */
	protected $birthDate;	

	/** @ORM\Column(type="blob") */
	protected $profileImage;

	/** @ORM\Column(type="integer") */
	protected $gameLevel;

	/** @ORM\Column(type="string", length=50) */
	protected $alias;

	public function __construct(){
		$this->notification = new \Doctrine\Common\Collections\ArrayCollection();
		$this->registrationDate = new \DateTime();
	}

	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getLastName(){
		return $this->lastName;
	}
	public function getFirstPhone(){
		return $this->firstPhone;
	}
	public function getSecondPhone(){
		return $this->secondPhone;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getAddress(){
		return $this->address;
	}
	public function getCity(){
		return $this->city;
	}
	public function getCountry(){
		return $this->country;
	}
	public function getCP(){
		return $this->cp;
	}
	public function getUser(){
		return $this->user;
	}
	public function getPassword(){
		return $this->$password;
	}
	public function getSalt(){
		return $this->$salt;
	}
	public function getStatus(){
		return $this->status;
	}
	public function getNotification(){
		return $this->notification;
	}
	public function getRegistrationDate(){
		return $this->registrationDate;
	}
	public function getBirthDate(){
		return $this->birthDate;
	}
	public function getProfileImage(){
		return $this->profileImage;
	}
	public function getGameLevel(){
		return $this->gameLevel;
	}
	public function getAlias(){
		return $this->alias;
	}

	public function setName($name){
		$this->name = $name;
	}
	public function setLastName($lastName){
		$this->lastName = $lastName;
	}
	public function setFirstPhone($firstPhone){
		$this->firstPhone = $firstPhone;
	}
	public function setSecondPhone($secondPhone){
		$this->secondPhone = $secondPhone;
	}
	public function setEmail($email){
		$this->email = $email;
	}
	public function setAddress($address){
		$this->address = $address;
	}
	public function setCity($city){
		$this->city = $city;
	}
	public function setCountry($country){
		$this->country = $country;
	}
	public function setCP($cp){
		$this->cp = $cp;
	}
	public function setUser($user){
		$this->user = $user;
	}
	public function setPassword($password){
		$this->password = $password;
	}
	public function setSalt($salt){
		$this->salt = $salt;
	}
	public function setStatus($status){
		$this->status = $status;
	}
	public function setNotification($notification){
		$this->notification = $notification;
	}
	public function setRegistrationDate($registrationDate){
		$this->registrationDate = $registrationDate;
	}
	public function setbirthDate($birthDate){
		$this->birthDate = $birthDate;
	}
	public function setProfileImage($profileImage){
		$this->profileImage = $profileImage;
	}
	public function setGameLevel($gameLevel){
		$this->gameLevel = $gameLevel;
	}
	public function setAlias($alias){
		$this->alias = $alias;
	}
}
