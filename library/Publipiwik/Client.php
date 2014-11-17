<?php
/**
 * Wrapper for Piwik Client entity
**/
class Publipiwik_Client extends Publipiwik_Abstract{
	
	protected static $__CLASS__ = __CLASS__; // Static attribute must be implemented for "Singleton Pattern"

	public function addUser($userLogin, $password, $email, $alias = null){
		//UsersManager.addUser
		if(is_null($alias))
			$alias = $userLogin;

		if(!$this->validate($email,$password))
			return array("error", "your parameters appear to be false");

		return $this->client->call("UsersManager.addUser", array(
			"userLogin" => $userLogin,
			"password" => $password,
			"email" => $email,
			"alias" => $alias
		));
	}
	
	public function updateUser($userLogin, $password, $email, $alias = null){
		//UsersManager.addUser
		if(is_null($alias))
			$alias = $userLogin;

		$params = array(
			"userLogin" => $userLogin,
			"alias" => $alias
		);

		if(!empty($password) and $this->validatePassword($password))
			$params["password"] = $password;

		if(!empty($email) and $this->validateEmail($email))
			$params["email"] = $email;

		return $this->client->call("UsersManager.updateUser", $params);
	}

	public function getUser($userLogin){
		return $this->client->call("UsersManager.getUsers", array(
			"userLogins" => $userLogin,
		));
	}

	public function checkUserExist($userLogin){
		$res = $this->getUser($userLogin);
		return (!empty($res));
	}

	public function setUserForSite($user, $access, $idSite){
		$this->client->call("UsersManager.setUserAccess",array(
			"userLogin" => $user,
			"access" => $access,
			"idSites" => $idSite
		));
	}

	public function getSitesAccess($user){
		return $this->client->call("UsersManager.getSitesAccessFromUser",array(
			"userLogin" => $user
		));
	}

	public function isSuperAdmin($token){
		$currentToken = $this->client->getToken();
		$this->client->setToken($token);
		$r= $this->client->call("UsersManager.hasSuperUserAccess",array());
		$this->client->setToken($currentToken);
		return $r;
	}
	
	protected function validate($email,$password){
		return ($this->validateEmail($email) && $this->validatePassword($password));
	}

	protected function validateEmail($email){
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
		return preg_match($regex, $email);
	}

	protected function validatePassword($password){
		return (strlen($password) >= 6 and strlen($password) <= 26)?true:false;
	}
}