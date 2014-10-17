<?php
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe = false;
	
	public function rules()
	{
		return array(
			array('username, password', 'required'),
			array('username', 'length', 'min' => 3, 'max' => 16),
			array('username', 'match', 'pattern' => '/^[0-9a-z_-]+$/i'),
			array('password', 'length', 'min' => 6, 'max' => 20),
			array('password', 'match', 'pattern' => '/^\S+$/i'),
			array('password', 'authenticate'),
			array('rememberMe', 'boolean')
		);
	}
	
	public function authenticate($attribute, $params)
	{
		$identity = new AdminUserIdentity($this->username, $this->password);
		
		if ($identity->authenticate())
			Yii::app()->user->login($identity, $this->rememberMe ? 3600 * 24 * 7 : 0);
		else
			$this->addError($attribute, $identity->errorMessage);
	}
}