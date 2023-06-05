<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
	private $_id;

	/**
	 * 验证用户账号密码.
	 * @return boolean
	 */
	public function authenticate()
	{
        $model = AdminUser::model();
		$user=$model->find('LOWER(username)=?',array(strtolower($this->username)));
		if($user===null){//用户名验证
            $model->addError('username', '用户名不存在');
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }elseif(!$user->validatePassword($this->password)){//调用用户model的方法验证密码
            $model->addError('password', '密码不正确');
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }else{//账号密码均正确
            $this->_id=$user->id;
            $this->username=$user->username;
            $this->errorCode=self::ERROR_NONE;
        }

        //返回用户名密码验证的结果跟约定正确的返回值是否相等
		return $this->errorCode==self::ERROR_NONE;
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
}