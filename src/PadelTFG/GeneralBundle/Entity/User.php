<?php

namespace PadelTFG\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JsonSerializable;

/**
* @ORM\Entity
* @ORM\HasLifecycleCallbacks()
* @UniqueEntity("email")
*/

class User implements JsonSerializable
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue */
	protected $id;

	/** @ORM\Column(type="string", length=100)
		@Assert\NotBlank() */
	protected $name;

	/** @ORM\Column(type="string", length=100)
		@Assert\NotBlank() */
	protected $lastName;

	/** @ORM\Column(type="string", length=9, nullable=true) */
	protected $firstPhone;

	/** @ORM\Column(type="string", length=9, nullable=true) */
	protected $secondPhone;

	/** @ORM\Column(type="string", length=100)
		@Assert\NotBlank()
		@Assert\Email() */
	protected $email;

	/** @ORM\Column(type="string", length=200, nullable=true) */
	protected $address;

	/** @ORM\Column(type="string", length=100, nullable=true) */
	protected $city;

	/** @ORM\Column(type="string", length=100, nullable=true) */
	protected $country;

	/** @ORM\Column(type="string", length=10, nullable=true) */
	protected $cp;

	/** @ORM\Column(type="string", nullable=true)
	@Assert\Choice(callback = {"PadelTFG\GeneralBundle\Resources\globals\Utils", "getUserGenders"}, message = "Choose a valid gender.") */
	protected $gender;

	/** @ORM\Column(type="string", length=500)
		@Assert\NotBlank() */
	protected $password;

	/** @ORM\Column(type="string", length=500, nullable=true) */
	protected $salt;

	/** @ORM\ManyToOne(targetEntity="PadelTFG\GeneralBundle\Entity\UserStatus") */
	protected $status;

	/**
     * @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\UserRole")
     * @ORM\JoinTable(name="user_userrole",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="userrole_id", referencedColumnName="id")}
     *      )
     */
	protected $roles;

	/** @ORM\ManyToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Notification", mappedBy="user") */
	protected $notification;

	/** @ORM\Column(type="datetime") */
	protected $registrationDate;

	/** @ORM\Column(type="datetime", nullable=true) */
	protected $birthDate;	

	/** @ORM\Column(type="blob", nullable=true) */
	protected $profileImage;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $gameLevel;

	/** @ORM\Column(type="string", length=50, nullable=true) */
	protected $alias;

	/** @ORM\OneToMany(targetEntity="PadelTFG\GeneralBundle\Entity\Tournament", mappedBy="user") */
	protected $tournament;

	public function __construct(){
		$this->notification = new \Doctrine\Common\Collections\ArrayCollection();
		$this->tournament = new \Doctrine\Common\Collections\ArrayCollection();
		$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
		$this->registrationDate = new \DateTime();
		$this->salt = $this->registrationDate->format('Y-m-d H:i:s') . 'PadelTFG';
	}

	/**
	 * @ORM\PrePersist
	 */
	public function setCreatedAtValue()
	{
	    $this->password = password_hash($this->password, PASSWORD_DEFAULT, array('salt' => $this->salt));
	}

	public function isPassEqual($pass){

		$equals = password_verify($pass, $this->password);
 
		if ($equals) {
		    return true;
		} else {
		    return false;
		}
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
	public function getGender(){
		return $this->gender;
	}
	public function getPassword(){
		return $this->password;
	}
	public function getSalt(){
		return $this->salt;
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
	public function setGender($gender){
		$this->gender = $gender;
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
	public function setBirthDate($birthDate){
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
	public function addRole($role){
		$this->roles[] = $role;
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
            'gender'=> $this->gender,
            'status' => $this->status,
            'notification' => $this->notification,
            'registrationDate' => $this->registrationDate,
            'birthDate' => $this->birthDate,
            'gameLevel' => $this->gameLevel,
            'alias' => $this->alias,
        );
    }
}
