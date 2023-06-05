<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    protected $_userid;

    public function authenticate()
    {
        $user = User::model()->find('username=?', array($this->username));
        if($user === null){
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }elseif($user->password !== md5($this->password)){
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }else{
            $this->errorCode=self::ERROR_NONE;
            $this->_userid = $user->userid;
        }
        return !$this->errorCode;
    }

    function getId()
    {
        return $this->_userid;
    }

}