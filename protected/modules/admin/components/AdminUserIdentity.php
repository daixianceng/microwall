<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class AdminUserIdentity extends CUserIdentity
{
	private $_id;
	
	public function authenticate()
	{
		$user = User::model()->find('name=:username', array(':username' => $this->username));
		if ($user) {
			if ($user->password === User::encrypt($this->name, $this->password)) {
				$this->_id = $user->id;
				$this->setState('nickname', $user->nickname);
				$this->setState('avatar', $user->avatar);
				$this->setState('description', $user->description);
				$this->errorCode = self::ERROR_NONE;
			} else {
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
				$this->errorMessage = 'The password invalid.';
			}
		} else {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
			$this->errorMessage = 'The username invalid.';
		}
		
		return $this->getIsAuthenticated();
	}
	
	public function getId()
	{
		return $this->_id;
	}
}