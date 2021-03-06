<?php

namespace PadelTFG\GeneralBundle\Service;

use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;
use PadelTFG\GeneralBundle\Entity\User;
use PadelTFG\GeneralBundle\Service\StatusService as StatusService;
use PadelTFG\GeneralBundle\Service\PairService as PairService;
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

    public function isTournamentAdmin($user){
        foreach ($user->getRoles() as $role) {
            if($role == Literals::TournamentAdmin){
                return true;
            }
        }
        return false;
    }

    public function getUserRoles($id){
        $sql = "SELECT ur.id, ur.value FROM user_userrole urr, userRole ur WHERE urr.userrole_id=ur.id AND urr.user_id=" . $id;
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        $roles = $stmt->fetchAll();
        return $roles;
    }

    public function getUserByEmail($email){
        $repository = $this->em->getRepository('GeneralBundle:User');
        $user = $repository->findOneByEmail($email);
        return $user;
    }

    private function setUserSave($user, $params){
        $user->setName(isset($params['name']) ? $params['name'] : '');
        $user->setLastName(isset($params['lastName']) ? $params['lastName'] : '');
        $user->setEmail(isset($params['email']) ? $params['email'] : '');
        $user->setPassword(isset($params['password']) ? $params['password'] : '');
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
        $user->setGender(isset($params['gender']) ? $params['gender'] : '');

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
        $user->setGender(isset($params['gender']) ? $params['gender'] : $user->getGender());

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
        $pairService = new PairService();
        $pairService->setManager($this->em);
        $pairService->modifyPairGender($user->getId());
        return array('result' => 'ok', 'message' => $user);
    }

    public function deleteUser($user){
        $userPreference = new UserPreferenceService();
        $userPreference->setManager($this->em);
        $userPre = $userPreference->getUserPreference($user->getId());
        $this->em->remove($user);
        $this->em->flush();
        if(!is_null($userPre)){
            $userPreference->deleteUserPreference($user->getId());
        }
    }

    public function loginUser($email, $password){
        $user = $this->getUserByEmail($email);
        if ($user instanceof User) {
            if($user->isPassEqual($password)){
                return array('result' => 'ok', 'message' => $user->getId());
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
