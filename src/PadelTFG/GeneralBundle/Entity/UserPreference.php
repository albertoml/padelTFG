<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
* @ORM\Entity
* @ORM\HasLifecycleCallbacks()
*/

class UserPreference implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer") */
	protected $id;

	/** @ORM\Column(type="boolean") */
	protected $name;

	/** @ORM\Column(type="boolean") */
	protected $lastName;

	/** @ORM\Column(type="boolean") */
	protected $firstPhone;

	/** @ORM\Column(type="boolean") */
	protected $secondPhone;

	/** @ORM\Column(type="boolean") */
	protected $email;

	/** @ORM\Column(type="boolean") */
	protected $address;

	/** @ORM\Column(type="boolean") */
	protected $city;

	/** @ORM\Column(type="boolean") */
	protected $country;

	/** @ORM\Column(type="boolean") */
	protected $cp;

	/** @ORM\Column(type="boolean") */
	protected $status;

	/** @ORM\Column(type="boolean") */
	protected $role;

	/** @ORM\Column(type="boolean") */
	protected $registrationDate;

	/** @ORM\Column(type="boolean") */
	protected $birthDate;	

	/** @ORM\Column(type="boolean") */
	protected $gameLevel;

	/** @ORM\Column(type="boolean") */
	protected $alias;

	/** @ORM\Column(type="boolean") */
	protected $profileImage;

	/** @ORM\Column(type="boolean") */
	protected $gender;

	
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
	public function getStatus(){
		return $this->status;
	}
	public function getRole(){
		return $this->role;
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
	public function getGender(){
		return $this->gender;
	}
	public function getGameLevel(){
		return $this->gameLevel;
	}
	public function getAlias(){
		return $this->alias;
	}

	public function setId($id){
		$this->id = $id;
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
	public function setRole($role){
		$this->role = $role;
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
	public function setGender($gender){
		$this->gender = $gender;
	}

	public function jsonSerialize()
    {
        return array(
        	'id' => $this->id,
            'name' => $this->name,
            'lastName' => $this->lastName,
            'firstPhone' => $this->firstPhone,
            'secondPhone' => $this->secondPhone,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'cp'=> $this->cp,
            'status' => $this->status,
            'role' => $this->role,
            'registrationDate' => $this->registrationDate,
            'birthDate' => $this->birthDate,
            'gameLevel' => $this->gameLevel,
            'alias' => $this->alias,
            'profileImage' => $this->profileImage,
            'gender' => $this->gender
        );
    }
}
