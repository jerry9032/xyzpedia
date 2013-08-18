<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	public $user;
	private $_id;

	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		Yii::import('application.extensions.PasswordHash');

		$this->user = User::model()->findByAttributes(array('login' => $this->username));
		if(!$this->user)
		{
			$this->errorCode = self::ERROR_USERNAME_INVALID;
			return $this->errorCode;
		}

		$passHasher = new PasswordHash(8, true);
		$correct = $passHasher->CheckPassword($this->password, $this->user->pass);

		if(!$correct)
			$this->errorCode =self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->errorCode = self::ERROR_NONE;
			$this->_id = $this->user->id;
		}
			
		return $this->errorCode;
	}

	public function getId()
	{
		return $this->_id;
	}

}