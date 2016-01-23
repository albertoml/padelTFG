<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\UserPreferenceService as UserPreferenceService;
 
class UserService{

    var $em;
    var $statusService;

    function __construct(){ 
        $this->statusService = new StatusService();
    } 

    public function setManager($em){ 
        $this->em = $em;
        $this->statusService->setManager($em);
    } 

	public function allUsers(){
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findAll();
        return $user;
    }

    public function getUser($id){
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->find($id);
        return $user;
    }

    public function getUserByEmail($email){
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByEmail($email);
        return $user;
    }

    private function setUserSave($user, $params){
        $user->setName($params['name']);
        $user->setLastName($params['lastName']);
        $user->setEmail($params['email']);
        $user->setPassword($params['password']);
        $user->setStatus($this->statusService->getStatus('user', 'Registered'));
        $user->setFirstPhone(isset($params['firstPhone']) ? $params['firstPhone'] : '');
        $user->setSecondPhone(isset($params['secondPhone']) ? $params['secondPhone'] : '');
        $user->setAddress(isset($params['address']) ? $params['address'] : '');
        $user->setCity(isset($params['city']) ? $params['city'] : '');
        $user->setCountry(isset($params['country']) ? $params['country'] : '');
        $user->setCP(isset($params['cp']) ? $params['cp'] : '');
        $user->setBirthDate(isset($params['birthDate']) ? $params['birthDate'] : null);
        $user->setProfileImage(isset($params['profileImage']) ? $params['profileImage'] : '');
        $user->setGameLevel(isset($params['gameLevel']) ? $params['gameLevel'] : 0);
        $user->setAlias(isset($params['alias']) ? $params['alias'] : '');

        return $user;
    }

    public function saveUser($params, $controller){
        $user = new User();
        $user = $this->setUserSave($user, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $userPreference = new UserPreferenceService();
        $userPreference->setManager($this->em);
        $this->em->persist($user);
        $this->em->flush();
        $userPreference->saveUserPreference($user->getId());
        return array('result' => 'ok', 'message' => $user);
    }

    private function setUserModify($user, $params){
        $user->setName(isset($params['name']) ? $params['name'] : $user->getName());
        $user->setLastName(isset($params['lastName']) ? $params['lastName'] : $user->getLastName());
        $user->setEmail(isset($params['email']) ? $params['email'] : $user->getEmail());
        $user->setPassword(isset($params['password']) ? $params['password'] : $user->getPassword());
        $user->setFirstPhone(isset($params['firstPhone']) ? $params['firstPhone'] : $user->getFirstPhone());
        $user->setSecondPhone(isset($params['secondPhone']) ? $params['secondPhone'] : $user->getSecondPhone());
        $user->setAddress(isset($params['address']) ? $params['address'] : $user->getAddress());
        $user->setCity(isset($params['city']) ? $params['city'] : $user->getCity());
        $user->setCountry(isset($params['country']) ? $params['country'] : $user->getCountry());
        $user->setCP(isset($params['cp']) ? $params['cp'] : $user->getCP());
        $user->setBirthDate(isset($params['birthDate']) ? $params['birthDate'] : $user->getBirthDate());
        $user->setProfileImage(isset($params['profileImage']) ? $params['profileImage'] : $user->getProfileImage());
        $user->setGameLevel(isset($params['gameLevel']) ? $params['gameLevel'] : $user->getGameLevel());
        $user->setAlias(isset($params['alias']) ? $params['alias'] : $user->getAlias());

        return $user;
    }

    public function modifyUser($user, $params, $controller){
        $user = $this->setUserModify($user, $params);
        $validator = $controller->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return array('result' => 'fail', 'message' => $errorsString);
        }
        $this->em->persist($user);
        $this->em->flush();
        return array('result' => 'ok', 'message' => $user);
    }

    public function deleteUser($user){
        $this->em->remove($user);
        $this->em->flush();
    }

    public function getUserRoleByUser($user){
        $repository = $this->em->getRepository('GeneralBundle:UserUserRole');
        $roles = $repository->findByUser($user);
        $rolesToSend = [];
        foreach ($roles as $role) {
            array_push($rolesToSend, $role->getRole());
        }
        return $rolesToSend;
    }

    public function loginUser($email, $password){
        $user = $this->getUserByEmail($email);
        if ($user instanceof User) {
            if($user->isPassEqual($password)){
                $roles = $this->getUserRoleByUser($user);
                $userToSend = array('user' => $user, 'roles' => $roles);
                return array('result' => 'ok', 'message' => $userToSend);
            }
            else{
                return array('result' => 'fail', 'message' => Literals::PasswordIncorrect);
            }
        }
        else{
            return array('result' => 'fail', 'message' => Literals::UserIncorrect);
        }
    }

    public function searchUser($name){

        $result = $this->em->getRepository("GeneralBundle:User")->createQueryBuilder('u')
       ->where('u.name LIKE :name')
       ->setParameter('name', '%'.$name.'%')
       ->getQuery()
       ->getResult();
        return $result;
    }
}
