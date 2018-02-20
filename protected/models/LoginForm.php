<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $user_id;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that user_id and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// user_id and password are required
			array('user_id, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		    
		if(!$this->hasErrors())
		{
			$this->_identity = new UserIdentity($this->user_id,$this->password);
			
			if(!$this->_identity->authenticate())
			{
				switch ($this->_identity->errorCode) 
				{
					case UserIdentity::ERROR_PASSWORD_INVALID:
						$this->addError('password','Salah password!');
						break; 
					case UserIdentity::ERROR_USER_EXPIRED:
						$this->addError('user_id','Password expired, silahkan melakukan change password!');	
						break;
					case UserIdentity::ERROR_USER_SUSPEND_WRONG_3X:
						$this->addError('user_id','3x salah password, user disuspend!');
						break;
					case UserIdentity::ERROR_USER_SUSPEND:
						$this->addError('user_id','User disuspend!');
						break;
					case UserIdentity::ERROR_USER_NOUSERGROUP:
						$this->addError('user_id','User belum diberikan akses!');
						break;
					case UserIdentity::ERROR_USER_ALREADYLOGIN:
						$this->addError('user_id','User sudah login di tempat lain, harap logout terlebih dahulu!');
						break;
					case UserIdentity::ERROR_USER_NOT_APPROVED:
						$this->addError('user_id','User belum diapprove!');
						break;
					default:
						$this->addError('user_id','Salah user id!');	
						break;
				}
                    
			}
				
		}
	}

	/**
	 * Logs in the user using the given user_id and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null){
			$this->_identity=new UserIdentity($this->user_id,$this->password);
			$this->_identity->authenticate();
		}
        
        if($this->_identity->errorCode === UserIdentity::ERROR_NONE)
		{
			$duration=3600*24; // 24 hours
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
